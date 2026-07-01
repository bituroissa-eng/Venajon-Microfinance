<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    public function index()
    {
        $setting = SystemSetting::firstOrCreate(
            ['id' => 1],
            ['name' => 'Venajon Microfinance']
        );
        return view('system-settings.index', compact('setting'));
    }

    public function update(Request $request, SystemSetting $systemSetting)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($systemSetting->logo_path) {
                Storage::disk('public')->delete($systemSetting->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('system', 'public');
        }

        $systemSetting->update($validated);

        return redirect()->route('system-settings.index')->with('success', 'System settings updated successfully.');
    }
}
