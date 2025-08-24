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
use App\Services\PromoService;

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
public function getAllProducts(Request $request): JsonResponse
{
    try {
        $promo = optional($request->user())->activePromoCode; // null if unauthenticated

        $products = Product::with(['category', 'subcategory'])->get()
            ->map(function ($p) use ($promo) {
                // ensure price is numeric
                $price = (float) $p->price;
                $discounted = PromoService::discountedPrice($price, $promo);

                return [
                    'id'               => $p->id,
                    'title'            => $p->title,
                    'image'            => $p->image,
                    'price'            => $price,            // original
                    'discounted_price' => $discounted,       // per-user
                    'discount_percent' => $promo?->percent ?? 0,
                    'category'         => $p->category,
                    'subcategory'      => $p->subcategory,
                ];
            });

        return response()->json([
            'status'  => true,
            'message' => 'Products fetched successfully.',
            'data'    => $products,
            'promo'   => $promo ? ['code' => $promo->code, 'percent' => $promo->percent] : null,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Failed to fetch products.',
            'error'   => $e->getMessage()
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
