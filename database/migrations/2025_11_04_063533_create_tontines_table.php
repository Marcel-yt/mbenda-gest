<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tontines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 30)->unique();
            $table->uuid('uuid')->nullable()->unique();
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('created_by_agent_id')->nullable()->index();
            $table->decimal('daily_amount', 13, 2);
            $table->unsignedSmallInteger('duration_days')->default(31);
            $table->date('start_date');
            $table->date('expected_end_date')->nullable()->index();
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['draft','active','completed','paid','archived','cancelled'])->default('draft')->index();
            $table->boolean('allow_early_payout')->default(true);
            $table->unsignedTinyInteger('commission_days')->default(1);
            $table->decimal('collected_total', 18, 2)->default(0);
            $table->json('settings')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // client_id doit pointer sur clients (table séparée)
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            // created_by_agent_id reste sur users
            $table->foreign('created_by_agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tontines');
    }
};
