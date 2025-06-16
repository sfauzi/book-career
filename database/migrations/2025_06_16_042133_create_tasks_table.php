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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id'); // Foreign key ke users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->date('applied_date');
            $table->enum('status', ['Applied', 'Interview', 'Test', 'Diterima', 'Ditolak'])->default('Applied');
            $table->string('platform')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'applied_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
