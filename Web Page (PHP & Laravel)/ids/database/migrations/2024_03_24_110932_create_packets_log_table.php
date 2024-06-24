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
        Schema::create('packets_log', function (Blueprint $table) {
            $table->integer('packet_id')->primary();
            $table->timestamp('timestamp')->useCurrent();
            $table->string('source_ip');
            $table->string('source_port');
            $table->string('destination_ip');
            $table->string('destination_port');
            $table->string('protocol');
            $table->text('layers_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packets_log');
    }
};
