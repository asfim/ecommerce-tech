<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-activitylogs,admin', only: ['index']),
        ];
    }

    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(25);

        return view('backend.activity-logs.index', compact('logs'));
    }
}
