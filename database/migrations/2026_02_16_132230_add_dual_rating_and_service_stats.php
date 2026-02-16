<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('service_rating')->nullable()->after('rating');
            $table->unsignedTinyInteger('salon_rating')->nullable()->after('service_rating');
            $table->foreignId('service_id')->nullable()->after('salon_id')->constrained('services')->cascadeOnDelete();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
            $table->unsignedInteger('reviews_count')->default(0)->after('average_rating');
        });

        // Migrate existing data if any
        $reviews = DB::table('reviews')->where('type', 'service')->get();
        foreach ($reviews as $review) {
            $appointment = DB::table('appointments')->where('id', $review->appointment_id)->first();
            if ($appointment) {
                DB::table('reviews')->where('id', $review->id)->update([
                    'service_rating' => $review->rating,
                    'salon_rating' => $review->rating,
                    'service_id' => $appointment->service_id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'reviews_count']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_rating', 'salon_rating', 'service_id']);
        });
    }
};
