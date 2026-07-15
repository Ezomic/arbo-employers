<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->date('date_of_birth')->nullable()->after('employee_number');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('nationality', 3)->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->dropColumn(['date_of_birth', 'gender', 'nationality']);
        });
    }
};
