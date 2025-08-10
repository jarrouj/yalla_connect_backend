<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCodes extends Model
{
    protected $fillable = ['product_id', 'code'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
