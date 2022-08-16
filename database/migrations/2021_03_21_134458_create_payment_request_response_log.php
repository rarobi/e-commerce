<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestResponseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_request_response_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->nullable();

            $table->string('product_category')->nullable();
            $table->string('product_name')->nullable();
            $table->double('request_price')->nullable();
            $table->string('currency')->nullable();

            $table->string('platform')->nullable();
            $table->string('provider_name')->nullable();
            $table->string('transaction_id')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            $table->integer('num_of_item')->nullable();

            $table->string('status')->nullable();
            $table->string('gateway_page_url')->nullable();
            $table->text('failed_reason')->nullable();
            $table->string('session_key')->nullable();
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
        Schema::dropIfExists('payment_request_response_log');
    }
}
