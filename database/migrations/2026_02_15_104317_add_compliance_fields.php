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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'pending', 'banned'])->default('active')->after('role');
            $table->json('password_history')->nullable()->after('password');
            $table->json('interests')->nullable()->after('password_history');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('type', ['service', 'user'])->default('service')->after('id');
            $table->foreignId('reviewed_user_id')->nullable()->after('client_id')->constrained('users')->onDelete('cascade');
            // Make salon_id nullable if reviewing a user
            $table->foreignId('salon_id')->nullable()->change();
            $table->foreignId('appointment_id')->nullable()->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_promoted')->default(false)->after('price');
            $table->decimal('discount_price', 10, 2)->nullable()->after('is_promoted');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['is_promoted', 'discount_price']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewed_user_id']);
            $table->dropColumn(['type', 'reviewed_user_id']);
            // Cannot easily revert nullable changes without knowing original state, skipping for now
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'password_history', 'interests']);
        });
    }
};
