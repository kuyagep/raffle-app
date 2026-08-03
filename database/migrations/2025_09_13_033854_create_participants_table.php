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
        Schema::create('participants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('district_division');
            $table->string('municipality')->default("Division Office");
            $table->string('full_name'); // Removed ->unique()
            $table->string('designation')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('school_office');
            $table->string('email')->nullable(); // Removed ->unique()
            $table->string('contact_number')->nullable();
            $table->string('qr_code')->unique();
            $table->timestamps();

            // Composite unique index matching uniqueBy()
            $table->unique(['full_name', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
