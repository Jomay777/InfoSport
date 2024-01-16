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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('last_name');
            $table->string('mother_last_name')->nullable();
            $table->date('birth_date');
            $table->string('c_i');

            $table->string('nacionality');
            $table->string('country_birth');
            $table->string('region_birth');
            
            $table->enum('state', [1,2])->default(1);
            
            //one-to-many relationship
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')
            ->references('id')
            ->on('teams')
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
        Schema::dropIfExists('players');
    }
};
