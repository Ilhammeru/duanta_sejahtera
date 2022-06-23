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
        Schema::create('booking_in', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('booking_code');
            $table->timestamp('booking_time')->nullable();
            $table->string('do_reference')->nullable();
            $table->integer('customer_id');
            $table->text('notes')->nullable();
            $table->integer('booked_by');
            $table->integer('accept_by')->nullable();
            $table->string('transport_company')->nullable();
            $table->string('transport_plate_number')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('billing_type_id')->nullable();
            $table->boolean('is_complete')->default(FALSE);
            $table->text('barcode_path')->nullable();
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
        Schema::dropIfExists('booking_in');
    }
};
