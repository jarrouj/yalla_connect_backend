<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Product;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkoutProduct($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated request.',
                ], 401);
            }

            if ($user->balance < $product->price) {
                return response()->json([
                    'message' => 'Insufficient balance.',
                    'error' => 'Your balance is less than the product price.',
                ], 403);
            }

            // Use DB transaction to make all steps atomic
            $result = DB::transaction(function () use ($user, $product) {
                // Deduct balance
                $user->balance -= $product->price;
                $user->save();

                // Save to checkouts
                $checkout = Checkout::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'title' => $product->title,
                    'price' => $product->price,
                ]);

                // Save to histories
                $history = History::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => $product->type,
                ]);

                return [
                    'checkout' => $checkout,
                    'history' => $history,
                    'new_balance' => $user->balance,
                ];
            });

            return response()->json([
                'message' => 'Checkout successful. Balance deducted and history saved.',
                'checkout' => $result['checkout'],
                'history' => $result['history'],
                'new_balance' => $result['new_balance'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userHistory()
    {
        $user = Auth::user();

        $history = History::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'User history fetched successfully.',
            'history' => $history
        ]);
    }
}
