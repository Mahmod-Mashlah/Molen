<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_times', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('user_id')->on('doctors');

            $table->unsignedBigInteger('day_id');
            $table->foreign('day_id')->references('id')->on('days');

            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_times');
    }
};
