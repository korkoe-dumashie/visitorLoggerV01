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
        Schema::create('visitor_access_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique();        
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->enum('active', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_access_cards');
    }
};
