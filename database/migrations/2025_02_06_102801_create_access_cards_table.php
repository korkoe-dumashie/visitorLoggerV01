<?php

use App\Models\AccessCards;
use App\Models\Visitor;
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
        Schema::create('access_cards', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Visitor::class,'visitor_id');

            $table->string('card_number')->nullable();
            $table->foreign('card_number')->references('card_number')->on('visitor_access_cards')->onDelete('SET NULL');
            $table->timestamps();


        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_cards');
    }
};
