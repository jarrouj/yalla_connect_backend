<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->paginate(10);
        $categories = Category::all();

        return view('admin.types.show_types', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
        }

        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Subcategory added successfully.');
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $imagePath = $subcategory->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
        }

        $subcategory->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('message', 'Subcategory updated successfully.');
    }


    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        // delete only the subcategory (keep products)
        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
            // if stored via ->store('subcategory_images','public')
        }

        $subcategory->delete();

        return redirect()->back()->with('message', 'Subcategory deleted successfully.');
    }

   public function destroyWithProducts($id)
    {
        DB::beginTransaction();

        try {
            $subcategory = Subcategory::findOrFail($id);

            // Delete products in chunks for safety if many
            Product::where('subcategory_id', $subcategory->id)
                ->chunkById(100, function ($products) {
                    foreach ($products as $product) {

                        // MAIN product image (stored on 'public' disk as a path like 'product_images/xx.jpg')
                        if (!empty($product->image)) {
                            Storage::disk('public')->delete($product->image);

                            // If you store filenames in /public/images/products:
                            // $full = public_path('images/products/' . $product->image);
                            // if (file_exists($full)) @unlink($full);
                        }

                        // EXTRA product images (if you have a product_images table)
                        if (method_exists($product, 'images')) {
                            $product->loadMissing('images');
                            foreach ($product->images as $pimg) {
                                if (!empty($pimg->image)) {
                                    Storage::disk('public')->delete($pimg->image);
                                }
                                $pimg->delete();
                            }
                        }

                        $product->delete(); // or ->forceDelete() if using SoftDeletes and you want hard delete
                    }
                });

            // Delete subcategory image (stored on 'public' disk as 'subcategory_images/...')
            if (!empty($subcategory->image)) {
                Storage::disk('public')->delete($subcategory->image);
            }

            $subcategory->delete(); // or ->forceDelete() if using SoftDeletes

            DB::commit();

            return redirect()->back()->with('message', 'Subcategory and its products deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Failed to delete subcategory with products.');
        }
    }
}
