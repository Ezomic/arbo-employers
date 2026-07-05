<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('address_line_1')->nullable()->after('name');
            $table->string('address_line_2')->nullable()->after('address_line_1');
            $table->string('postal_code')->nullable()->after('address_line_2');
            $table->string('city')->nullable()->after('postal_code');
        });

        Schema::create('contact_persons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employer_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_persons');
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn(['address_line_1', 'address_line_2', 'postal_code', 'city']);
        });
    }
};
