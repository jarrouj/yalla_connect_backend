<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'type',
        'title',
        'description',
        'price',
        'image',
        'code',
        'subcategory_id',
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

   public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function codes()
    {
        return $this->hasMany(ProductCodes::class);
    }
}
