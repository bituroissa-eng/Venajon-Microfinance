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
            'favicon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $validated['logo_path'] = 'data:' . $logo->getMimeType() . ';base64,' . base64_encode(file_get_contents($logo->getRealPath()));
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $validated['favicon_path'] = 'data:' . $favicon->getMimeType() . ';base64,' . base64_encode(file_get_contents($favicon->getRealPath()));
        }

        $systemSetting->update($validated);

        return redirect()->route('system-settings.index')->with('success', 'System settings updated successfully.');
    }
}
