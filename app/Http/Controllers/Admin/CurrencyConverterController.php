<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CurrencyConverter;
use App\Http\Controllers\Controller;

class CurrencyConverterController extends Controller
{
    public function show()
    {
        $converter = CurrencyConverter::firstOrCreate(['id' => 1], [
            'dollar_price' => 0,
        ]);

        return view('admin.settings.settings', compact('converter'));
    }

    // Update the dollar price
    public function update(Request $request)
    {
        $validated = $request->validate([
            'dollar_price' => ['required', 'numeric', 'min:0'],
        ]);

        $converter = CurrencyConverter::firstOrCreate(['id' => 1]);
        $converter->dollar_price = $validated['dollar_price'];
        $converter->save();

        return redirect()->back()->with('message', 'Dollar Price Updated Successfully');
    }
}
