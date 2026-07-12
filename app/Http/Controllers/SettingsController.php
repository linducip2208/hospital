<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SatuSehatClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ($request->input('settings', []) as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function satusehat(): View
    {
        $settings = Setting::where('group', 'satu_sehat')->get()->pluck('value', 'key')->toArray();

        foreach (['satusehat_client_id', 'satusehat_client_secret', 'satusehat_webhook_secret'] as $key) {
            if (! empty($settings[$key])) {
                try {
                    $settings[$key] = Crypt::decryptString($settings[$key]);
                } catch (\Illuminate\Contracts\Encryption\DecryptException) {
                    $settings[$key] = '';
                }
            }
        }

        return view('settings.satusehat', compact('settings'));
    }

    public function satusehatUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'satusehat_base_url' => 'nullable|url',
            'satusehat_client_id' => 'nullable|string',
            'satusehat_client_secret' => 'nullable|string',
            'satusehat_organization_id' => 'nullable|string',
            'satusehat_is_enabled' => 'nullable|boolean',
            'satusehat_webhook_url' => 'nullable|url',
            'satusehat_webhook_secret' => 'nullable|string',
            'satusehat_webhook_enabled' => 'nullable|boolean',
        ]);

        $encryptedKeys = [
            'satusehat_client_id',
            'satusehat_client_secret',
            'satusehat_webhook_secret',
        ];

        foreach ($validated as $key => $value) {
            if ($value === null || $value === '') {
                if (in_array($key, $encryptedKeys)) {
                    continue;
                }
            }

            $encrypted = $value;
            if (in_array($key, $encryptedKeys) && $value !== null && $value !== '') {
                $encrypted = Crypt::encryptString($value);
            }

            $type = 'text';
            if (in_array($key, ['satusehat_is_enabled', 'satusehat_webhook_enabled'])) {
                $type = 'boolean';
            }

            Setting::updateOrCreate(['key' => $key], [
                'value' => $encrypted,
                'group' => 'satu_sehat',
                'type' => $type,
                'label' => ucwords(str_replace('_', ' ', substr($key, 10))),
            ]);
        }
        return back()->with('success', 'Konfigurasi Satu Sehat berhasil disimpan.');
    }

    public function satusehatTest(): RedirectResponse
    {
        $client = new SatuSehatClient;
        $result = $client->testConnection();

        if ($result['ok']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['error']);
    }

    public function satusehatWebhookTest(): RedirectResponse
    {
        $client = new SatuSehatClient;
        $result = $client->testWebhook();

        if ($result['ok']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['error']);
    }

    public function bpjs(): View
    {
        $settings = Setting::where('group', 'bpjs')->get()->pluck('value', 'key');
        return view('settings.bpjs', compact('settings'));
    }

    public function bpjsUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bpjs_base_url' => 'nullable|url',
            'bpjs_consumer_id' => 'nullable|string',
            'bpjs_consumer_secret' => 'nullable|string',
            'bpjs_user_key' => 'nullable|string',
            'bpjs_is_enabled' => 'nullable|boolean',
        ]);
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value, 'group' => 'bpjs',
                'type' => $key === 'bpjs_is_enabled' ? 'boolean' : 'text',
                'label' => ucwords(str_replace('_', ' ', substr($key, 5))),
            ]);
        }
        return back()->with('success', 'Konfigurasi BPJS berhasil disimpan.');
    }

    public function branding(): View
    {
        $settings = Setting::where('group', 'branding')->get()->pluck('value', 'key');
        return view('settings.branding', compact('settings'));
    }

    public function brandingUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branding_app_name' => 'nullable|string|max:100',
            'branding_logo_url' => 'nullable|url|max:500',
            'branding_favicon_url' => 'nullable|url|max:500',
            'branding_footer_text' => 'nullable|string|max:255',
            'branding_hero_title' => 'nullable|string|max:255',
            'branding_hero_subtitle' => 'nullable|string|max:500',
            'branding_logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'branding_favicon_file' => 'nullable|file|mimes:ico,png,svg|max:1024',
        ]);

        if ($request->hasFile('branding_logo_file')) {
            $path = $request->file('branding_logo_file')->store('branding', 'public');
            $validated['branding_logo_url'] = asset('storage/' . $path);
        }
        if ($request->hasFile('branding_favicon_file')) {
            $path = $request->file('branding_favicon_file')->store('branding', 'public');
            $validated['branding_favicon_url'] = asset('storage/' . $path);
        }

        unset($validated['branding_logo_file'], $validated['branding_favicon_file']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value, 'group' => 'branding',
                'type' => 'text',
                'label' => ucwords(str_replace('_', ' ', substr($key, 9))),
            ]);
        }
        return back()->with('success', 'Pengaturan branding berhasil disimpan.');
    }
}
