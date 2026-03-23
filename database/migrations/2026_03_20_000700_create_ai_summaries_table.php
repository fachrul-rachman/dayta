<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('scope_type');
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('summary')->nullable();
            $table->json('filters')->nullable();
            $table->foreignId('generated_by_user_id')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['scope_type', 'scope_id']);
            $table->index(['date_from', 'date_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_summaries');
    }
};

