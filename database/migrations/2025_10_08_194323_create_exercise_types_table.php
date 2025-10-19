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
        Schema::create('exercise_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('category', ['cardio', 'strength', 'flexibility', 'balance', 'sports', 'other']);
            $table->string('muscle_groups', 50);
            $table->string('equipment_needed', 50);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->integer('estimated_calories_per_minute')->nullable();
            $table->text('instructions')->nullable();
            $table->string('video_url', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('exercise_types');
    }
};