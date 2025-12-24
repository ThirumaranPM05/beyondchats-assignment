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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();

        // Core article data
        $table->string('title');
        $table->string('slug')->unique();
        $table->longText('content');

        // Metadata
        $table->string('source_url')->nullable();

        // Original vs enriched
        $table->enum('type', ['original', 'updated'])->default('original');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
