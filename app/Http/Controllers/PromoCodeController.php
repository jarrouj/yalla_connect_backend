<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Services\PromoService;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function __construct(private PromoService $service) {}

    public function apply(Request $request)
    {
        $user = $request->user(); // with auth:sanctum, this should be your User

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $data  = $request->validate(['code' => 'required|string']);
        $promo = $this->service->applyCode($user, $data['code']);

        return response()->json([
            'message' => 'Promo applied.',
            'promo'   => $promo,
        ]);
    }

    public function remove(Request $request)
    {
        $this->service->removeCode($request->user());
        return response()->json(['message' => 'Promo removed.']);
    }

    //admin

      public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'percent' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
            'global_one_time' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $promo = PromoCode::create($data);
        return response()->json(['message'=>'Created','promo'=>$promo], 201);
    }

    public function update(Request $request, PromoCode $promo)
    {
        $data = $request->validate([
            'percent' => 'sometimes|integer|min:1|max:100',
            'is_active' => 'sometimes|boolean',
            'global_one_time' => 'sometimes|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);
        $promo->update($data);
        return response()->json(['message'=>'Updated','promo'=>$promo]);
    }

    public function destroy(PromoCode $promo)
    {
        $promo->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
