<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VisitLog;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $visitLogs = VisitLog::where('void', $request->void)->orderBy('created_at', 'desc')
                ->get();
        } else {
            $visitLogs = VisitLog::where('void', 'false')->orderBy('created_at', 'desc')
                ->get();
        }
        return view('visit-logs.index', compact('visitLogs'));
    }

    public function create()
    {
        $visitors = Visitor::all();
        $users = User::all();
        return view('visit-logs.create', compact('visitors', 'users'));
    }
}
