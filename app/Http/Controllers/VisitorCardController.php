<?php

namespace App\Http\Controllers;

use App\Models\VisitLog;
use App\Models\VisitorCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class VisitorCardController extends Controller
{
    public function index(Request $request)
    {
        // $visitorCards = DB::table('visitor_cards')
        //     ->leftJoin('visit_logs', 'visitor_cards.id', '=', 'visit_logs.visitor_card_id')
        //     ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
        //     ->select(
        //         'visitor_cards.*',
        //         'visit_logs.*',
        //         'visitors.name as visitor_name'
        //     )
        //     ->where('visitor_cards.void', 'false')
        //     ->orderBy('visitor_cards.created_at', 'asc')
        //     ->paginate(8);
        // dd($visitorCards);

        if ($request->available === 'available') {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.id', '=', 'visit_logs.visitor_card_id')
                ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->select(
                    'visitor_cards.*',
                    'visit_logs.*',
                    'visitors.name as visitor_name'
                )
                ->where('visitor_cards.status_card', 'available')
                ->orderBy('visitor_cards.created_at', 'asc')
                ->distinct('visitor_cards.id')
                ->paginate(8);
        } elseif ($request->available === 'in-use') {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.id', '=', 'visit_logs.visitor_card_id')
                ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->select(
                    'visitor_cards.*',
                    'visit_logs.*',
                    'visitors.name as visitor_name'
                )
                ->where('visitor_cards.status_card', 'in-use')
                ->orderBy('visitor_cards.created_at', 'asc')
                ->distinct('visitor_cards.id')
                ->paginate(8);
        } else {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.id', '=', 'visit_logs.visitor_card_id')
                ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->select(
                    'visitor_cards.*',
                    'visit_logs.*',
                    'visitors.name as visitor_name'
                )
                ->where('visitor_cards.void', 'false')
                ->orderBy('visitor_cards.created_at', 'asc')
                ->distinct('visitor_cards.id')
                ->paginate(8);
        }
        return view('visitor-card.index', compact('visitorCard'));
    }

    public function create()
    {
        $visitCardId = VisitorCard::latest()->first();
        if ($visitCardId) {
            $visitCardId = $visitCardId->id + 1;
        } else {
            $visitCardId = 1;
        }
        return view('visitor-card.create', compact('visitCardId'));
    }

    public function store(Request $request)
    {
        $visitorCard = VisitorCard::create([
            'rfid' => $request->rfid,
            'visitor_code' => $request->visitor_code,
            'status_card' => 'available',
            'void' => 'false',
        ]);

        Alert::success('Created Successfully!', 'Visitor Card successfully created!');
        return redirect()->intended('visitor-card/index');
    }
}
