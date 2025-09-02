<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Specialty;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Services\PromoService;
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

public function getAllSpecialties(Request $request): JsonResponse
{
    try {
        // If the user is authenticated and has an active promo, use it.
        $promo = optional($request->user())->activePromoCode;
        if ($promo && method_exists($promo, 'isCurrentlyValid') && !$promo->isCurrentlyValid()) {
            $promo = null; // ignore invalid/expired promo
        }

        // Fetch specialties (filter to active if that's your UI rule)
        $specialties = Specialty::query()
            ->where('is_active', true) // <- remove if you want all
            ->get()
            ->map(function ($s) use ($promo) {
                $price       = (float) $s->price;
                $discounted  = PromoService::discountedPrice($price, $promo);

                return [
                    'id'                => $s->id,
                    'name'              => $s->name,
                    'description'       => $s->description,
                    'image'             => $s->image,
                    'time'              => $s->time,          // duration label
                    'is_active'         => (bool) $s->is_active,
                    'price'             => $price,            // original
                    'discounted_price'  => $discounted,       // per-user price
                    'discount_percent'  => $promo?->percent ?? 0,
                ];
            });

        return response()->json([
            'status'  => true,
            'message' => 'Specialties fetched successfully.',
            'data'    => $specialties,
            'promo'   => $promo ? ['code' => $promo->code, 'percent' => $promo->percent] : null,
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Failed to fetch Specialties.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}
    // public function getProductsBySubcategory($id)
    // {
    //     try {
    //         $products = Product::with(['category', 'subcategory'])
    //             ->where('subcategory_id', $id)
    //             ->get();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Products fetched successfully.',
    //             'data' => $products
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to fetch products.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getProductsBySubcategory(Request $request, $id)
{
    try {
        // same logic as getAllProducts
        $promo = optional($request->user())->activePromoCode; // null if unauthenticated

        $products = Product::with(['category', 'subcategory'])
            ->where('subcategory_id', $id)
            ->get()
            ->map(function ($p) use ($promo) {
                $price = (float) $p->price;
                $discounted = PromoService::discountedPrice($price, $promo);

                return [
                    'id'               => $p->id,
                    'title'            => $p->title,
                    'image'            => $p->image,
                    'price'            => $price,                 // original
                    'discounted_price' => $discounted,            // per-user
                    'discount_percent' => $promo?->percent ?? 0,  // 0 if no promo
                    'category'         => $p->category,
                    'subcategory'      => $p->subcategory,        // frontend can read sub.name, etc.
                ];
            });

        return response()->json([
            'status'  => true,
            'message' => 'Products fetched successfully.',
            'data'    => $products,
            'promo'   => $promo ? ['code' => $promo->code, 'percent' => $promo->percent] : null,
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Failed to fetch products.',
            'error'   => $e->getMessage(),
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
