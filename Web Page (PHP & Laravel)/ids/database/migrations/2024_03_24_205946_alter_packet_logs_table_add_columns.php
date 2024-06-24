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
        Schema::table('packet_logs', function (Blueprint $table) {
            $table->dropColumn('layers_info'); // Remove the layers_info column
            $table->text('transport_info')->nullable(); // Add transport_info column
            $table->text('ip_info')->nullable(); // Add ip_info column
            $table->text('application_info')->nullable(); // Add application_info column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packet_logs', function (Blueprint $table) {
            $table->text('layers_info'); // Add the layers_info column back
            $table->dropColumn(['transport_info', 'ip_info', 'application_info']); // Remove the new columns
        });
    }
};
