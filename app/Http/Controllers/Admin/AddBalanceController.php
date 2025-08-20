<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AddBalanceController extends Controller
{
    public function show_user()
    {
        $user = User::latest()->paginate(10);

        return view('admin.Add_balance.show_add_balance', compact('user'));
    }

    public function add_balance(Request $request, $id)
    {
        // validate the *amount to add*
        $validated = $request->validate([
            'balance' => 'required|numeric|min:0.01', // top-ups only; change to min:0 for zero-allowed
        ]);

        $amount = (float) $validated['balance'];

        // find user or 404
        $user = User::findOrFail($id);
        $before = (float) $user->balance;

        // atomic increment (handles concurrency)
        $user->increment('balance', $amount);
        $user->refresh();

        return redirect()->back()
            ->with('message', "Balance updated: {$before} → {$user->balance} (+" . number_format($amount, 2) . ").");
    }
}
