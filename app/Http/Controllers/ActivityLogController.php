<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        $categories = ['klinis', 'farmasi', 'keuangan', 'sdm', 'operasional', 'sistem', 'umum'];
        $events = ['created', 'updated', 'deleted', 'login', 'logout'];

        return view('activity-logs.index', compact('logs', 'categories', 'events'));
    }
}
