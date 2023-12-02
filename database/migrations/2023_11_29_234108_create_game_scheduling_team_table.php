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
        Schema::create('game_scheduling_team', function (Blueprint $table) {
            $table->id();

            //many-to-many relationship
            $table->unsignedBigInteger('game_scheduling_id');
            $table->unsignedBigInteger('team_id');

            $table->foreign('game_scheduling_id')
                ->references('id')
                ->on('game_schedulings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
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
        Schema::dropIfExists('game_scheduling_team');
    }
};
