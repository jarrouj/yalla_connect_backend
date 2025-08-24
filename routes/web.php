<?php

use App\Http\Controllers\Admin\AddBalanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\PromoCodeController;

Route::redirect('/', '/login');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::prefix('/admin')->middleware(['auth', 'checkUserType'])->group(function () {

    Route::get('/', [CmsController::class, 'dash']);

    // {{ User }}
    Route::get('/show_user', [UserController::class, 'show_user']);
    Route::get('/update_user/{id}', [UserController::class, 'update_user']);
    Route::post('/update_user_confirm/{id}', [UserController::class, 'update_user_confirm']);
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user']);
    Route::get('/search_user', [UserController::class, 'search_user']);

    // {{ Category }}
    Route::get('/show_categories', [CategoryController::class, 'index']);
    Route::post('/add_category', [CategoryController::class, 'add_category']);
    Route::post('/update_category/{id}', [CategoryController::class, 'update']);
    Route::get('/delete_category/{id}', [CategoryController::class, 'delete_category']);
    Route::get('/delete_category_with_products/{id}', [CategoryController::class, 'delete_category_with_products']);

    // {{ Types }}
    Route::get('/show_types', [SubcategoryController::class, 'index']);
    Route::post('/add_subcategory', [SubcategoryController::class, 'store']);
    Route::post('/update_subcategory/{id}', [SubcategoryController::class, 'update']);
    Route::delete('/delete_subcategory/{id}', [SubcategoryController::class, 'destroy']);
    Route::get('/delete_subcategory_with_products/{id}', [SubcategoryController::class, 'destroyWithProducts']);

    // {{ Product }}
    Route::get('/show_product', [ProductController::class, 'index']);
    Route::post('/add_product', [ProductController::class, 'store']);
    Route::post('/update_product/{id}', [ProductController::class, 'update']);
    Route::get('/view_product/{id}', [ProductController::class, 'view_product']);
    Route::post('/update_product_confirm/{id}', [ProductController::class, 'update_product_confirm']);
    Route::get('/delete_product/{id}', [ProductController::class, 'delete_product']);
    Route::get('/search_product', [ProductController::class, 'search_product']);

    // {{ Specialty }}
    Route::get('/show_specialties', [SpecialtyController::class, 'index']);
    Route::post('/add_specialty', [SpecialtyController::class, 'store']);
    Route::post('/update_specialty/{id}', [SpecialtyController::class, 'update']);
    Route::delete('/delete_specialty/{id}', [SpecialtyController::class, 'destroy']);

    // {{ Offer }}
    Route::get('/show_offers', [OfferController::class, 'show_offers']);
    Route::post('/add_offer', [OfferController::class, 'add_offer']);
    Route::post('/update_offer/{id}', [OfferController::class, 'update_offer']);
    Route::get('/delete_offer/{id}', [OfferController::class, 'delete_offer']);

    // {{ Show User Balance }}
        Route::get('/show_user_balance', [AddBalanceController::class, 'show_user']);
        Route::post('/users/{id}/balance/add', [AddBalanceController::class, 'add_balance'])->name('admin.users.balance.add');




    // // {{ Social }}
    // Route::get('/show_social',[SocialController::class,'show_social']);
    // Route::post('/update_social_confirm/{id}',[SocialController::class,'update_social_confirm']);
    // Route::get('/update_social/{id}',[SocialController::class,'update_social']);

    // // {{ Subscriber }}
    // Route::get('/show_subscriber',[SubscriberController::class,'show_subscriber']);
    // Route::post('/update_subscriber/{id}',[SubscriberController::class,'update_subscriber']);
    // Route::get('/delete_subscriber/{id}',[SubscriberController::class,'delete_subscriber']);
    // Route::post('/send_email', [SubscriberController::class, 'message'])->name('send.email');

    // // {{ Promo }}
    // Route::get('/show_promo' , [PromoController::class , 'show_promo']);
    // Route::post('/add_promo' , [PromoController::class , 'add_promo']);
    // Route::post('/update_promo/{id}' , [PromoController::class , 'update_promo']);
    // Route::get('/delete_promo' , [PromoController::class , 'delete_promo']);

    // // {{ Term }}
    // Route::get('/show_term' , [TermController::class , 'show_term']);
    // Route::post('/add_term' , [TermController::class , 'add_term']);
    // Route::post('/update_term/{id}' , [TermController::class , 'update_term']);
    // Route::get('/delete_term/{id}' , [TermController::class , 'delete_term']);

    // // {{ Privacy }}
    // Route::get('/show_privacy' , [PrivacyController::class , 'show_privacy']);
    // Route::post('/add_privacy' , [PrivacyController::class , 'add_privacy']);
    // Route::post('/update_privacy/{id}' , [PrivacyController::class , 'update_privacy']);
    // Route::get('/delete_privacy/{id}' , [PrivacyController::class , 'delete_privacy']);

    // // {{ Testimonial }}
    // Route::get('/show_testimonial' , [TestimonialController::class , 'show_testimonial']);
    // Route::post('/add_testimonial' , [TestimonialController::class , 'add_testimonial']);
    // Route::post('/update_testimonial/{id}' , [TestimonialController::class , 'update_testimonial']);
    // Route::get('/delete_testimonial/{id}' , [TestimonialController::class , 'delete_testimonial']);

    // // {{ Collection }}
    // Route::get('/show_collection' , [CollectionController::class , 'show_collection']);
    // Route::post('/add_collection' , [CollectionController::class , 'add_collection']);
    // Route::post('/update_collection/{id}' , [CollectionController::class , 'update_collection']);
    // Route::get('/delete_collection/{id}' , [CollectionController::class , 'delete_collection']);
    // Route::get('/delete_collection_with_associate/{id}' , [CollectionController::class , 'delete_collection_with_associate']);

    // // {{ Landing }}
    // Route::get('/show_landing' , [LandingController::class , 'show_landing']);
    // Route::post('/add_landing' , [LandingController::class , 'add_landing']);
    // Route::post('/update_landing/{id}' , [LandingController::class , 'update_landing']);
    // Route::get('/delete_landing/{id}' , [LandingController::class , 'delete_landing']);

    // // {{ Tag }}
    // Route::get('/show_tag' , [TagController::class , 'show_tag']);
    // Route::post('/add_tag' , [TagController::class , 'add_tag']);
    // Route::post('/update_tag/{id}' , [TagController::class , 'update_tag']);
    // Route::get('/delete_tag/{id}' , [TagController::class , 'delete_tag']);






    // // {{ Product Image }}
    // Route::post('/add_product_image' , [ProductImageController::class , 'add_product_image']);
    // Route::post('/update_product_image/{id}' , [ProductImageController::class , 'update_product_image']);
    // Route::get('/delete_product_image/{id}' , [ProductImageController::class , 'delete_product_image']);

    // // {{ Size }}
    // Route::post('/add_size' , [SizeController::class , 'add_size']);
    // Route::post('/update_size/{id}' , [SizeController::class , 'update_size']);
    // Route::get('/delete_size/{id}' , [SizeController::class , 'delete_size']);

    // // {{ Smell }}
    // Route::post('/add_smell' , [SmellController::class , 'add_smell']);
    // Route::post('/update_smell/{id}' , [SmellController::class , 'update_smell']);
    // Route::get('/delete_smell/{id}' , [SmellController::class , 'delete_smell']);

    // // {{ About }}
    // Route::get('/show_about' , [AboutController::class , 'show_about']);
    // Route::get('/update_about/{id}' , [AboutController::class , 'update_about']);
    // Route::post('/update_about_confirm/{id}' , [AboutController::class , 'update_about_confirm']);
    // Route::post('/add_about_img' , [AboutController::class , 'add_about_img']);
    // Route::post('/add_about_point' , [AboutController::class , 'add_about_point']);


    // // {{ Cart }}
    // Route::get('/show_cart' , [CartController::class , 'show_cart']);
    // Route::post('/add_cart' , [cartController::class , 'add_cart']);
    // Route::post('/update_cart/{id}' , [cartController::class , 'update_cart']);
    // Route::get('/delete_cart/{id}' , [cartController::class , 'delete_cart']);

    // // {{ Order }}
    // Route::get('/show_order' , [OrderController::class , 'show_order']);
    // Route::post('/add_order' , [OrderController::class , 'add_order']);
    // Route::get('/{start_date}/{end_date}/update_order/{id}' , [OrderController::class , 'update_order']);
    // Route::post('/{start_date}/{end_date}/update_order_confirm/{id}' , [OrderController::class , 'update_order_confirm']);
    // Route::get('/delete_order/{id}' , [OrderController::class , 'delete_order']);
    // Route::get('/view_order/{id}' , [OrderController::class , 'view_order']);
    // Route::get('/search_order' , [OrderController::class , 'search_order']);
    // Route::post('/update-status/{id}',[OrderController::class,'update_status'])->name('update-status');


    // // {{ Offer }}
    // Route::get('/show_offer', [OfferController::class,'show_offer']);
    // Route::get('/add_offer', [OfferController::class, 'add_offer']);
    // Route::post('/add_offer_confirm', [OfferController::class, 'add_offer_confirm']);
    // Route::get('/update_offer/{id}', [OfferController::class, 'update_offer']);
    // Route::post('/update_offer_confirm/{id}', [OfferController::class, 'update_offer_confirm']);
    // Route::get('/delete_offer/{id}', [OfferController::class, 'delete_offer']);
    // Route::get('/view_offer/{id}', [OfferController::class,'view_offer']);
    // Route::get('/search_product_offer' , [offerController::class , 'search_product_offer']);
    // Route::get('/search_offer' , [offerController::class , 'search_offer']);
    // Route::post('/add-single-offer', [offerController::class, 'addSelectedProduct']);
    // Route::get('/reload_table_data', [offerController::class, 'reloadTableData'])->name('admin.reload_table_data');

    // // {{ General }}
    // Route::get('/show_general' , [GeneralController::class , 'show_general']);
    // Route::get('/update_general/{id}' , [GeneralController::class , 'update_general']);
    // Route::post('/update_general_confirm/{id}' , [GeneralController::class , 'update_general_confirm']);

    // // {{ Transaction }}
    // Route::get('/show_transaction' , [TransactionController::class , 'show_transaction']);


    // // {{ Date }}

    // Route::get('/filter_date', [CmsController::class, 'filterDate'])->name('filter_date')->middleware('web');

});
