<?php

namespace App\Http\Controllers;

use App\Models\VisitorCard;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VisitorCardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $visitorCard = VisitorCard::where('void', $request->void)->orderBy('created_at', 'desc')
                ->paginate(4);
        } else {
            $visitorCard = VisitorCard::where('void', 'false')->orderBy('created_at', 'desc')
                ->paginate(8);
        }
        return view('visitor-card.index', compact('visitorCard'));
    }

    public function create()
    {
        return view('visitor-card.create');
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
