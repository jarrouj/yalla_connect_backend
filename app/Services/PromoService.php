<?php

namespace App\Services;

use App\Models\PromoCode;
use App\Models\User;
use App\Models\PromoCodeRedemption;
use Illuminate\Validation\ValidationException;

class PromoService
{
    public function applyCode(User $user, string $code): PromoCode
    {
        $promo = PromoCode::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower($code)])
            ->first();

        if (!$promo || !$promo->isCurrentlyValid()) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired promo code.'
            ]);
        }

        // Per-user one-time use
        if (PromoCodeRedemption::where('promo_code_id', $promo->id)
                ->where('user_id', $user->id)
                ->exists()) {
            throw ValidationException::withMessages([
                'code' => 'You have already used this promo code.'
            ]);
        }

        PromoCodeRedemption::create([
            'promo_code_id' => $promo->id,
            'user_id'       => $user->id,
            'status'        => 'applied',
        ]);

        $user->active_promo_code_id = $promo->id;
        $user->save();

        return $promo;
    }

    public function removeCode(User $user): void
    {
        if (!$user->active_promo_code_id) return;

        PromoCodeRedemption::where('promo_code_id', $user->active_promo_code_id)
            ->where('user_id', $user->id)
            ->where('status', 'applied')
            ->update(['status' => 'revoked']);

        $user->active_promo_code_id = null;
        $user->save();
    }

    public function consumeAtCheckout(User $user): void
    {
        if (!$user->active_promo_code_id) return;

        PromoCodeRedemption::where('promo_code_id', $user->active_promo_code_id)
            ->where('user_id', $user->id)
            ->where('status', 'applied')
            ->update(['status' => 'consumed']);

        $promo = $user->activePromoCode;
        if ($promo && $promo->global_one_time) {
            $promo->update(['is_active' => false]);
        }

        $user->active_promo_code_id = null;
        $user->save();
    }

    public static function discountedPrice(float $basePrice, ?PromoCode $promo): float
    {
        if (!$promo) return $basePrice;

        $pct = max(0, min(100, (int) $promo->percent));
        return round($basePrice * (100 - $pct) / 100, 2);
    }
}
