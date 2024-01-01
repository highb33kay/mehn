<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use League\Csv\Reader;

use App\Models\Category;

use App\Models\User;
use App\Models\BankStatement;
use Illuminate\Support\Facades\Log;

class BankStatementController extends Controller
{
	//
	public function analyze(Request $request)
	{
		try {
			//code...
			$request->validate([
				'csv_file' => 'required|mimes:csv,txt|max:10240',
			]);

			// if the filename is already in the database, return an error
			$filename = $request->file('csv_file')->getClientOriginalName();
			$existingStatement = BankStatement::where('filename', $filename)->first();

			if ($existingStatement) {
				return response()->json([
					'success' => false,
					'data' => null,
					'message' => 'A bank statement with the same filename already exists',
				], 400);
			}

			// generate a bank statement id
			$bankStatementId = uniqid();

			$file = $request->file('csv_file');
			$csv = Reader::createFromPath($file->getPathname(), 'r');
			$csv->setHeaderOffset(0);

			$records = $csv->getRecords();
			$bankStatementId = $request->input('bank_statement_id');

			// Associate the current user with the bank statement
			$user = Auth::user();
			$bankStatement = $user->bankStatements()->firstOrNew(['id' => $bankStatementId]);
			$bankStatement->filename = $filename;
			$bankStatement->save();


			// Save the opening balance to the database
			$openingBalance = null;

			foreach ($records as $record) {
				// Extract data from the CSV record
				$date = $record['Date/Time'];
				$moneyIn = $record['Money In'];
				$moneyOut = $record['Money out'];
				$category = $record['Category'];
				$toFrom = $record['To / From'];
				$description = $record['Description'];
				$balance = $record['Balance'];
				$closingBalance = $record['Closing balance'];

				// remove the ? from the money in, money out, balance and closing balance
				$moneyIn = str_replace('?', '', $moneyIn);
				$moneyOut = str_replace('?', '', $moneyOut);
				$balance = str_replace('?', '', $balance);
				$closingBalance = str_replace('?', '', $closingBalance);

				// set the first record as the opening balance
				if ($openingBalance === null) {
					$openingBalance = $balance;
				}

				$amount = $moneyIn > 0 ? $moneyIn : $moneyOut;

				// Save the transaction to the database with a reference to the BankStatement record
				$transaction = new Transaction([
					'date' => $date,
					'amount' => $amount,
					'description' => $description,
					'type' => $moneyIn ? 'credit' : 'debit',
					'sender' => $moneyIn ? $toFrom : 'Alesinloye Ibukun/20000000261902/Kuda',
					'recipient' => $moneyIn ? 'Alesinloye Ibukun/20000000261902/Kuda' : $toFrom,
					'category_id' => $this->getCategoryId($category),
					'balance' => $balance,
					'closing_balance' => $closingBalance, // Assuming 'closing_balance' is the correct field name
					'bank_statement_id' => $bankStatementId,
				]);

				$bankStatement->transactions()->save($transaction);
			}

			print_r($closingBalance);
			print("\n");
			print_r($openingBalance);

			// Update the closing balance as the value for the last closing balance record
			$bankStatement->closing_balance = $closingBalance;
			$bankStatement->opening_balance = $openingBalance;
			$bankStatement->save();


			return response()->json(['message' => 'CSV file uploaded and processed successfully']);
		} catch (\Throwable $th) {
			//throw $th;
			Log::error('User Registration Failed: ' . $th->getMessage());

			return response()->json([
				'success' => false,
				'data' => $th->getMessage(),
				'message' => 'User Registration Failed',
			], 400);
		}
	}

	/**
	 * Get the category ID for the given category name.
	 */
	private function getCategoryId(string $categoryName): ?int
	{
		// If the category name is empty, return null
		if (!$categoryName) {
			return null;
		}

		// Get the category from the existing categories in the database

		$category = Category::where('name', $categoryName)->first();

		// If the category doesn't exist, create it
		if (!$category) {
			$category = new Category(['name' => $categoryName]);
			$category->save();
		}

		return $category->id;
	}
}
