<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stock_id');
            $table->bigInteger('product_id');
            $table->integer('qty');
            $table->integer('stock_qty');
            $table->float('pur_value');
            $table->float('sale_value');
            $table->boolean('status')->default(true);
            $table->boolean('is_stock')->default(false);
            $table->boolean('is_group')->default(false);
            $table->bigInteger('group_of')->default(0);
            $table->integer('group_qty')->default(0);
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
        Schema::dropIfExists('stock_details');
    }
}
