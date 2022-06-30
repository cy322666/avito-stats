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
        Schema::create('ads_stats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('ads_id');

            $table->string('date');
            $table->integer('uniq_views')->nullable();
            $table->integer('uniq_contacts')->nullable();
            $table->integer('uniq_favorites')->nullable();

            $table->index(['ads_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_stats');
    }
};
