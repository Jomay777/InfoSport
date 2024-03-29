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
        Schema::create('game_statistics', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('goals_team_a')->nullable();
            $table->unsignedBigInteger('goals_team_b')->nullable();
            $table->unsignedBigInteger('yellow_cards_a')->nullable();
            $table->unsignedBigInteger('yellow_cards_b')->nullable();
            $table->unsignedBigInteger('red_cards_a')->nullable();
            $table->unsignedBigInteger('red_cards_b')->nullable();

            //one-to-one relationship
            $table->unsignedBigInteger('game_id')->unique();
            
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_statistics');
    }
};
