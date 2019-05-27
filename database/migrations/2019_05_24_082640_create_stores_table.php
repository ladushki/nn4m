<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_number');
            $table->bigInteger('address_id')->unsigned();
            $table->string('name');
            $table->string('site_id');
            $table->string('phone_number');
            $table->string('manager')->nullable();
            $table->string('cfslocation')->nullable();
            $table->string('delivery_lead_time')->nullable();
            $table->boolean('cfs_flag');
            $table->json('standardhours');
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
        Schema::dropIfExists('stores');
    }
}
