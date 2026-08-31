<?php

namespace App\Services;

use App\Models\AdmissionPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class IciciPaymentGatewayService
{
    /** @var array<string, mixed> */
    private array $config;

    public function __construct()
    {
        $this->config = config('payment.icici', []);
    }

    /** @return array<string, mixed> */
    public function buildInitiatePayload(AdmissionPayment $payment): array
    {
        $txnDate = now()->format('YmdHis');
        $amount = number_format((float) $payment->amount, 2, '.', '');

        $payload = [
            'merchantId' => $this->config['merchant_id'],
            'aggregatorID' => $this->config['aggregator_id'],
            'merchantTxnNo' => $payment->merchant_txn_no,
            'amount' => $amount,
            'currencyCode' => $payment->currency_code ?: $this->config['currency_code'],
            'payType' => $this->config['pay_type'],
            'customerEmailID' => $payment->customer_email,
            'transactionType' => 'SALE',
            'returnURL' => route('payment.callback'),
            'txnDate' => $txnDate,
            'customerMobileNo' => $this->digitsOnly($payment->customer_mobile),
            'customerName' => $payment->customer_name,
            'addlParam1' => $payment->addl_param1 ?: $payment->registration_number,
            'addlParam2' => $payment->addl_param2 ?: $payment->form_type,
        ];

        $payload['secureHash'] = $this->hashInitiateSale($payload);

        return $payload;
    }

    /** @return array<string, mixed> */
    public function initiateSale(AdmissionPayment $payment): array
    {
        if (trim((string) ($this->config['secret_key'] ?? '')) === '') {
            Log::error('ICICI initiateSale aborted: secret key missing', [
                'merchant_txn_no' => $payment->merchant_txn_no,
            ]);

            throw new RuntimeException('Payment gateway secret key is not configured. Please set ICICI_SECRET_KEY in .env.');
        }

        $payload = $this->buildInitiatePayload($payment);

        $response = Http::timeout(30)
            ->acceptJson()
            ->asJson()
            ->post($this->config['initiate_sale_url'], $payload);

        if (! $response->successful()) {
            Log::error('ICICI initiateSale HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merchant_txn_no' => $payment->merchant_txn_no,
            ]);

            throw new RuntimeException('Unable to connect to payment gateway. Please try again.');
        }

        /** @var array<string, mixed> $body */
        $body = $response->json() ?? [];

        Log::info('ICICI initiateSale response', [
            'merchant_txn_no' => $payment->merchant_txn_no,
            'responseCode' => $body['responseCode'] ?? null,
            'responseDescription' => $body['responseDescription'] ?? ($body['respdescription'] ?? null),
        ]);

        return [
            'request' => $payload,
            'response' => $body,
        ];
    }

    /** @return array<string, mixed> */
    public function checkStatus(AdmissionPayment $payment): array
    {
        $payload = [
            'merchantId' => $this->config['merchant_id'],
            'aggregatorID' => $this->config['aggregator_id'],
            'merchantTxnNo' => $payment->merchant_txn_no,
            'transactionType' => 'STATUS',
            'originalTxnNo' => $payment->merchant_txn_no,
        ];

        $payload['secureHash'] = $this->hashStatusCheck($payload);

        return $this->postCommand($payload, $payment->merchant_txn_no, 'status check');
    }

    /**
     * Check STATUS for a refund (or any) merchant transaction number.
     *
     * @return array<string, mixed>
     */
    public function checkRefundStatus(AdmissionPayment $payment): array
    {
        $txnNo = trim((string) ($payment->refund_merchant_txn_no ?: $payment->merchant_txn_no));

        $payload = [
            'merchantId' => $this->config['merchant_id'],
            'aggregatorID' => $this->config['aggregator_id'],
            'merchantTxnNo' => $txnNo,
            'transactionType' => 'STATUS',
            'originalTxnNo' => $txnNo,
        ];

        $payload['secureHash'] = $this->hashStatusCheck($payload);

        return $this->postCommand($payload, $txnNo, 'refund status check');
    }

    /**
     * Initiate a REFUND against a successful payment.
     *
     * @return array<string, mixed>
     */
    public function refund(AdmissionPayment $payment, string $amount, string $refundMerchantTxnNo): array
    {
        if (trim((string) ($this->config['secret_key'] ?? '')) === '') {
            throw new RuntimeException('Payment gateway secret key is not configured. Please set ICICI_SECRET_KEY in .env.');
        }

        $amount = number_format((float) $amount, 2, '.', '');
        $originalTxnNo = (string) ($payment->merchant_txn_no ?: '');

        if ($originalTxnNo === '') {
            throw new RuntimeException('Original merchant transaction number is missing for this payment.');
        }

        $payload = [
            'merchantId' => $this->config['merchant_id'],
            'aggregatorID' => $this->config['aggregator_id'],
            'merchantTxnNo' => $refundMerchantTxnNo,
            'amount' => $amount,
            'transactionType' => 'REFUND',
            'originalTxnNo' => $originalTxnNo,
        ];

        $payload['secureHash'] = $this->hashRefund($payload);

        $result = $this->postCommand($payload, $refundMerchantTxnNo, 'refund');

        Log::info('ICICI refund response', [
            'original_txn_no' => $originalTxnNo,
            'refund_merchant_txn_no' => $refundMerchantTxnNo,
            'amount' => $amount,
            'responseCode' => $result['response']['responseCode'] ?? null,
            'responseDescription' => $result['response']['responseDescription'] ?? ($result['response']['respdescription'] ?? null),
        ]);

        return $result;
    }

    public function redirectUrl(array $initiateResponse): ?string
    {
        $redirectUri = $initiateResponse['redirectURI'] ?? null;
        $tranCtx = $initiateResponse['tranCtx'] ?? null;

        if (! $redirectUri || ! $tranCtx) {
            return null;
        }

        $separator = str_contains($redirectUri, '?') ? '&' : '?';

        return $redirectUri.$separator.'tranCtx='.urlencode((string) $tranCtx);
    }

    public function isInitiateSuccess(array $response): bool
    {
        return ($response['responseCode'] ?? null) === ($this->config['initiate_success_code'] ?? 'R1000');
    }

    public function isPaymentSuccess(array $response): bool
    {
        $code = (string) ($response['responseCode'] ?? '');

        return in_array($code, $this->config['success_response_codes'] ?? ['0000', '000', '0000/000'], true);
    }

    public function isRefundSuccess(array $response): bool
    {
        $code = (string) ($response['responseCode'] ?? '');

        return in_array($code, $this->config['refund_success_codes'] ?? ['0000', '000', '0000/000', 'R1000'], true);
    }

    /** @param array<string, mixed> $payload */
    public function hashInitiateSale(array $payload): string
    {
        $order = [
            'addlParam1',
            'addlParam2',
            'aggregatorID',
            'amount',
            'currencyCode',
            'customerEmailID',
            'customerMobileNo',
            'customerName',
            'merchantId',
            'merchantTxnNo',
            'payType',
            'returnURL',
            'transactionType',
            'txnDate',
        ];

        return $this->secureHash($payload, $order);
    }

    /** @param array<string, mixed> $payload */
    public function hashStatusCheck(array $payload): string
    {
        $order = [
            'aggregatorID',
            'merchantId',
            'merchantTxnNo',
            'originalTxnNo',
            'transactionType',
        ];

        return $this->secureHash($payload, $order);
    }

    /** @param array<string, mixed> $payload */
    public function hashRefund(array $payload): string
    {
        $order = [
            'aggregatorID',
            'amount',
            'merchantId',
            'merchantTxnNo',
            'originalTxnNo',
            'transactionType',
        ];

        return $this->secureHash($payload, $order);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{request: array<string, mixed>, response: array<string, mixed>}
     */
    private function postCommand(array $payload, string $txnNo, string $actionLabel): array
    {
        $response = Http::timeout(30)
            ->acceptJson()
            ->asJson()
            ->post($this->config['status_check_url'], $payload);

        if (! $response->successful()) {
            Log::warning('ICICI '.$actionLabel.' HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merchant_txn_no' => $txnNo,
            ]);

            return [
                'request' => $payload,
                'response' => ['error' => $response->body()],
            ];
        }

        /** @var array<string, mixed> $body */
        $body = $response->json() ?? [];

        return [
            'request' => $payload,
            'response' => $body,
        ];
    }

    /** @param array<string, mixed> $payload */
    private function secureHash(array $payload, array $fieldOrder): string
    {
        $hashText = '';

        foreach ($fieldOrder as $field) {
            $hashText .= (string) ($payload[$field] ?? '');
        }

        return hash_hmac('sha256', $hashText, (string) $this->config['secret_key']);
    }

    private function digitsOnly(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?: $value;
    }
}
