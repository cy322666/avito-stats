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
        Schema::create('ads_calls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('date');
            $table->integer('answered');
            $table->integer('calls');
            $table->integer('new');
            $table->integer('new_answered');
            $table->bigInteger('ads_id');

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
        Schema::dropIfExists('ads_calls');
    }
};
