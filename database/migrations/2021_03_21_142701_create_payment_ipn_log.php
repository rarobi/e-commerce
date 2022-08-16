<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentIpnLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_ipn_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->string('order_validation_status')->nullable();
            $table->string('tran_date')->nullable();
            $table->string('tran_id')->nullable();
            $table->string('val_id')->nullable();
            $table->double('amount')->nullable();
            $table->double('store_amount')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_no')->nullable();
            $table->string('currency')->nullable();
            $table->string('bank_tran_id')->nullable();
            $table->string('card_issuer')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_issuer_country')->nullable();
            $table->double('currency_amount')->nullable();
            $table->string('verify_sign')->nullable();
            $table->string('verify_key')->nullable();
            $table->boolean('risk_level')->nullable(0);
            $table->string('risk_title')->nullable();
            $table->integer('request_id')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('platform')->nullable();
            $table->string('provider_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_ipn_log');
    }
}
