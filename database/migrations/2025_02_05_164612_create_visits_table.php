<?php

use App\Models\Employee;
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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();//done
            $table->string('phone_number');//done
            $table->foreignIdFor(Employee::class, 'employee_Id');//done
            $table->string('company_name')->nullable();
            $table->string('purpose');//done
            $table->longText('rating')->nullable();
            
            $table->timestamp('departed_at')->nullable();
            $table->boolean('marketing_consent')->default(false)->nullable();
            $table->longText('visitor_experience')->nullable();
            $table->enum('status', ['ongoing', 'departed'])->default('ongoing');
            $table->json('devices')->nullable();//done
            $table->json('companions')->nullable();

            $table->timestamps();
            
            $table->index('phone_number', 'idx_phone_number');
            $table->index('email', 'idx_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};