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
        Schema::table('alert_logs', function (Blueprint $table) {
            $table->unsignedInteger('packet_id')->nullable(); // Use unsignedInteger to match packet_id

            $table->foreign('packet_id')
                  ->references('packet_id')
                  ->on('packet_logs')
                  ->onDelete('cascade'); // Ensure cascading deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_logs', function (Blueprint $table) {
            $table->dropForeign(['packet_id']);  // Drop the foreign key constraint
            $table->dropColumn('packet_id');
        });
    }
};
