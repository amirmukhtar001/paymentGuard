<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{

    public function settings()
    {
        $settings = Setting::all();
        return view('settings.settings.settings', [
            'title' => 'Application Settings',
            'settings' => $settings,
        ]);
    }


    public function save(Request $request)
    {
        $allKeys = [
            'enable_email_forgot',
            'enable_sms_forgot',
            'enable_login_otp',
            'enable_farmer_register_sms',
            // add all other setting keys here...
        ];

        foreach ($allKeys as $key) {
            $value = $request->has($key) ? 1 : 0; // checkbox logic
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Save other fields (like text inputs)
        foreach ($request->except('_token','media') as $key => $value) {
            if (!in_array($key, $allKeys)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        // Handle site logo upload
        $siteLogo = Setting::firstOrCreate(['key' => 'site_logo']);
        $siteLogo->addAllMediaFromTokens();
        if ($media = $siteLogo->getFirstMedia('site_logo')) {
            $siteLogo->value = $media->getFullUrl();
            $siteLogo->save();
        }

        $footerLogo = Setting::firstOrCreate(['key' => 'site_logo_bottom']);
        $footerLogo->addAllMediaFromTokens();
        if ($media = $footerLogo->getFirstMedia('site_logo_bottom')) {
            $footerLogo->value = $media->getFullUrl();
            $footerLogo->save();
        }

        return back()->with('success', 'Settings updated!');
    }
}
