<?php

namespace App\Http\Controllers;

use App\Models\VisitLog;
use App\Models\VisitorCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorCardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->available === 'available') {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.visitor_number', '=', 'visit_logs.visitor_card_id')
                ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->select(
                    'visitor_cards.*',
                    'visit_logs.*',
                    'visitors.name as visitor_name'
                )
                ->where('visitor_cards.status_card', 'available')
                ->where('visitor_cards.void', 'false')
                ->orderBy('visitor_cards.created_at', 'asc')
                ->distinct('visitor_cards.id')
                ->paginate(8);
        } elseif ($request->available === 'in-use') {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.visitor_number', '=', 'visit_logs.visitor_card_id')
                ->leftJoin('visitors', 'visit_logs.visitor_id', '=', 'visitors.id')
                ->select(
                    'visitor_cards.*',
                    'visit_logs.*',
                    'visitors.name as visitor_name'
                )
                ->where('visitor_cards.status_card', 'in-use')
                ->where('visitor_cards.void', 'false')
                ->orderBy('visitor_cards.created_at', 'asc')
                ->distinct('visitor_cards.id')
                ->paginate(8);
        } else {
            $visitorCard = DB::table('visitor_cards')
                ->leftJoin('visit_logs', 'visitor_cards.visitor_number', '=', 'visit_logs.visitor_card_id')
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
        return view('visitor-card.indexQRScan', compact('visitorCard'));
    }

    public function indexTable(Request $request)
    {
        if ($request->void) {
            $visitorCard = VisitorCard::where('void', $request->void)->orderBy('id', 'asc')->get();
        } else {
            $visitorCard = VisitorCard::where('void', 'false')->orderBy('id', 'asc')->get();
        }
        return view('visitor-card.indexTable', compact('visitorCard'));
    }

    public function create()
    {
        $visitCard = VisitorCard::orderBy('id', 'desc')->first();
        if ($visitCard) {
            $visitCardId = $visitCard->visitor_number + 1;
        } else {
            $visitCardId = 1;
        }

        $prefix = 'VST';
        $defaultNumber = 1;
        if (isset($visitCard) && preg_match('/^VST(\d{5})$/', $visitCard->visitor_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = $defaultNumber;
        }
        $newVisitorCode = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('visitor-card.create', compact('visitCardId', 'newVisitorCode'));
    }

    public function store(Request $request)
    {
        $visitorCard = VisitorCard::create([
            // 'rfid' => $request->rfid,
            'visitor_code' => $request->visitor_code,
            'visitor_number' => $request->visitor_number,
            'status_card' => 'available',
            'void' => 'false',
        ]);

        Alert::success('Created Successfully!', 'Visitor Card successfully created!');
        return redirect()->intended('visitor-card/index');
    }

    public function generateqr($id)
    {
        $visitorCard = VisitorCard::findOrFail($id);

        $path = storage_path('public/qr/single/');
        $qr_data = $visitorCard->rfid . "_" . "Visitor" . $visitorCard->visitor_code;
        $qr = QrCode::format('png')->generate($qr_data);
        $qrImageName = $visitorCard->rfid . "_" . $visitorCard->visitor_code . '.png';

        Storage::put('public/qr/single/' . $qrImageName, $qr);

        Alert::success('Created Successfully!', ' QRCode Visitor Card successfully created!');
        return redirect()->intended('visitor-card/index');
    }

    public function batchQR()
    {
        $visitorCards = VisitorCard::all();

        foreach ($visitorCards as $visitorCard) {
            $path = storage_path('public/qr/batch/');
            $qr_data = $visitorCard->visitor_code . "_" . "Visitor" . $visitorCard->visitor_number;
            $qr = QrCode::format('png')->generate($qr_data);
            $qrImageName = $qr_data . '.png';

            Storage::put('public/qr/batch/' . $qrImageName, $qr);

            // Save the QR code path to the visitor card
            $visitorCard->qr_name = $qr_data;
            $visitorCard->qr_path = 'storage/qr/batch/' . $qrImageName;
            $visitorCard->save();
        }

        Alert::success('Created Successfully!', 'Batch QRCode Visitor Card successfully created!');
        return redirect()->intended('visitor-card/index');
    }
}
