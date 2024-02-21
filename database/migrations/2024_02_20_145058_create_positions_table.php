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

            $table->integer('poinst');
            $table->integer('games_played');
            $table->integer('games_won');
            $table->integer('games_drawn');
            $table->integer('games_lost');
            $table->integer('goals_scored');
            $table->integer('goals_against');
        
            //one-to-one relationship
            $table->unsignedBigInteger('tournament_id')->unique();            
             $table->foreign('tournament_id')
                 ->references('id')
                 ->on('tournament')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            
            //one-to-one relationship
             $table->unsignedBigInteger('team_id')->unique();
            
             $table->foreign('team_id')
                 ->references('id')
                 ->on('team')
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
