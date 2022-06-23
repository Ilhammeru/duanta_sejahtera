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
        Schema::create('booking_in_container', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->string('container_number')->nullable();
            $table->string('container_seal')->nullable();
            $table->string('cargo_goods')->nullable();
            $table->integer('container_size_type_id')->nullable();
            $table->string('is_customer_container_size')->nullable();
            $table->string('custom_container_size')->nullable();
            $table->integer('volume')->default(0);
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
        Schema::dropIfExists('booking_in_container');
    }
};
