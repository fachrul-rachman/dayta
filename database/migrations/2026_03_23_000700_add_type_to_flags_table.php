<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->string('type')->nullable()->after('scope_id');
            $table->index(['scope_type', 'scope_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->dropIndex(['scope_type', 'scope_id', 'type']);
            $table->dropColumn('type');
        });
    }
};

