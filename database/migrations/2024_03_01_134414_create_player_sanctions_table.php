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
        Schema::create('player_sanctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yellow_cards')->nullable();
            $table->unsignedInteger('red_card')->nullable();
            $table->string('state', 20)->comment('State of the player sanction');
            $table->text('sanction')->nullable()->comment('Description of the sanction');
        
            // One-to-many relationship with games table
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        
            // One-to-many relationship with players table
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        
            // Unique constraint for game_id and player_id combination
            $table->unique(['game_id', 'player_id']);
        
            // Indexes for frequently queried columns
           /*  $table->index('game_id');
            $table->index('player_id'); */
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_sanctions');
    }
};
