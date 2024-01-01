<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('bank_statements', function (Blueprint $table) {
			$table->uuid('id')->primary()->unique()->index();
			$table->foreignUuid('user_id')->constrained();
			$table->decimal('opening_balance', 10, 2)->nullable();
			$table->decimal('closing_balance', 10, 2)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('bank_statements');
	}
};
