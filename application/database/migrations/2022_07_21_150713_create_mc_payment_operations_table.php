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
        Schema::create('mc_order_positions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

//            $table->uuid()->unique();
            $table->integer('order_id');

            $table->string('name')->nullable();
            $table->uuid('product_uuid')->nullable();
            $table->string('code')->nullable();
            $table->float('salePrices')->nullable();
            $table->float('buyPrice')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mc_payment_operations');
    }
};
