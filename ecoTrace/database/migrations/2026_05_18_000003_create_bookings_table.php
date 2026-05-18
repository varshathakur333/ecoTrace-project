<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();
            $table->date('booking_date');
            $table->decimal('weight', 8, 2)->nullable(); // estimated or actual weight in kg
            $table->string('status')->default('pending'); // pending, accepted, completed, cancelled
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable(); // image upload for e-waste types
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
