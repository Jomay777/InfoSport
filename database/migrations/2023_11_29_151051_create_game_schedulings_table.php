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
        Schema::create('game_schedulings', function (Blueprint $table) {
            $table->id();

            $table->string('name');


            //one-to-many relationship
            $table->unsignedBigInteger('game_role_id');

            $table->foreign('game_role_id')
                ->references('id')
                ->on('game_roles')
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
        Schema::dropIfExists('game_schedulings');
    }
};
