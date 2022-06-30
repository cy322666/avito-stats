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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('ads_id')->unique();
            $table->integer('price');
            $table->string('category_name')->nullable();
            $table->string('title');
            $table->string('status');
            $table->string('url');
            $table->integer('account_id');
            $table->date('stats_updated_at')->nullable();
            $table->date('calls_updated_at')->nullable();
            $table->date('services_updated_at')->nullable();

            $table->index('ads_id');
            $table->index('account_id');
            $table->index('stats_updated_at');
            $table->index('calls_updated_at');
            $table->index('services_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
};
