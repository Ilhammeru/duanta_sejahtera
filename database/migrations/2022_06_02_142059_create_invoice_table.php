<?php

use Carbon\Carbon;
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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->date('date_create')->default(Carbon::now());
            $table->integer('billing_type_id');
            $table->string('invoice_number');
            $table->text('reference')->nullable();
            $table->integer('service_id');
            $table->text('description')->nullable();
            $table->integer('qty_container');
            $table->float('discount')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
};
