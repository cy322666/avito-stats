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
        Schema::create('ads_services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('finish_time');
            $table->json('schedule');
            $table->bigInteger('ads_id');
            $table->string('vas_id');

            $table->index('ads_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_services');
    }
};
