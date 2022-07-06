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
        Schema::create('sipout_managers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer("number")->nullable();
            $table->string("descr")->nullable();
            $table->string("sip_login")->nullable();
            $table->string("sip_password")->nullable();
            $table->string("aon")->nullable();
            $table->string("email")->nullable();
            $table->string("pickup_groups")->nullable();
            $table->string("type")->nullable();

            $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
};
