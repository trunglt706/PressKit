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
        Schema::create('contact_submissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 190);
            $table->string('phone', 30);
            $table->string('subject', 180);
            $table->text('message');
            $table->enum('status', ['pending', 'processed'])->default('pending');
            $table->ipAddress('ip_address')->nullable();
            $table->date('submitted_date')->nullable();
            $table->timestamps();

            $table->unique(['submitted_date', 'ip_address'], 'contact_submissions_date_ip_unique');
            $table->unique(['submitted_date', 'email'], 'contact_submissions_date_email_unique');
            $table->unique(['submitted_date', 'phone'], 'contact_submissions_date_phone_unique');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
