<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
      protected $fillable = [
        'user_id',
        'type_of_transaction',
        'amount',
    ];

    // relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
