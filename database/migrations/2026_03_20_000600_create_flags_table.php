<?php

use App\Enums\FlagSeverity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->id();
            $table->string('scope_type');
            $table->unsignedBigInteger('scope_id');
            $table->string('severity')->default(FlagSeverity::Low->value);
            $table->timestamp('flagged_at')->nullable();
            $table->string('title');
            $table->text('details')->nullable();
            $table->timestamps();

            $table->index(['scope_type', 'scope_id']);
            $table->index(['severity', 'flagged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flags');
    }
};
