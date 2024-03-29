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
            
            $table->string('result', 100);
            
            $table->text('observation', 300)->nullable();

            //one-to-many relationship
            $table->unsignedBigInteger('game_scheduling_id')->unique();
            $table->foreign('game_scheduling_id')
                ->references('id')
                ->on('game_schedulings')
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
