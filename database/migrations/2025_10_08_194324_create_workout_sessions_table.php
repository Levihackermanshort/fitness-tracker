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
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('workout_type', ['strength', 'cardio', 'flexibility', 'mixed', 'sports', 'other']);
            $table->integer('total_duration')->nullable(); // Duration in minutes
            $table->integer('total_calories')->nullable(); // Calories burned
            $table->integer('perceived_exertion')->nullable(); // 1-10 scale
            $table->integer('mood_before')->nullable(); // 1-10 scale
            $table->integer('mood_after')->nullable(); // 1-10 scale
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('workout_sessions');
    }
};