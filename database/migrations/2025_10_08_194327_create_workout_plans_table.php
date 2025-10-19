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
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('plan_type', ['strength', 'cardio', 'flexibility', 'mixed', 'sports', 'rehabilitation', 'other']);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->integer('duration_weeks')->nullable();
            $table->integer('sessions_per_week')->nullable();
            $table->integer('estimated_duration_per_session')->nullable(); // minutes
            $table->boolean('is_template')->default(false);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('workout_plans');
    }
};
