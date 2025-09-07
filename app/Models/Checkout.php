<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'product_id','specialty_id','user_id',
        'title','price','final_price','quantity','total_paid',
        'promo_code','promo_percent','is_completed'
      ];
}
