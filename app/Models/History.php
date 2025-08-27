<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
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
        'product_id',
        'specialty_id',
        'user_id',
        'type',
        'code',
        'amount',
        'quantity',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
