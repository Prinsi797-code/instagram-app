<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'coins',
        'giveaway',
        'pkg_image_url',
        'label_popular',
        'label_color',
        'price_per_coin',
        'total_price',
        'product_id'
    ];

    public function getPkgImageUrlAttribute($value)
    {
        return $value ? url($value) : null;
    }
}