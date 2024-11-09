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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('startDate'); // Start date
            $table->date('endDate'); // End date
            $table->string('description');
            $table->string('image')->nullable(); // Store image file path
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to categories table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
