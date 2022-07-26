<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_timelines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->nullable();
            $table->string('stock_id')->nullable();
            $table->float('price')->nullable();
            $table->float('quantity')->nullable();
            $table->float('reserve')->nullable();
            $table->float('stock')->nullable();
            $table->float('salePrice')->nullable();
            $table->float('inTransit')->nullable();
            $table->float('stockDays')->nullable();
            $table->string('article')->nullable();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mc_timelines');
    }
};
