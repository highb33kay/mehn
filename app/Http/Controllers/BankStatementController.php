<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use League\Csv\Reader;

use App\Models\User;
use App\Models\BankStatement;


class BankStatementController extends Controller
{
	//
	public function analyze(Request $request)
	{
		$request->validate([
			'csv_file' => 'required|mimes:csv,txt|max:10240',
			'bank_statement_id' => 'required|string',
		]);

		$file = $request->file('csv_file');
		$csv = Reader::createFromPath($file->getPathname(), 'r');
		$csv->setHeaderOffset(0);

		$records = $csv->getRecords();
		$openingBalance = 0;
		$bankStatementId = $request->input('bank_statement_id');

		// Associate the current user with the bank statement
		$user = Auth::user();
		$bankStatement = $user->bankStatements()->firstOrNew(['id' => $bankStatementId]);
		$bankStatement->save();

		foreach ($records as $record) {
			// Extract data from the CSV record
			$date = $record['Date/Time'];
			$moneyIn = $record['Money In'];
			$moneyOut = $record['Money out'];
			$category = $record['Category'];
			$toFrom = $record['To / From'];
			$description = $record['Description'];
			$balance = $record['Balance'];
			$closingBalance = $record['ClosingBalance'];

			// Capture the opening balance from the first record
			if ($openingBalance === 0) {
				$openingBalance = $record['Opening Balance'];
			}

			// Save the transaction to the database with a reference to the BankStatement record
			$transaction = new Transaction([
				'date' => $date,
				'amount' => $moneyIn ?: -$moneyOut,
				'description' => $description,
				'type' => $moneyIn ? 'credit' : 'debit',
				'sender' => $moneyIn ? null : $toFrom,
				'recipient' => $moneyIn ? $toFrom : null,
				'category_id' => $this->getCategoryId($category),
				'balance' => $balance,
				'closing_balance' => $closingBalance, // Assuming 'closing_balance' is the correct field name
			]);

			$bankStatement->transactions()->save($transaction);
		}

		// Update the closing balance for the bank statement
		$bankStatement->closing_balance = $closingBalance;
		$bankStatement->save();

		return response()->json(['message' => 'CSV file uploaded and processed successfully']);
	}

	private function getCategoryId($category)
	{
		// Add logic to map category names to category IDs in your database
		// You may create a Category model with a name field for this purpose
		// For simplicity, you can return null or a default category ID if no match is found
		// Example: return Category::where('name', $category)->value('id') ?? 1;
	}
}
