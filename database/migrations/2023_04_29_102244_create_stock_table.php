<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->increments('idstock');
            $table->integer('quantiter');
            $table->unsignedBigInteger('idproduit');
            $table->foreign('idproduit')->references('idproduit')->on('produits')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('idreception');
            $table->foreign('idreception')->references('idreception')->on('receptions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('stock');
    }
}
