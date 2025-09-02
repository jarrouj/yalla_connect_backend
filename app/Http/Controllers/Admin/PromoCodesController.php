<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoCodesController extends Controller
{
    public function show_promo_codes()
    {

        $promo = PromoCode::latest()->paginate(10);
        return view('admin.promo.show_promo', compact('promo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'            => 'nullable|string|unique:promo_codes,code',
            'promo'           => 'nullable|string|unique:promo_codes,code',
            'percent'         => 'nullable|integer|min:1|max:100',
            'discount'        => 'nullable|integer|min:1|max:100',
            'is_active'       => 'nullable|boolean',
            'active'          => 'nullable|in:0,1',
            'global_one_time' => 'nullable|in:0,1', // accept 0/1 from the form
            'starts_at'       => 'nullable|date',
            'ends_at'         => 'nullable|date|after:starts_at',
        ]);

        $data = [
            'code'            => $request->input('code', $request->input('promo')),
            'percent'         => (int) $request->input('percent', $request->input('discount')),
            'is_active'       => (int) $request->input('is_active', $request->input('active', 0)) ? 1 : 0,
            // 👇 this is the key line
            'global_one_time' => (int) $request->input('global_one_time', 0),
            'starts_at'       => $request->input('starts_at'),
            'ends_at'         => $request->input('ends_at'),
        ];

        if (!$data['code'] || !$data['percent']) {
            return back()->withErrors([
                'promo'    => 'Promo code is required.',
                'discount' => 'Discount percent is required.',
            ])->withInput();
        }

        PromoCode::create($data);

        return back()->with('message', 'Promo code created successfully.');
    }

    public function update(Request $request, PromoCode $promo)
    {
        $request->validate([
            'code'            => ['nullable', 'string', Rule::unique('promo_codes', 'code')->ignore($promo->id)],
            'percent'         => 'nullable|integer|min:1|max:100',
            'is_active'       => 'nullable|boolean',
            'global_one_time' => 'nullable|in:0,1',
            'starts_at'       => 'nullable|date',
            'ends_at'         => 'nullable|date|after:starts_at',
        ]);

        $promo->update([
            'code'            => $request->input('code', $promo->code),
            'percent'         => (int) $request->input('percent', $promo->percent),
            'is_active'       => (int) $request->input('is_active', (int)$promo->is_active),
            // ✅ key line:
            'global_one_time' => $request->boolean('global_one_time'),
            'starts_at'       => $request->input('starts_at', $promo->starts_at),
            'ends_at'         => $request->input('ends_at', $promo->ends_at),
        ]);

        return back()->with('message', 'Promo updated successfully.');
    }

    public function destroy(PromoCode $promo)
    {
        $promo->delete();
        return back()->with('message', 'Promo code deleted successfully.');
    }
}
