<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\API\BalanceController;
use App\Http\Controllers\AuthApi\AuthApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Authentication routes
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/verify-email', [AuthApiController::class, 'verifyEmail']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);


//Balance routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/save-fcm-token', [BalanceController::class, 'saveFcmToken']);
    Route::post('/add-balance', [BalanceController::class, 'addBalance']);
    Route::post('/checkout/{productId}', [CheckoutController::class, 'checkoutProduct']);
    Route::post('/specialties/checkout/{specialtyId}', [CheckoutController::class, 'checkoutSpecialty']);
    Route::get('/history', [CheckoutController::class, 'userHistory']);
});


//check user balance
Route::middleware('auth:sanctum')->get('/user-balance', function (Request $request) {
    return response()->json([
        'balance' => $request->user()->balance
    ]);
});


//Api routes
Route::get('/categories', [ApiController::class, 'getAllCategories']);
Route::get('/subcategories', [ApiController::class, 'getAllSubcategories']);
Route::middleware('auth:sanctum')->get('/products', [ApiController::class, 'getAllProducts']);
Route::middleware('auth:sanctum')->get('/specialties', [ApiController::class, 'getAllSpecialties']);
Route::middleware('auth:sanctum')->get('/products/subcategory/{id}', [ApiController::class, 'getProductsBySubcategory']);
Route::get('/offers', [ApiController::class, 'getAllOffers']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/promo/apply', [PromoCodeController::class, 'apply']);
});
Route::post('/promo/remove', [PromoCodeController::class, 'remove']);



         Route::post('/promos', [PromoCodeController::class, 'store']);
    Route::patch('/promos/{promo}', [PromoCodeController::class, 'update']);
    Route::delete('/promos/{promo}', [PromoCodeController::class, 'destroy']);
