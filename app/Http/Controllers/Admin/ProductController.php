<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List products
   public function index()
    {
        $products = Product::with(['category', 'subcategory'])->paginate(10);
        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view('admin.product.show_product', compact('products', 'categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'code' => 'nullable|required_if:subcategory_id,' . $this->getVoucherSubcategoryId(),
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
        }

        Product::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'code' => $this->isVoucher($request->subcategory_id) ? $request->code : null,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('message', 'Product added successfully.');
    }

    private function getVoucherSubcategoryId()
    {
        return Subcategory::where('name', 'voucher')->value('id');
    }

    private function isVoucher($subcategoryId)
    {
        return $subcategoryId == $this->getVoucherSubcategoryId();
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload (replace old image if new one is uploaded)
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                unlink(public_path('images/products/' . $product->image));
            }
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'category_id' => $request->category_id,
            'type' => $request->type,
            'code' => $request->type === 'voucher' ? $request->code : null,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $product->image ?? null,
        ]);

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
            unlink(public_path('images/products/' . $product->image));
        }
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
