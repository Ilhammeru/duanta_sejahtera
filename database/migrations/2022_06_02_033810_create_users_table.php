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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('password');
            $table->string('phone');
            $table->date('birth_date')->nullable();
            $table->timestamp('date_in')->nullable();
            $table->integer('division_id')->nullable();
            $table->text('photo')->nullable();
            $table->string('identity_number')->nullable();
            $table->boolean('status');
            $table->timestamp('last_login')->default(Carbon::now());
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
        Schema::dropIfExists('users');
    }
};
