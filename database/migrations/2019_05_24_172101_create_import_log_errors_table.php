<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportLogErrorsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_log_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_number');
            $table->bigInteger('import_log_id')->unsigned();
            $table->string('column_name');
            $table->text('description');
            $table->timestamps();

            $table->foreign('import_log_id')->references('id')->on('import_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_log_errors');
    }
}
