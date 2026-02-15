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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();
            $table->foreignId('client_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->dateTime('ends_at');
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled', 'completed'])
                ->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['salon_id', 'scheduled_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

