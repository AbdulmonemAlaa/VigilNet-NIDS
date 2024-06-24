<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('reports', function (Blueprint $table) {
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->string('image_path')->nullable();
    });
}

public function down()
{
    Schema::table('reports', function (Blueprint $table) {
        $table->dropColumn(['title', 'description', 'image_path']);
    });
}

};
