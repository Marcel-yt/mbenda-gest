<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tontine_id')->constrained('tontines')->unique();
                $table->foreignId('client_id')->constrained('clients');
                $table->foreignId('paid_by_admin_id')->constrained('users');
                $table->dateTime('paid_at');
                $table->decimal('amount_gross', 12, 2);
                $table->decimal('commission_amount', 12, 2);
                $table->decimal('amount_net', 12, 2);
                $table->string('receipt_path')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index('paid_by_admin_id');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('payouts');
    }
};