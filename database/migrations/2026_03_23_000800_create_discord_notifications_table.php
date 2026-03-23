<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discord_notifications', function (Blueprint $table) {
            $table->id();
            $table->date('reporting_date');
            $table->string('status');
            $table->string('channel')->nullable();
            $table->text('message')->nullable();
            $table->unsignedInteger('divisions_count')->default(0);
            $table->unsignedInteger('people_count')->default(0);
            $table->unsignedInteger('findings_count')->default(0);
            $table->unsignedInteger('attempt_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['reporting_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discord_notifications');
    }
};

