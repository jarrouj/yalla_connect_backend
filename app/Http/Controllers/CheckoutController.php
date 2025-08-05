<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\History;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkoutProduct($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $user = Auth::user();

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

            return response()->json([
                'message' => 'Checkout successful and history saved.',
                'checkout' => $checkout,
                'history' => $history
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
