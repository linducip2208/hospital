<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageContentController extends Controller
{
    public function index(): View
    {
        $pageContents = PageContent::orderBy('order')->get();

        $groups = [
            'Branding & Pengaturan Umum' => ['branding', 'footer', 'contact'],
            'Hero & Header'              => ['hero', 'trust'],
            'Konten Utama'               => ['video', 'modules', 'features', 'showcase', 'stats', 'testimonials', 'insights', 'partners', 'cta'],
            'Lainnya'                    => ['about'],
        ];

        return view('cms.index', compact('pageContents', 'groups'));
    }

    public function edit(PageContent $pageContent): View
    {
        return view('cms.edit', compact('pageContent'));
    }

    public function update(Request $request, PageContent $pageContent): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'image_url'   => 'nullable|string|max:500',
            'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_url'  => 'nullable|string|max:500',
            'video_url'   => 'nullable|string|max:500',
            'meta_json'   => 'nullable|string',
            'is_active'   => 'nullable|boolean',
            'order'       => 'nullable|integer|min:0',
            'remove_image'=> 'nullable|boolean',
        ]);

        // Handle file upload — disimpan di storage/app/public/cms/
        if ($request->hasFile('image_file')) {
            // Hapus file lama kalau ada (hanya yang stored lokal)
            if ($pageContent->image_url && str_starts_with($pageContent->image_url, '/storage/cms/')) {
                $oldPath = str_replace('/storage/', '', $pageContent->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('image_file');
            // Ekstensi ditebak dari isi file (bukan dari nama upload) + nama acak
            // agar file SVG/HTML ber-JS tidak bisa lolos sebagai gambar.
            $ext = $file->guessExtension() ?: 'bin';
            $filename = $pageContent->section . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            $path = $file->storeAs('cms', $filename, 'public');
            $validated['image_url'] = Storage::url($path); // jadi /storage/cms/xxx.png
        } elseif ($request->boolean('remove_image')) {
            // User klik tombol hapus
            if ($pageContent->image_url && str_starts_with($pageContent->image_url, '/storage/cms/')) {
                $oldPath = str_replace('/storage/', '', $pageContent->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['image_url'] = null;
        }
        // Hapus field internal yang bukan kolom DB
        unset($validated['image_file'], $validated['remove_image']);

        // Parse meta JSON dari textarea
        if (! empty($validated['meta_json'])) {
            $decoded = json_decode($validated['meta_json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['meta_json' => 'Format JSON tidak valid: ' . json_last_error_msg()]);
            }
            $validated['meta'] = $decoded;
        }
        unset($validated['meta_json']);

        $validated['is_active'] = $request->boolean('is_active');
        $pageContent->update($validated);

        // Cache otomatis di-flush via model boot event
        return redirect()->route('cms.index')->with('success', 'Konten "' . $pageContent->section . '" berhasil diperbarui dan tampil di halaman utama.');
    }

    /**
     * Toggle active/inactive section langsung dari index.
     */
    public function toggle(PageContent $pageContent): RedirectResponse
    {
        $pageContent->update(['is_active' => ! $pageContent->is_active]);
        $status = $pageContent->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Section \"{$pageContent->section}\" berhasil {$status}.");
    }

    /**
     * Reset section ke nilai default dari seeder.
     */
    public function reset(PageContent $pageContent): RedirectResponse
    {
        \Artisan::call('db:seed', ['--class' => 'PageContentSeeder', '--force' => true]);
        PageContent::flushCache();

        return back()->with('success', 'Konten berhasil direset ke default.');
    }
}
