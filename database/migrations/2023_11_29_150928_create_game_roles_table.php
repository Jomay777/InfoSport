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
        Schema::create('game_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            
            //one-to-many relationship
            $table->unsignedBigInteger('tournament_id');

            $table->foreign('tournament_id')
                ->references('id')
                ->on('tournaments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
                //one-to-many relationship
            $table->unsignedBigInteger('pitch_id')->nullable();

            $table->foreign('pitch_id')
                ->references('id')
                ->on('pitches')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_roles');
    }
};
