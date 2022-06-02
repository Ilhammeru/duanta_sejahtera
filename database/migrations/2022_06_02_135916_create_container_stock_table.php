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
        Schema::create('container_stock', function (Blueprint $table) {
            $table->id();
            $table->string('container_number');
            $table->integer('customer_id');
            $table->integer('next_service')->nullable();
            $table->date('start_date_park')->nullable();
            $table->time('start_time_park')->nullable();
            $table->date('end_date_park')->nullable();
            $table->time('end_time_park')->nullable();
            $table->boolean('is_booked_out')->default(FALSE);
            $table->boolean('is_out')->default(FALSE);
            $table->integer('staff_id');
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
        Schema::dropIfExists('container_stock');
    }
};
