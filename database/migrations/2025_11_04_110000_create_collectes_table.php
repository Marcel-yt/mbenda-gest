<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collectes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('tontine_id')->index();
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('agent_id')->nullable()->index(); // agent who collected

            $table->text('notes')->nullable();

            $table->timestamps(); // use created_at as collecte timestamp
            $table->softDeletes();

            // foreign keys
            $table->foreign('tontine_id')->references('id')->on('tontines')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};