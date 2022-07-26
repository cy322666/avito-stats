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
        Schema::create('mc_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid()->unique();
            $table->string('name')->nullable();
            $table->string('moment')->nullable();
            $table->boolean('applicable')->nullable();
            $table->uuid('contragent_uuid')->nullable();
            $table->string('checkNumber')->nullable();
            $table->float('checkSum')->nullable();
            $table->string('code')->nullable();
            $table->dateTime('created')->nullable();
            $table->string('description')->nullable();
            $table->string('documentNumber')->nullable();
            $table->boolean('fiscal')->nullable();
            $table->string('fiscalPrinterInfo')->nullable();
            $table->float('noCashSum')->nullable();
            $table->uuid('employee_uuid')->nullable();
            $table->float('payedSum')->nullable();
            $table->float('prepaymentCashSum')->nullable();
            $table->float('prepaymentNoCashSum')->nullable();
            $table->float('prepaymentQrSum')->nullable();
            $table->float('qrSum')->nullable();
            $table->string('retailStore')->nullable();
            $table->string('sessionNumber')->nullable();
            $table->uuid('store_uuid')->nullable();
            $table->uuid('sum')->nullable();
            $table->float('vatSum')->nullable();
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
