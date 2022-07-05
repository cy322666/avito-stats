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
        Schema::create('sipout_calls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("date")->nullable();
            $table->string("cnam")->nullable();
            $table->string("caller")->nullable();
            $table->string("called")->nullable();
            $table->integer("duration" )->nullable();
            $table->string("direction")->nullable();
            $table->string("type")->nullable();
            $table->boolean("answer")->nullable();
            $table->integer("note_cnt")->nullable();
            $table->string("callid")->unique();
            $table->string("ts")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calls');
    }
};
