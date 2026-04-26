<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        $logs = Activity::with('causer')
            ->when($search, function($query) use ($search) {
                $query->where('description', 'LIKE', "%{$search}%")
                      ->orWhere('log_name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('master.activity-logs.index', compact('logs'));
    }
}
