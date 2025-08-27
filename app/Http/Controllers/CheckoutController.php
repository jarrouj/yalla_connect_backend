<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use App\Models\Product;
use App\Models\Checkout;
use App\Models\Specialty;
use App\Models\ProductCodes;
use Illuminate\Http\Request;
use App\Services\PromoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckoutController extends Controller
{
    public function checkoutProduct(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated request.'], 401);
            }
            $product = Product::with('subcategory')->findOrFail($productId);


            $product->load('subcategory');

            // quantity support (optional; default 1)
            $quantity = (int) $request->input('quantity', 1);
            if ($quantity < 1) $quantity = 1;

            // Determine voucher by subcategory name
            $isVoucher = $product->subcategory
                && strcasecmp($product->subcategory->name, 'voucher') === 0;

            // Base price (cast safely)
            $basePrice = (float) $product->price;

            // Grab active promo & ensure it's still valid (else ignore)
            $promo = optional($user)->activePromoCode;
            if ($promo && method_exists($promo, 'isCurrentlyValid') && !$promo->isCurrentlyValid()) {
                $promo = null;
            }

            // Discounted unit price via your service
            $unitPrice = PromoService::discountedPrice($basePrice, $promo);
            // Line total (qty * unit) rounded to 2
            $lineTotal = round($unitPrice * $quantity, 2);

            // If line total is negative for any reason, clamp to 0
            if ($lineTotal < 0) $lineTotal = 0.0;

            // Balance check (allow free)
            if ($lineTotal > 0 && $user->balance < $lineTotal) {
                return response()->json([
                    'message' => 'Insufficient balance.',
                    'error'   => 'Your balance is less than the amount required.',
                    'needed'  => $lineTotal,
                    'have'    => (float) $user->balance,
                ], 403);
            }

            $result = DB::transaction(function () use ($user, $product, $isVoucher, $promo, $basePrice, $unitPrice, $lineTotal, $quantity) {
                // Re-load & lock the user row to prevent race conditions
                $userLocked = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

                // Assign voucher code if needed
                $assignedCode = null;
                if ($isVoucher) {
                    // Lock an AVAILABLE code for this product
                    // Table: product_codes (id, product_id, code, status: available/used, used_by, used_at)
                    $codeRow = ProductCodes::where('product_id', $product->id)
                    ->lockForUpdate()
                    ->oldest()
                    ->first();

                if (!$codeRow) {
                    throw new HttpException(409, 'No voucher codes available for this product.');
                }

                $assignedCode = $codeRow->code;

                // Consume it by deleting the row
                $codeRow->delete();

                }

                // Deduct the user's balance by the line total (if > 0)
                if ($lineTotal > 0) {
                    $userLocked->balance = round(((float) $userLocked->balance) - $lineTotal, 2);
                    $userLocked->save();
                }

                // Create checkout snapshot (store base & final, promo info, qty)
                $checkout = Checkout::create([
                    'product_id'     => $product->id,
                    'user_id'        => $userLocked->id,
                    'title'          => $product->title,
                    'price'          => $basePrice,               // base unit price
                    'final_price'    => $unitPrice,               // discounted unit price
                    'quantity'       => $quantity,
                    'total_paid'     => $lineTotal,               // final total charged
                    'promo_code'     => $promo?->code,            // nullable
                    'promo_percent'  => $promo?->percent,         // nullable
                    // 'voucher_code'  => $assignedCode,           // add if column exists
                ]);

                // History entry (store amount & quantity)
                History::create([
                    'product_id' => $product->id,
                    'user_id'    => $userLocked->id,
                    'type'       => $product->subcategory->name ?? $product->type,
                    'code'       => $assignedCode,                // null if not voucher
                    'amount'     => $lineTotal,
                    'quantity'   => $quantity,
                ]);

                // Consume promo after successful checkout (your existing logic)
                app(PromoService::class)->consumeAtCheckout($userLocked);

                return [
                    'checkout'     => $checkout,
                    'assignedCode' => $assignedCode,
                    'new_balance'  => (float) $userLocked->balance,
                    'unit_price'   => $unitPrice,
                    'line_total'   => $lineTotal,
                ];
            });

            return response()->json([
                'message'       => $isVoucher
                    ? 'Checkout successful. Voucher code assigned, balance deducted, and history saved.'
                    : 'Checkout successful. Balance deducted and history saved.',
                'checkout'      => $result['checkout'],
                'voucher_code'  => $result['assignedCode'], // only for vouchers
                'new_balance'   => $result['new_balance'],
                'unit_price'    => $result['unit_price'],
                'line_total'    => $result['line_total'],
            ], 200);
        } catch (HttpException $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => 'Product not found.',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function checkoutSpecialty(Request $request, $specialtyId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated request.'], 401);
        }

        $specialty = Specialty::findOrFail($specialtyId);

        $quantity = max(1, (int) $request->input('quantity', 1));
        $basePrice = (float) $specialty->price;

        // apply user’s active promo if valid
        $promo = optional($user)->activePromoCode;
        if ($promo && method_exists($promo, 'isCurrentlyValid') && !$promo->isCurrentlyValid()) {
            $promo = null;
        }

        $unitPrice = PromoService::discountedPrice($basePrice, $promo);
        $lineTotal = round($unitPrice * $quantity, 2);
        if ($lineTotal < 0) $lineTotal = 0.0;

        if ($lineTotal > 0 && $user->balance < $lineTotal) {
            return response()->json([
                'message' => 'Insufficient balance.',
                'error'   => 'Your balance is less than the amount required.',
                'needed'  => $lineTotal,
                'have'    => (float) $user->balance,
            ], 403);
        }

        $result = DB::transaction(function () use ($user, $specialty, $promo, $basePrice, $unitPrice, $lineTotal, $quantity) {
            $userLocked = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            if ($lineTotal > 0) {
                $userLocked->balance = round(((float) $userLocked->balance) - $lineTotal, 2);
                $userLocked->save();
            }

            $checkout = Checkout::create([
                'specialty_id'  => $specialty->id,
                'user_id'       => $userLocked->id,
                'title'         => $specialty->name,
                'price'         => $basePrice,     // base unit
                'final_price'   => $unitPrice,     // discounted unit
                'quantity'      => $quantity,
                'total_paid'    => $lineTotal,
                'promo_code'    => $promo?->code,
                'promo_percent' => $promo?->percent,
            ]);

            History::create([
                'specialty_id' => $specialty->id,
                'user_id'      => $userLocked->id,
                'type'         => 'specialty',
                'code'         => null,
                'amount'       => $lineTotal,
                'quantity'     => $quantity,
            ]);

            // consume promo after success (if that's your rule)
            app(PromoService::class)->consumeAtCheckout($userLocked);

            return [
                'checkout'    => $checkout,
                'new_balance' => (float) $userLocked->balance,
                'unit_price'  => $unitPrice,
                'line_total'  => $lineTotal,
            ];
        });

        return response()->json([
            'message'     => 'Checkout successful. Balance deducted and history saved.',
            'checkout'    => $result['checkout'],
            'new_balance' => $result['new_balance'],
            'unit_price'  => $result['unit_price'],
            'line_total'  => $result['line_total'],
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Checkout failed.', 'error' => 'Specialty not found.'], 404);
    } catch (\Throwable $e) {
        return response()->json(['message' => 'Checkout failed.', 'error' => $e->getMessage()], 500);
    }
}

public function userHistory()
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $history = History::with([
            'product:id,title,image',
            'specialty:id,name,image',
        ])
        ->where('user_id', $user->id)
        ->latest()
        ->get([
            'id',
            'product_id',
            'specialty_id',
            'user_id',
            'type',
            'code',
            'amount',
            'quantity',
            'created_at',
        ]);

    return response()->json([
        'message' => 'User history fetched successfully.',
        'history' => $history,
    ]);
}

}
