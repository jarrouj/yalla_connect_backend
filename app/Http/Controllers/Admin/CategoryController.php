<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.category.show_category', compact('categories'));
    }


    public function add_category(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/categories'), $imageName);

            $category->image = $imageName; // Save filename in DB
            $imageUrl = asset('images/categories/' . $imageName); // Public URL
        }

        $category->save();

        return redirect()->back()->with('message', 'Category created successfully.');

        // return response()->json([
        //     'message' => 'Category created successfully',
        //     'category' => $category,
        //     'image_url' => $imageUrl,
        // ]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->back()->with('message', 'Category updated successfully.');
    }

      public function delete_category($id)
    {
        $category = Category::findOrFail($id);

        // remove category image file from public/images/categories
        $this->deleteCategoryImage($category->image);

        $category->delete();

        return redirect('/admin/show_categories')->with('message', 'Category deleted successfully.');
    }


   // Delete category + related (types/subcategories & products) and their images where applicable
    public function destroyWithProducts($id)
    {
        DB::beginTransaction();

        try {
            $category = Category::with([
                'types',                // or 'subcategories'
                'products.images',
            ])->findOrFail($id);

            // 1) Delete all products under this category (and their image rows if any)
            foreach ($category->products as $product) {
                // If you also store a main product image file somewhere, you can delete it here similarly
                // $this->deleteProductMainImage($product->image);

                if ($product->relationLoaded('images')) {
                    foreach ($product->images as $pimg) {
                        // If product extra images were stored on 'public' disk (e.g. 'product_images/..'), delete like subcategory
                        // $this->deletePublicDiskFile($pimg->image);
                        $pimg->delete();
                    }
                }
                $product->delete();
            }

            // 2) Delete all types/subcategories under this category (including their stored files)
            foreach ($category->types as $type) {
                // IMPORTANT: your subcategory/type upload uses:
                // $imagePath = $request->file('image')->store('subcategory_images', 'public');
                // So the DB likely stores something like 'subcategory_images/xxxx.jpg'
                $this->deleteSubcategoryImage($type->image);
                $type->delete();
            }

            // 3) Delete the category image file
            $this->deleteCategoryImage($category->image);

            // 4) Delete the category
            $category->delete();

            DB::commit();

            return redirect('/admin/show_categories')->with('message', 'Category and all related data deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect('/admin/show_categories')
                ->with('error', 'Failed to delete category and related data. Please try again.');
        }
    }

    /**
     * Delete a category image stored as a FILENAME under public/images/categories
     * Example stored value: '1724153492.jpg'
     */
    private function deleteCategoryImage(?string $filename): void
    {
        if (empty($filename)) return;

        $relative = 'images/categories/' . $filename;

        // If you ever switched to storing category images on the 'public' disk with this same relative path:
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
            return;
        }

        // Current approach: moved directly to public_path(...)
        $full = public_path($relative);
        if (file_exists($full)) {
            @unlink($full);
        }
    }

    /**
     * Delete a subcategory/type image stored via:
     * $imagePath = $request->file('image')->store('subcategory_images', 'public');
     * Example stored value: 'subcategory_images/1724153492.jpg'
     */
    private function deleteSubcategoryImage(?string $storedPath): void
    {
        if (empty($storedPath)) return;

        // Primary: delete from 'public' disk
        if (Storage::disk('public')->exists($storedPath)) {
            Storage::disk('public')->delete($storedPath);
            return;
        }

        // Fallback if file is accessible via public/storage symlink
        $fallback = public_path('storage/' . ltrim($storedPath, '/'));
        if (file_exists($fallback)) {
            @unlink($fallback);
        }
    }

    /**
     * Generic helper if you later need to delete any 'public' disk file.
     */
    private function deletePublicDiskFile(?string $storedPath): void
    {
        if (empty($storedPath)) return;
        if (Storage::disk('public')->exists($storedPath)) {
            Storage::disk('public')->delete($storedPath);
        } else {
            $fallback = public_path('storage/' . ltrim($storedPath, '/'));
            if (file_exists($fallback)) {
                @unlink($fallback);
            }
        }
    }
}
