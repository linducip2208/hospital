<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PublicHomeController extends Controller
{
    public function index(): Response
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $stats = Cache::remember('welcome.stats', now()->addHour(), fn () => [
            'patients' => Patient::count(),
            'doctors' => Doctor::where('status', 'active')->count(),
            'polys' => Polyclinic::where('is_active', true)->count(),
        ]);

        return view('welcome', compact('stats'));
    }
}
