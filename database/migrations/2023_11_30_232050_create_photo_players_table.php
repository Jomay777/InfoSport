<?php

use DragonCode\Contracts\Cashier\Config\Queues\Unique;
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
        Schema::create('photo_players', function (Blueprint $table) {
            $table->id();

            
            $table->string('photo_path');
            $table->string('photo_c_i');
            $table->string('photo_birth_certificate');
            $table->string('photo_parental_authorization')->nullable();
            
            //one-to-one relationship
            $table->unsignedBigInteger('player_id')->unique();
            
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
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
        Schema::dropIfExists('photo_players');
    }
};
