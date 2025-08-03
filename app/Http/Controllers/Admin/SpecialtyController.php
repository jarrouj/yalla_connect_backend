<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

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
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = $request->file('image')?->store('specialty_images', 'public');

        Specialty::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Specialty added successfully.');
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $specialty->image = $request->file('image')->store('specialty_images', 'public');
        }

        $specialty->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $specialty->image,
        ]);

        return redirect()->back()->with('success', 'Specialty updated successfully.');
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();
        return redirect()->back()->with('success', 'Specialty deleted successfully.');
    }
}
