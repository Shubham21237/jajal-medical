<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Step 5: Create the medical cases database
     */
    public function up(): void
    {
        Schema::create('medical_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number', 64)->unique();
            $table->string('patient_code', 64)->comment('De-identified synthetic patient reference code');
            $table->string('surgeon_name');
            $table->string('implant_type');
            $table->enum('status', [
                'Draft',
                'Planning',
                'In Review',
                'Approved',
                'Completed',
                'Cancelled'
            ])->default('Draft');
            $table->enum('priority', [
                'Low',
                'Medium',
                'High'
            ])->default('Medium');
            $table->date('surgery_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes for fast querying & filtering
            $table->index('status');
            $table->index('surgeon_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_cases');
    }
};
