<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    use HasFactory;

    protected $table = 'user_purchase';
    protected $fillable = [
        'device_id',
        'product_id'
    ];
}