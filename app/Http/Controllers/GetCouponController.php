<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GetCoupon;
use Illuminate\Support\Facades\File;

class GetCouponController extends Controller
{
    public function coupon(Request $request)
    {
        $coupons = GetCoupon::all();

        return view('Coupon.index', compact('coupons'));
    }
    public function index(Request $request)
    {
        return view('Coupon.store');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'coins' => 'nullable|string|max:255',
            'giveaway' => 'nullable|string|max:255',
            'pkg_image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Max 2MB
            'label_popular' => 'nullable|string|max:255',
            'label_color' => 'nullable|string|max:255',
            'price_per_coin' => 'nullable|string|max:255',
            'total_price' => 'nullable|string|max:255',
            'product_id' => 'nullable|string|max:255',
        ]);

        // Initialize the data array
        $data = $request->only([
            'title',
            'coins',
            'giveaway',
            'label_popular',
            'label_color',
            'price_per_coin',
            'total_price',
            'product_id'
        ]);

        // Handle the image upload
        if ($request->hasFile('pkg_image_url')) {
            // Create the coupons folder if it doesn't exist
            $folderPath = public_path('coupons');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Generate a unique filename
            $image = $request->file('pkg_image_url');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($folderPath, $imageName);

            // Store the relative path to the image
            $data['pkg_image_url'] = 'coupons/' . $imageName;
        }

        // Create the record in the get_coupons table
        GetCoupon::create($data);

        // Redirect back with a success message
        return redirect()->route('index')->with('success', 'Coupon added successfully!');
    }

    public function edit($id)
    {
        $coupon = GetCoupon::findOrFail($id);
        return view('Coupon.coupons-edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'coins' => 'nullable|string|max:255',
            'giveaway' => 'nullable|string|max:255',
            'pkg_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // Max 5MB
            'label_popular' => 'nullable|string|max:255',
            'label_color' => 'nullable|string|max:255',
            'price_per_coin' => 'nullable|string|max:255',
            'total_price' => 'nullable|string|max:255',
            'product_id' => 'nullable|string|max:255',
        ]);

        // Find the coupon
        $coupon = GetCoupon::findOrFail($id);

        // Initialize the data array
        $data = $request->only([
            'title',
            'coins',
            'giveaway',
            'label_popular',
            'label_color',
            'price_per_coin',
            'total_price',
            'product_id'
        ]);

        // Handle the image upload
        if ($request->hasFile('pkg_image_url')) {
            // Delete the old image if it exists
            if ($coupon->pkg_image_url && File::exists(public_path($coupon->pkg_image_url))) {
                File::delete(public_path($coupon->pkg_image_url));
            }

            // Create the coupons folder if it doesn't exist
            $folderPath = public_path('coupons');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Generate a unique filename
            $image = $request->file('pkg_image_url');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($folderPath, $imageName);

            // Store the relative path to the image
            $data['pkg_image_url'] = 'coupons/' . $imageName;
        }

        // Update the coupon
        $coupon->update($data);

        // Flash success message
        return redirect()->route('index')->with('success', 'Coupon updated successfully!');
    }

    public function destroy($id)
    {
        // Find the coupon
        $coupon = GetCoupon::findOrFail($id);

        // Delete the image if it exists
        if ($coupon->pkg_image_url && File::exists(public_path($coupon->pkg_image_url))) {
            File::delete(public_path($coupon->pkg_image_url));
        }

        // Delete the coupon
        $coupon->delete();

        // Flash success message
        return redirect()->route('index')->with('success', 'Coupon deleted successfully!');
    }
}