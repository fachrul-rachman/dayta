<?php

use App\Enums\DailyPlanStatus;
use App\Enums\DailyRealizationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('division_id')->constrained('divisions');
            $table->date('entry_date');
            $table->string('plan_status')->default(DailyPlanStatus::Locked->value);
            $table->string('realization_status')->default(DailyRealizationStatus::Locked->value);
            $table->timestamp('plan_submitted_at')->nullable();
            $table->timestamp('realization_submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'entry_date']);
            $table->index(['division_id', 'entry_date']);
        });

        Schema::create('daily_entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_entry_id')->constrained('daily_entries')->onDelete('cascade');
            $table->text('description');
            $table->string('work_type');
            $table->foreignId('big_rock_id')->nullable()->constrained('big_rocks');
            $table->float('planned_hours')->nullable();
            $table->float('realized_hours')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['daily_entry_id', 'work_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_entry_items');
        Schema::dropIfExists('daily_entries');
    }
};

