<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_payments', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_txn_no', 40)->unique();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->string('form_type', 60);
            $table->string('registration_number', 40)->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency_code', 5)->default('356');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_mobile', 20);
            $table->string('addl_param1', 120)->nullable();
            $table->string('addl_param2', 120)->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->string('response_code', 30)->nullable();
            $table->string('response_description')->nullable();
            $table->string('payment_mode', 40)->nullable();
            $table->string('payment_sub_inst_type')->nullable();
            $table->string('payment_id', 40)->nullable()->index();
            $table->string('txn_id', 40)->nullable()->index();
            $table->string('tran_ctx')->nullable();
            $table->string('redirect_uri')->nullable();
            $table->string('payment_datetime', 20)->nullable();
            $table->json('initiate_request')->nullable();
            $table->json('initiate_response')->nullable();
            $table->json('callback_response')->nullable();
            $table->json('status_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_payments');
    }
};
