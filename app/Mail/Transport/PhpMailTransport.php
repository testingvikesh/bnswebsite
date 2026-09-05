<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class PhpMailTransport extends AbstractTransport
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $from = $email->getFrom()[0] ?? null;
        $fromAddress = $from?->getAddress() ?: 'info@businessnavacharschool.com';
        $fromName = $from?->getName() ?: 'Business Navachar School';

        $to = implode(', ', array_map(
            static fn (Address $address) => $address->getAddress(),
            $email->getTo()
        ));

        if ($to === '') {
            throw new \RuntimeException('PHP mail() has no recipient.');
        }

        $subject = (string) $email->getSubject();
        $body = (string) ($email->getHtmlBody() ?: $email->getTextBody() ?: '');
        $encodedSubject = '=?UTF-8?B?'.base64_encode($subject).'?=';

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: '.$this->headerAddress($fromAddress, $fromName),
        ];

        $replyTo = $email->getReplyTo();
        if ($replyTo !== []) {
            $headers[] = 'Reply-To: '.implode(', ', array_map(
                fn (Address $address) => $this->headerAddress($address->getAddress(), $address->getName()),
                $replyTo
            ));
        }

        if (function_exists('ini_set')) {
            @ini_set('sendmail_from', $fromAddress);
        }

        $ok = @mail(
            $to,
            $encodedSubject,
            $body,
            implode("\r\n", $headers),
            '-f'.$fromAddress
        );

        if (! $ok) {
            throw new \RuntimeException('PHP mail() could not send the message.');
        }
    }

    public function __toString(): string
    {
        return 'php';
    }

    private function headerAddress(string $address, string $name = ''): string
    {
        $address = trim($address);
        $name = trim($name);
        if ($name === '') {
            return $address;
        }

        return '=?UTF-8?B?'.base64_encode($name).'?= <'.$address.'>';
    }
}
