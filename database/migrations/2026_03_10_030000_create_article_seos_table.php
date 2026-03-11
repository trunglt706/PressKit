<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_seos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->unsignedTinyInteger('score')->default(0);
            $table->json('score_breakdown')->nullable();
            $table->json('warnings')->nullable();
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();

            $table->unique('article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_seos');
    }
};
