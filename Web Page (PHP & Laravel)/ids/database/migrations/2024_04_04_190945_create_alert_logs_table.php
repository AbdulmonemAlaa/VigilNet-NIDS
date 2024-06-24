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
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent();
            $table->string('source_ip');
            $table->string('source_port');
            $table->string('destination_ip');
            $table->string('destination_port');
            $table->string('protocol');
            $table->text('transport_info')->nullable(); // Add transport_info column
            $table->text('ip_info')->nullable(); // Add ip_info column
            $table->text('application_info')->nullable(); // Add application_info column
            $table->text('alert_info')->nullable(); // Add application_info column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
    }
};
