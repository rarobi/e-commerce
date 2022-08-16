<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_mobile');
            $table->string('platform');
            $table->float('transaction_price');

            $table->integer('provider_id');
            $table->string('provider_name');

            $table->string('store_id');
            $table->string('agent_code');
            $table->string('external_trx_id');
            $table->string('internal_trx_id');
            $table->string('bank_tran_id')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_no')->nullable();
            $table->string('card_issuer')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('verify_sign')->nullable();
            $table->text('verify_key')->nullable();

            $table->boolean('is_success')->default(false);
            $table->string('purchase_date');
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
        Schema::dropIfExists('sales_invoices');
    }
}
