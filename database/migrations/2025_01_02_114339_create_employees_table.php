<?php

use App\Models\Department;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'employee_number')->unique()->nullable();
            $table->boolean('is_user')->default(false);
            $table->string(column: 'first_name');
            $table->string(column: 'other_name')->nullable();
            $table->string(column: 'last_name');
            $table->string(column: 'email')->nullable();
            $table->string(column: 'phone_number');
            $table->foreignIdFor(Department::class, column: 'department_id');
            $table->enum('employment_status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->string(column: 'job_title');
            $table->string(column: 'access_card_number')->nullable();
            $table->string(column: 'gender');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
