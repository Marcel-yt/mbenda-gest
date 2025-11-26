<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('tontines')) {
			return;
		}

		Schema::table('tontines', function (Blueprint $table) {
			$table->unsignedBigInteger('cancelled_by')->nullable()->after('status');
			$table->text('cancelled_reason')->nullable()->after('cancelled_by');
			$table->timestamp('cancelled_at')->nullable()->after('cancelled_reason');

			// add foreign key if users table exists
			if (Schema::hasTable('users')) {
				$table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (! Schema::hasTable('tontines')) {
			return;
		}

		Schema::table('tontines', function (Blueprint $table) {
			if (Schema::hasColumn('tontines', 'cancelled_by')) {
				// drop foreign if exists
				try {
					$table->dropForeign(['cancelled_by']);
				} catch (\Throwable $e) {
					// ignore
				}
				$table->dropColumn('cancelled_by');
			}

			if (Schema::hasColumn('tontines', 'cancelled_reason')) {
				$table->dropColumn('cancelled_reason');
			}

			if (Schema::hasColumn('tontines', 'cancelled_at')) {
				$table->dropColumn('cancelled_at');
			}
		});
	}
};
