<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCode;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
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
        // Decide once, reuse everywhere
        $isVoucher = $this->isVoucherSub($request->subcategory_id);

        // Base rules
        $rules = [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Make codes conditional
        if ($isVoucher) {
            $rules['codes']   = ['required', 'array', 'min:1'];
            $rules['codes.*'] = ['required', 'string', 'max:255', 'distinct'];
        } else {
            // Ignore any posted codes fields when not voucher
            $rules['codes']   = [Rule::excludeIf(true)];
            $rules['codes.*'] = [Rule::excludeIf(true)];
            // Alternatively, if you want to explicitly block them instead of ignoring:
            // $rules['codes'] = ['prohibited'];
        }

        $validated = $request->validate($rules);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
        }

        $subcategory = Subcategory::findOrFail($validated['subcategory_id']);

        // Create product
        $product = Product::create([
            'category_id'    => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'],
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'image'          => $imageName,              // store filename; or switch to Storage later
            'type'           => $subcategory->name,      // if you still want to keep this
        ]);

        // Save codes only for voucher subcategories
        if ($isVoucher) {
            $codes = collect($request->input('codes', []))
                ->filter(fn($c) => filled($c))
                ->unique()
                ->values()
                ->map(fn($c) => ['code' => $c])
                ->all();

            if (!empty($codes)) {
                $product->codes()->createMany($codes);
            }
        }

        return redirect()->back()->with('message', 'Product added successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('subcategory')->findOrFail($id);

        // Get the new subcategory name from DB
        $newSubcategory = Subcategory::findOrFail($request->subcategory_id);
        $isVoucherNew   = strtolower($newSubcategory->name) === 'voucher';

        // Base validation rules
        $rules = [
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Add voucher-specific validation
        if ($isVoucherNew) {
            $rules['codes']   = ['required', 'array', 'min:1'];
            $rules['codes.*'] = ['required', 'string', 'max:255', 'distinct'];
        }

        $request->validate($rules);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                @unlink(public_path('images/products/' . $product->image));
            }
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        // Track if it WAS voucher before
        $wasVoucherOld = $product->subcategory && strtolower($product->subcategory->name) === 'voucher';

        // Update product
        $product->update([
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'price'          => $request->price,
            'image'          => $product->image ?? null,
            'type'           => $newSubcategory->name,
        ]);

        // Manage codes
        if ($isVoucherNew) {
            $newCodes = collect($request->input('codes', []))
                ->filter(fn($c) => filled($c))
                ->unique()
                ->values();

            $product->codes()->whereNotIn('code', $newCodes)->delete();

            $existing = $product->codes()->pluck('code');
            $toInsert = $newCodes->diff($existing);

            if ($toInsert->isNotEmpty()) {
                $product->codes()->createMany($toInsert->map(fn($c) => ['code' => $c])->all());
            }

            // Clear legacy single code
            if (isset($product->code)) {
                $product->code = null;
                $product->save();
            }
        } else {
            // Remove all codes if no longer voucher
            $product->codes()->delete();
            if (isset($product->code)) {
                $product->code = null;
                $product->save();
            }
        }

        return back()->with('success', 'Product updated successfully.');
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
