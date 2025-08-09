<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function show_offers()
    {
        $offers = Offer::latest()->paginate(10);
        return view('admin.offers.show_offers' , compact('offers'));
    }

    public function add_offer(Request $request)
    {
        $offer = new Offer();

        $offer->text = $request->text;
        $offer->save();


        return redirect()->back()->with('message' , 'Offer Added Successfully');
    }

    public function update_offer(Request $request , $id)
    {
        $offer = Offer::find($id);

        $offer->text = $request->text;
        $offer->save();


        return redirect()->back()->with('message' , 'Offer Updated Successfully');
    }

    public function delete_offer($id)
    {
        $offer = Offer::find($id);

        $offer->delete();

        return redirect()->back()->with('message' , 'Offer Deleted Successfully');
    }
}
