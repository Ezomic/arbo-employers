<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Read-shadow of Case Officers' organizational_units table. The
     * self-referencing FK is added in a second step (Postgres won't let a
     * table reference its own not-yet-committed primary key within the
     * same CREATE TABLE statement).
     */
    public function up(): void
    {
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employer_id')->constrained()->cascadeOnDelete();
            $table->uuid('parent_id')->nullable();
            $table->string('name');
            $table->boolean('is_legal_entity')->default(false);
            $table->string('kvk_number')->nullable();
            $table->timestamps();
        });

        Schema::table('organizational_units', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('organizational_units')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_units');
    }
};
