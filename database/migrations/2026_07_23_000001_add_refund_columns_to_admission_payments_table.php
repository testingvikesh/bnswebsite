<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_payments', function (Blueprint $table) {
            $table->string('refund_merchant_txn_no', 64)->nullable()->after('status_response');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_merchant_txn_no');
            $table->string('refund_status', 40)->nullable()->after('refund_amount');
            $table->string('refund_response_code', 40)->nullable()->after('refund_status');
            $table->string('refund_response_description', 255)->nullable()->after('refund_response_code');
            $table->json('refund_request')->nullable()->after('refund_response_description');
            $table->json('refund_response')->nullable()->after('refund_request');
            $table->json('refund_status_response')->nullable()->after('refund_response');
            $table->timestamp('refunded_at')->nullable()->after('refund_status_response');
        });
    }

    public function down(): void
    {
        Schema::table('admission_payments', function (Blueprint $table) {
            $table->dropColumn([
                'refund_merchant_txn_no',
                'refund_amount',
                'refund_status',
                'refund_response_code',
                'refund_response_description',
                'refund_request',
                'refund_response',
                'refund_status_response',
                'refunded_at',
            ]);
        });
    }
};
