<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_entry_item_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_entry_item_id')
                ->constrained('daily_entry_items')
                ->onDelete('cascade');
            $table->string('file_path', 2048);
            $table->string('file_name', 255);
            $table->string('file_mime', 255);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_entry_item_attachments');
    }
};
