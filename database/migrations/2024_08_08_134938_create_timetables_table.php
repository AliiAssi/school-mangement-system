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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')
                  ->constrained('classes')
                  ->onDelete('cascade'); 
            $table->foreignId('user_id')
                  ->constrained('users') 
                  ->onDelete('cascade');  
            $table->foreignId('subject_id')
                  ->constrained('subjects')
                  ->onDelete('cascade'); 
            $table->time('start_time');
            $table->time('end_time');
            $table->string('day_of_week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
