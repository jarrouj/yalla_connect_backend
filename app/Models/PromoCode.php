<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
     protected $fillable = ['code','percent','is_active','global_one_time','starts_at','ends_at'];
    protected $casts = ['is_active'=>'bool','global_one_time'=>'bool','starts_at'=>'datetime','ends_at'=>'datetime'];

    public function redemptions() { return $this->hasMany(PromoCodeRedemption::class); }

    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) return false;
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at && $now->gt($this->ends_at)) return false;
        if ($this->global_one_time && $this->redemptions()->where('status','consumed')->exists()) return false;
        return $this->percent >= 1 && $this->percent <= 100;
    }
}
