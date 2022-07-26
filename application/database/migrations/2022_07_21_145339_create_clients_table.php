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
        Schema::create('mc_clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid()->unique();
            $table->json('group')->nullable();
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->boolean('archived')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mc_clients');
    }
};
