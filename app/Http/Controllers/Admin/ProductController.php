<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCode;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    // List products
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'codes']) // ← add codes
            ->withCount('codes')                                        // optional, handy
            ->paginate(10);

        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view('admin.product.show_product', compact('products', 'categories', 'subcategories'));
    }


    private function voucherSubId(): ?int
    {
        static $id;
        return $id ??= Subcategory::whereRaw('LOWER(name)=?', ['voucher'])->value('id');
    }

    private function isVoucherSub($subcategoryId): bool
    {
        return (int)$subcategoryId === (int)$this->voucherSubId();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            // codes[] required only if voucher
            'codes'          => ['nullable','array', Rule::requiredIf(fn() => $this->isVoucherSub($request->subcategory_id))],
            'codes.*'        => 'required|string|max:255|distinct',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
        }

        $subcategory = Subcategory::findOrFail($request->subcategory_id);

        $product = Product::create([
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'price'          => $request->price,
            'image'          => $imageName,
            'type'           => $subcategory->name,   // you’re keeping this
        ]);

        // Save codes if voucher
        if ($this->isVoucherSub($request->subcategory_id)) {
            $codes = collect($request->input('codes', []))
                ->filter(fn($c) => filled($c))
                ->unique()
                ->values();

            $product->codes()->createMany($codes->map(fn($c) => ['code' => $c])->all());
        }

        return redirect()->back()->with('message', 'Product added successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'type'           => 'required|string',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'codes'          => ['nullable','array', Rule::requiredIf(fn() => $this->isVoucherSub($request->subcategory_id))],
            'codes.*'        => 'required|string|max:255|distinct',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('images/products/'.$product->image))) {
                @unlink(public_path('images/products/'.$product->image));
            }
            $imageName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'type'           => $request->type,
            'title'          => $request->title,
            'description'    => $request->description,
            'price'          => $request->price,
            'image'          => $product->image ?? null,
        ]);

        // Sync codes if voucher; otherwise delete any existing codes
        if ($this->isVoucherSub($request->subcategory_id)) {
            $newCodes = collect($request->input('codes', []))
                ->filter(fn($c) => filled($c))
                ->unique()
                ->values();

            // Delete codes that are not in the new list
            $product->codes()->whereNotIn('code', $newCodes)->delete();

            // Upsert new/changed codes
            // (unique key is product_id+code; this inserts missing ones)
            $toInsert = $newCodes->diff($product->codes()->pluck('code'));
            if ($toInsert->isNotEmpty()) {
                $product->codes()->createMany($toInsert->map(fn($c) => ['code' => $c])->all());
            }
        } else {
            // not voucher anymore → remove any previously saved codes
            $product->codes()->delete();
        }

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
