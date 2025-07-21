<?php

namespace App\Http\Controllers;

use App\Models\GetCoupon;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function dashboard(Request $request)
    {
        $userCount = User::count();
        $couponCount = GetCoupon::count();
        return view('home', compact('userCount', 'couponCount'));
    }

    public function Users(Request $request)
    {
        $users = User::with('tokens')->get();

        return view("User.index", compact('users'));

    }
}