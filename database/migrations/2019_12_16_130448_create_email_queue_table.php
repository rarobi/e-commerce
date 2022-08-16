<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to');
            $table->string('cc')->nullable();
            $table->string('subject')->nullable();
            $table->longText('content')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('sending_status')->default(0);
            $table->dateTime('sent_at')->nullable();
            $table->integer('times_of_try')->default(0)->comment('Max Try Limit 9');
            $table->string('remarks')->nullable();

            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_archive')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('email_queue');
    }
}
