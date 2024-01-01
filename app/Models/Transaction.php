<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
	use HasFactory, HasUuids;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'date',
		'amount',
		'description',
		'type',
		'sender',
		'recipient',
		'category_id',
		'balance',
		'closing_balance',
		'bank_statement_id',
	];

	protected $keyType = 'uuid';

	/**
	 * relationship with bankStatement
	 *
	 */
	public function bankStatement()
	{
		return $this->belongsTo(BankStatement::class);
	}
}
