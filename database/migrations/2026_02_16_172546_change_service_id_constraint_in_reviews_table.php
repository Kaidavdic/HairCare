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
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['service_id']);
            
            // Recreate the foreign key with nullOnDelete instead of cascadeOnDelete
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the nullOnDelete constraint
            $table->dropForeign(['service_id']);
            
            // Restore the original cascadeOnDelete constraint
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();
        });
    }
};
