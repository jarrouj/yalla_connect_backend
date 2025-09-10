<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CurrencyConverter;
use App\Http\Controllers\Controller;

class CurrencyConverterController extends Controller
{
    public function show_dollar_price()
    {
        $dollar_price = CurrencyConverter::find(1)->get();
        return view('admin.settings.settings' , compact('dollar_price'));
    }

    public function update_dollar_price(Request $request){

        $dollar_price_update = CurrencyConverter::find(1);
        $dollar_price_update->dollar_price = $request->dollar_price;

        $dollar_price_update->save();

        return redirect()->back()->with('message', 'Dollar Price Updated Successfully');
    }
}
