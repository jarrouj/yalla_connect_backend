<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show_transactions(){

        $transactions = Transaction::with('user')->latest()->paginate(10);
        return view('admin.transaction.show_transaction' , compact('transactions'));
    }

    public function delete_transaction($id)
    {
        $transaction = Transaction::find($id);

        $transaction->delete();

        return redirect()->back()->with('message', 'Transaction Deleted');
    }
}
