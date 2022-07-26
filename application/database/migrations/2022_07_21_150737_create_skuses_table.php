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
        Schema::create('mc_skus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid()->unique();
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->string('article')->nullable();
            $table->string('group')->nullable();
            $table->boolean('archived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mc_skus');
    }
};
