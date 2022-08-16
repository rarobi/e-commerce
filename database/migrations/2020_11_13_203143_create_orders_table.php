<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('order_number')->nullable();
          $table->unsignedBigInteger('customer_id')->nullable();
          $table->unsignedBigInteger('shipping_id')->nullable();
          $table->unsignedBigInteger('shipped_by')->nullable();
          $table->unsignedBigInteger('payment_type_id')->nullable();
          $table->string('shipping_amount')->nullable();
          $table->decimal('tax_amount',12,2)->nullable();
          $table->decimal('discount_amount',12,2)->nullable();
          $table->decimal('total_amount',12,2)->nullable();
          $table->decimal('paid_amount',12,2)->nullable();
          $table->enum('payment_status',['uncompleted','completed'])->default('uncompleted');
          $table->text('payment_details')->nullable();
          $table->enum('order_status', ['processing','completed','cancelled'])->default('processing');
          $table->date('payment_date')->nullable();
          $table->date('shipped_date')->nullable();
          $table->tinyInteger('status')->default(0);
          $table->tinyInteger('is_archive')->default(0);
          $table->integer('created_by')->nullable();
          $table->integer('updated_by')->nullable();
          $table->integer('deleted_by')->nullable();
          $table->softDeletes();
          $table->timestamps();
          $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
