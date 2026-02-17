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
        Schema::table('salons', function (Blueprint $table) {
            $table->integer('opening_hour')->default(9)->after('status');
            $table->integer('closing_hour')->default(18)->after('opening_hour');
            $table->json('closed_days')->nullable()->after('closing_hour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn(['opening_hour', 'closing_hour', 'closed_days']);
        });
    }
};
