<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('provider_type')->nullable();
            $table->string('provider_slug')->nullable();
            $table->string('store_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('provider_info');
    }
}
