<?php

use App\Enums\BigRockStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('big_rocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default(BigRockStatus::Active->value);
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->timestamps();

            $table->index(['division_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('big_rocks');
    }
};
