<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReceiptTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('total');
            $table->string('payment_reference_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
        DB::unprepared('ALTER TABLE receipts AUTO_INCREMENT = 1000;');

        Schema::create('receipt_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->unsigned();
            $table->foreign('receipt_id')->references('id')->on('receipts');
            $table->string('sku');
            $table->string('description');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 6, 2)->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('receipt_items');
        Schema::drop('receipts');
    }
}
