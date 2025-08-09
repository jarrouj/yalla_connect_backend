<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Specialty;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function getAllCategories(): JsonResponse
    {
        try {
            $categories = Category::all();

            return response()->json([
                'status' => true,
                'message' => 'Categories fetched successfully.',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch categories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllSubcategories(): JsonResponse
    {
        try {
            $subcategories = Subcategory::all();

            return response()->json([
                'status' => true,
                'message' => 'Subcategories fetched successfully.',
                'data' => $subcategories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch subcategories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllProducts(): JsonResponse
    {
        try {
            $products = Product::with(['category', 'subcategory'])->get();

            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully.',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllSpecialties(): JsonResponse
    {
        try {
            $Specialties = Specialty::all();

            return response()->json([
                'status' => true,
                'message' => 'Specialties fetched successfully.',
                'data' => $Specialties
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch Specialties.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductsBySubcategory($id)
    {
        try {
            $products = Product::with(['category', 'subcategory'])
                ->where('subcategory_id', $id)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully.',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllOffers(): JsonResponse
    {
        try {
            $Offers = Offer::all();

            return response()->json([
                'status' => true,
                'message' => 'Offers fetched successfully.',
                'data' => $Offers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch Offers.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
