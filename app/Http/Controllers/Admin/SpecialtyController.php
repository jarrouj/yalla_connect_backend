<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::paginate(10);
        return view('admin.specialty.show_specialty', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->file('image')?->store('specialty_images', 'public');

        Specialty::create([
            'name' => $request->name,
            'description' => $request->description,
            'time' => $request->time ?? '', // like 30 days
            'image' => $imagePath,
            'price' => $request->price,
            'purchase_price' => $request->purchase_price,
            'is_active' => $request->is_active ?? true,
        ]);


        return redirect()->back()->with('message', 'Specialty added successfully.');
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $specialty->image = $request->file('image')->store('specialty_images', 'public');
        }

        $specialty->update([
            'name' => $request->name,
            'description' => $request->description,
            'time' => $request->time ?? '', // like 30 days
            'image' => $specialty->image,
            'price' => $request->price,
            'purchase_price' => $request->purchase_price,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('message', 'Specialty updated successfully.');
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();
        return redirect()->back()->with('message', 'Specialty deleted successfully.');
    }
}
