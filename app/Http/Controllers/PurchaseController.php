<?php

namespace App\Http\Controllers;

use App\Models\UserPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function purchaseUser(Request $request)
    {
        // Validate the request (optional, if you want to ensure device_id is provided)
        $request->validate([
            'device_id' => 'nullable|string|exists:user_purchase,device_id',
        ]);

        // Query to fetch the required details
        $purchaseDetails = DB::table('user_purchase')
            ->join('users', 'user_purchase.device_id', '=', 'users.device_id')
            ->join('get_coupons', 'user_purchase.product_id', '=', 'get_coupons.product_id')
            ->select(
                'users.device_id',
                'users.coin_count',
                'user_purchase.product_id',
                'user_purchase.id',
                'get_coupons.coins',
                'get_coupons.giveaway',
                'get_coupons.pkg_image_url',
                'get_coupons.label_popular',
                'get_coupons.label_color',
                'get_coupons.price_per_coin',
                'get_coupons.total_price'
            )
            ->when($request->device_id, function ($query, $device_id) {
                return $query->where('user_purchase.device_id', $device_id);
            })
            ->get();

        return view('purchase.index', compact('purchaseDetails'));
    }
}