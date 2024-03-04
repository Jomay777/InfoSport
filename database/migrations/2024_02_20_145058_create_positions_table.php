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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('poinst');
            $table->unsignedInteger('games_played');
            $table->unsignedInteger('games_won');
            $table->unsignedInteger('games_drawn');
            $table->unsignedInteger('games_lost');
            $table->unsignedInteger('goals_scored');
            $table->unsignedInteger('goals_against');
        
            //one-to-one relationship
            $table->unsignedBigInteger('tournament_id')->unique();            
             $table->foreign('tournament_id')
                 ->references('id')
                 ->on('tournaments')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            
            //one-to-one relationship
             $table->unsignedBigInteger('team_id')->unique();
            
             $table->foreign('team_id')
                 ->references('id')
                 ->on('teams')
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
        Schema::dropIfExists('positions');
    }
};
