<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->index();
            $table->decimal('cost_per_kg', 8, 2)->default(0.00);
            $table->string('status')->default('active'); // active, inactive
            $table->json('ewaste_types')->nullable(); // JSON column to store supported types list
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
