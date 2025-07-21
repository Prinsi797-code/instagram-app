<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting(Request $request)
    {
        $settings = Setting::get();

        return view('setting.index', compact(('settings')));
    }

    public function viewSetting(Request $request)
    {
        return view('setting.view');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'coins' => 'nullable|string|max:255',
            'giveaway' => 'nullable|string|max:255',
        ]);

        // Initialize the data array
        $data = $request->only([
            'coins',
            'giveaway'
        ]);

        // Create the record in the get_coupons table
        Setting::create($data);

        // Redirect back with a success message
        return redirect()->route('setting')->with('success', 'Setting added successfully!');
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('setting.settings-edit', compact('setting'));
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'coins' => 'nullable|string|max:255',
            'giveaway' => 'nullable|string|max:255',
        ]);

        // Find the coupon
        $setting = Setting::findOrFail($id);

        // Initialize the data array
        $data = $request->only([
            'coins',
            'giveaway'
        ]);

        // Update the coupon
        $setting->update($data);

        // Flash success message
        return redirect()->route('setting')->with('success', 'Setting updated successfully!');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        // Delete the Setting
        $setting->delete();
        // Flash success message
        return redirect()->route('setting')->with('success', 'Setting deleted successfully!');
    }
}