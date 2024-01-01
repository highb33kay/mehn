<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BankStatement extends Model
{
	use HasFactory, HasUuids;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
	];

	/**
	 * relationship with user
	 *
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * relationship with transaction
	 *
	 */
	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
