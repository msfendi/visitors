<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VisitLog;
use App\Models\Visitor;
use App\Models\VisitorCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class VisitLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {

            $visitLogs = DB::table('visit_logs')->join('visitor_cards', 'visit_logs.visitor_card_id', '=', 'visitor_cards.id')
                ->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->join('users as appointer', 'visit_logs.appointer', '=', 'appointer.id')
                ->join('users as security', 'visit_logs.security_id', '=', 'security.id')
                ->select('visit_logs.*', 'visitor_cards.*', 'visitors.name as visitor_name', 'appointer.name as appointer_name', 'security.name as security_name')
                ->where('visit_logs.void', $request->void)->orderBy('visit_logs.created_at', 'desc')
                ->get();
        } else {
            $visitLogs = DB::table('visit_logs')->join('visitor_cards', 'visit_logs.visitor_card_id', '=', 'visitor_cards.id')
                ->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->join('users as appointer', 'visit_logs.appointer', '=', 'appointer.id')
                ->join('users as security', 'visit_logs.security_id', '=', 'security.id')
                ->select('visit_logs.*', 'visitor_cards.*', 'visitors.name as visitor_name', 'appointer.name as appointer_name', 'security.name as security_name')
                ->where('visit_logs.void', 'false')->orderBy('visit_logs.created_at', 'desc')
                ->get();
        }
        return view('visit-logs.indexActive', compact('visitLogs'));
    }

    public function indexAll(Request $request)
    {
        if ($request->void) {

            $visitLogs = DB::table('visit_logs')->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->join('users as appointer', 'visit_logs.appointer', '=', 'appointer.id')
                ->join('users as security', 'visit_logs.security_id', '=', 'security.id')
                ->select('visit_logs.*', 'visitors.name as visitor_name', 'appointer.name as appointer_name', 'security.name as security_name')
                ->where('visit_logs.void', $request->void)->orderBy('visit_logs.created_at', 'desc')
                ->get();
        } else {
            $visitLogs = DB::table('visit_logs')->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->join('users as appointer', 'visit_logs.appointer', '=', 'appointer.id')
                ->join('users as security', 'visit_logs.security_id', '=', 'security.id')
                ->select('visit_logs.*', 'visitors.name as visitor_name', 'appointer.name as appointer_name', 'security.name as security_name')
                ->where('visit_logs.void', 'false')->orderBy('visit_logs.created_at', 'desc')
                ->get();
        }
        return view('visit-logs.index', compact('visitLogs'));
    }

    public function create($visitor_id)
    {
        $visitors = Visitor::all();
        $users = User::all();
        $securities = User::where('dept', 'security')->get();
        $visitor_card_id = VisitorCard::findOrFail($visitor_id);

        return view('visit-logs.create', compact('visitors', 'users', 'securities', 'visitor_card_id'));
    }

    public function store(Request $request)
    {
        $visitLog = VisitLog::create([
            'visitor_id' => $request->visitor_name_id,
            'visit_date' => $request->visit_date,
            'visit_time' => $request->visit_time,
            'leave_time' => $request->leave_time,
            'purpose' => $request->purpose,
            'appointer' => $request->appointer_name_id,
            'dept' => $request->dept,
            'security_id' => $request->security_name_id,
            'visitor_card_id' => $request->visitor_card_id,
            'void' => 'false',
        ]);

        $visitCard = VisitorCard::find($request->visitor_card_id);
        $visitCard->update([
            'status_card' => 'in-use',
        ]);

        Alert::success('Created Successfully!', 'Visit Log successfully created!');
        return redirect()->intended('visit-logs/index');
    }

    public function fetchDept($id_user)
    {
        $users = User::findOrFail($id_user);
        return response()->json($users);
    }

    public function visit_time(Request $request)
    {
        // checking rfid number
        $visitorCard = VisitorCard::where('rfid', $request->rfid_visit)->first();
        if (!$visitorCard) {
            Alert::error('Error!', 'RFID not found!');
            return redirect()->back();
        } else {
            $visitLog = VisitLog::where('visitor_card_id', $visitorCard->id)->where('void', 'false')->first();
            $visitLog->update([
                'visit_time' => now()
            ]);
        }

        return response()->json($visitorCard);
    }

    public function leave_time(Request $request)
    {
        // checking rfid number
        $visitorCard = VisitorCard::where('rfid', $request->rfid_leave)->first();
        if (!$visitorCard) {
            Alert::error('Error!', 'RFID not found!');
            return redirect()->back();
        } else {
            $visitLog = VisitLog::where('visitor_card_id', $visitorCard->id)->where('void', 'false')->first();
            $visitLog->update([
                'leave_time' => now()
            ]);

            $visitorCard->update([
                'status_card' => 'available',
            ]);

            $visitLog->update([
                'visitor_card_id' => '',
            ]);
        }

        return response()->json($visitorCard);
    }

    public function showvisitor(Request $request)
    {
        if ($request->ajax()) {
            $visitLog = VisitLog::select('visit_logs.*', 'visitors.name as visitor_name', 'appointer.name as appointer_name', 'security.name as security_name',)
                ->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->join('users as appointer', 'visit_logs.appointer', '=', 'appointer.id')
                ->join('users as security', 'visit_logs.security_id', '=', 'security.id')
                ->orderBy('visit_logs.created_at', 'desc');
            return DataTables::of($visitLog)
                ->addIndexColumn()
                ->addColumn('created_at_formated', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->created_at));
                })
                ->rawColumns(['created_at_formated'])
                ->filter(function ($instance) use ($request) {
                    if ($request->filled('fromdate') && $request->filled('todate')) {
                        $instance
                            ->where('visit_date', '>=', $request->get('fromdate'))
                            ->where('visit_date', '<=', $request->get('todate'));
                    }
                })->make(true);
        };
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $visitor = DB::table('visitor_cards')->join('visit_logs', 'visitor_cards.id', '=', 'visit_logs.visitor_card_id')->join('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->where('visitor_code', 'like', '%' . $request->search . '%')
                ->orWhere('visitors.name', 'like', '%' . $request->search . '%')
                ->get();

            return response($visitor);
        }
    }
}
