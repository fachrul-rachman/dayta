<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::Manager->value)->after('password');
            $table->foreignId('division_id')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('division_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('division_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['division_id']);
            $table->dropColumn(['role', 'division_id', 'is_active']);
        });
    }
};
