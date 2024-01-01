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
		Schema::create('transactions', function (Blueprint $table) {
			$table->uuid('id')->primary()->unique()->index();
			$table->date('date');
			$table->decimal('amount', 10, 2);
			$table->string('description');
			$table->enum('type', ['debit', 'credit']);
			$table->string('sender')->nullable();
			$table->string('recipient')->nullable();
			$table->unsignedBigInteger('category_id')->nullable();
			$table->decimal('balance', 10, 2);
			$table->decimal('closing_balance', 10, 2)->nullable();
			$table->unsignedBigInteger('bank_statement_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('transactions');
	}
};
