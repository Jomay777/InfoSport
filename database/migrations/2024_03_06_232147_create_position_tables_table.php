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
        Schema::create('position_tables', function (Blueprint $table) {
            $table->id();

            $table->integer('points')->default(0);
            $table->integer('games_played')->default(0);
            $table->integer('games_won')->default(0);
            $table->integer('games_drawn')->default(0);
            $table->integer('games_lost')->default(0);
            $table->integer('goals_scored')->default(0);
            $table->integer('goals_against')->default(0);

            //one-to-one relationship
            $table->unsignedBigInteger('tournament_id');            
             $table->foreign('tournament_id')
                 ->references('id')
                 ->on('tournaments')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            
            //one-to-one relationship
             $table->unsignedBigInteger('team_id');
            
             $table->foreign('team_id')
                 ->references('id')
                 ->on('teams')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

            // Restricción única en la combinación de tournament_id y team_id
             $table->unique(['tournament_id', 'team_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_tables');
    }
};
