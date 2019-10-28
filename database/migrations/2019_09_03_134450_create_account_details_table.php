<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('account_id');
            $table->bigInteger('invoice_id');
            $table->bigInteger('sender_id')->default(0);
            $table->string('desc');
            $table->string('memo')->default('មិនបានដាក់ជូន');
            $table->float('balance');
            $table->boolean('is_sender')->default(false);
            $table->boolean('is_receiver')->default(false);
            $table->boolean('status')->default(true);
            $table->boolean('is_return')->default(false);
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
        Schema::dropIfExists('account_details');
    }
}
