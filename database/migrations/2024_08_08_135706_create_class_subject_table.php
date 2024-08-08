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
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id(); // Primary key
            
            $table->foreignId('class_id')
                  ->constrained('classes') // Foreign key constraint to the classes table
                  ->onDelete('cascade'); // Delete records if the referenced class is deleted

            $table->foreignId('subject_id')
                  ->constrained('subjects') // Foreign key constraint to the subjects table
                  ->onDelete('cascade'); // Delete records if the referenced subject is deleted

            $table->integer('required_sessions'); // Number of sessions required
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject');
    }
};
