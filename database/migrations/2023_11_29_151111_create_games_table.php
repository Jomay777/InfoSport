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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            
            $table->date('game_date');
            $table->time('game_time');
            $table->string('location');
            $table->string('result');
            
            $table->text('observation');

            //one-to-many relationship
            $table->unsignedBigInteger('game_scheduling_id');
            $table->foreign('game_scheduling_id')
                ->references('id')
                ->on('game_schedulings')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            //one-to-many relationship
            $table->unsignedBigInteger('tournament_id');

            $table->foreign('tournament_id')
                ->references('id')
                ->on('tournaments')
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
        Schema::dropIfExists('games');
    }
};
