<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('playlist_id');
            $table->bigInteger('stock_detail_id');
            $table->bigInteger('qty');
            $table->bigInteger('unique_qty');
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
        Schema::dropIfExists('bundle_details');
    }
}
