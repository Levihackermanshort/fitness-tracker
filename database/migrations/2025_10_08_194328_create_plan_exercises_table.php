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
        Schema::create('plan_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_type_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 1-7 (Monday-Sunday)
            $table->integer('order_in_day');
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('distance_meters')->nullable();
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
        Schema::dropIfExists('plan_exercises');
    }
};
