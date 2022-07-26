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
        Schema::table('mc_clients', function (Blueprint $table) {

            $table->index('uuid');
        });

        Schema::table('mc_order_positions', function (Blueprint $table) {

            $table->index('order_id');
            $table->index('product_uuid');
        });

        Schema::table('mc_orders', function (Blueprint $table) {

            $table->index('employee_uuid');
            $table->index('store_uuid');
            $table->index('retailStore');
            $table->index('moment');
        });

        Schema::table('mc_skus', function (Blueprint $table) {

            $table->index('uuid');
            $table->index('type');
        });

        Schema::table('mc_stocks', function (Blueprint $table) {

            $table->index('uuid');
        });

        Schema::table('mc_timelines', function (Blueprint $table) {

            $table->index('stock_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
