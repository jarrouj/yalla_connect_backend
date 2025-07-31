<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

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

    // Delete category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Optionally delete image file from public folder
        if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
            unlink(public_path('images/categories/' . $category->image));
        }

        $category->delete();

        return redirect('/categories')->with('success', 'Category deleted successfully.');
    }
}
