<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('collectes', function (Blueprint $table) {
            if (!Schema::hasColumn('collectes', 'for_date')) {
                $table->date('for_date')->nullable()->after('agent_id')->index();
            }
        });
    }
    public function down(): void {
        Schema::table('collectes', function (Blueprint $table) {
            if (Schema::hasColumn('collectes', 'for_date')) {
                $table->dropColumn('for_date');
            }
        });
    }
};