<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
       public function addBalance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->balance += $request->amount;
        $user->save();

        return response()->json([
            'message' => 'Balance updated successfully',
            'new_balance' => $user->balance,
        ]);
    }

}
