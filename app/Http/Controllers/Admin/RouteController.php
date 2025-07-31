<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        } else {
            abort(404);
        }
    }

    public function categories()
    {
        $categories = Category::all();

        return view('category.show_category', compact('categories'));
    }
}
