<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('users');
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('reason');
            $table->timestamp('overridden_at')->useCurrent();
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
            $table->index('admin_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_overrides');
    }
};

