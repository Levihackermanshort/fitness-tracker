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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->enum('goal_type', ['weight_loss', 'muscle_gain', 'endurance', 'strength', 'flexibility', 'general_fitness', 'sports_performance', 'other']);
            $table->decimal('target_value', 10, 2)->nullable();
            $table->string('target_unit', 20)->nullable();
            $table->decimal('current_value', 10, 2)->nullable();
            $table->date('target_date')->nullable();
            $table->integer('priority')->nullable(); // 1-5 scale
            $table->boolean('is_active')->default(true);
            $table->boolean('is_achieved')->default(false);
            $table->date('achieved_date')->nullable();
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
        Schema::dropIfExists('goals');
    }
};