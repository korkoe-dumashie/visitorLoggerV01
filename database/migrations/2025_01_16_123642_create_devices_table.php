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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'device_brand');
            $table->string(column: 'serial_number');
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained('employees');
            $table->boolean('is_personal');
            $table->string(column: 'action');
            $table->string('status');
            $table->timestamp('logged_at');
            $table->timestamp('signed_out_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
