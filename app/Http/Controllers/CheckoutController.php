<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Product;
use App\Models\Checkout;
use App\Models\Transaction;
use App\Models\ProductCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkoutProduct($productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated request.'], 401);
            }

            // Eager-load subcategory to know if voucher
            $product = Product::with('subcategory')->findOrFail($productId);

            // Determine if voucher by subcategory name
            $isVoucher = $product->subcategory
                && strtolower($product->subcategory->name) === 'voucher';

            // Price/balance check (you can skip for voucher if price=0 in your logic)
            if ($user->balance < $product->price) {
                return response()->json([
                    'message' => 'Insufficient balance.',
                    'error'   => 'Your balance is less than the product price.',
                ], 403);
            }

            // Do everything atomically
            $result = DB::transaction(function () use ($user, $product, $isVoucher) {
                $assignedCode = null;

                if ($isVoucher) {
                    // Lock an available code for this product to avoid race conditions
                    $codeRow = ProductCodes::where('product_id', $product->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$codeRow) {
                        // No codes left for this voucher
                        abort(409, 'No voucher codes available for this product.');
                    }

                    $assignedCode = $codeRow->code;

                    // Remove the code from the pool (consumed)
                    $codeRow->delete();
                }

                // Deduct balance
                $user->balance -= $product->price;
                $user->save();

                // Save checkout
                $checkout = Checkout::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'title'      => $product->title,
                    'price'      => $product->price,
                    // optionally store code too if you want:
                    // 'code' => $assignedCode,
                ]);

                // Save history with subcategory name as type, and code if voucher
                $history = History::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'type'       => $product->subcategory->name ?? $product->type, // prefer subcategory name
                    'code'       => $assignedCode, // null for non-voucher
                ]);

                Transaction::create([
                    'user_id' => $user->id,
                    'type_of_transaction' => 'Product Purchase ' . $product->title,
                    'amount' => $product->price,
                ]);


                return [
                    'checkout'     => $checkout,
                    'history'      => $history,
                    'assignedCode' => $assignedCode,
                    'new_balance'  => $user->balance,
                ];
            });

            return response()->json([
                'message'       => $isVoucher
                    ? 'Checkout successful. Voucher code assigned, balance deducted, and history saved.'
                    : 'Checkout successful. Balance deducted and history saved.',
                'checkout'      => $result['checkout'],
                'history'       => $result['history'],
                'voucher_code'  => $result['assignedCode'], // present only if voucher
                'new_balance'   => $result['new_balance'],
            ]);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // catches the 409 abort above
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function userHistory()
    {
        $user = Auth::user();

        $history = History::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get([
                'id',
                'product_id',
                'user_id',
                'type',
                'code',
                'created_at'
            ]);

        return response()->json([
            'message' => 'User history fetched successfully.',
            'history' => $history
        ]);
    }
}
