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
            $table->foreignUlid('office_id')->constrained()->cascadeOnDelete();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('position')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('qr_code')->unique();
            $table->timestamps();

            // FIXED: Use actual column names
            $table->unique(['lastname', 'firstname', 'email']);
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
