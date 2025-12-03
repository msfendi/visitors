<?php

namespace App\Http\Controllers;

use Alimranahmed\LaraOCR\Facades\OCR;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use thiagoalessio\TesseractOCR\TesseractOCR;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $visitors = Visitor::where('void', $request->void)->orderBy('created_at', 'desc')
                ->get();
        } else {
            $visitors = Visitor::where('void', 'false')->orderBy('created_at', 'desc')
                ->get();
        }
        return view('visitor.index', compact('visitors'));
    }

    public function create()
    {
        $users = User::all();
        return view('visitor.create', compact('users'));
    }

    // public function store(Request $request)
    // {
    //     $visitor = Visitor::create([
    //         'name' => $request->visitor_name,
    //         'phone' => $request->phone,
    //         'instansi' => $request->instansi,
    //         'identity_number' => $request->identity_number,
    //         'number_plate' => $request->number_plate,
    //         'void' => 'false',
    //     ]);

    //     Alert::success('Created Successfully!', 'Visitor successfully created!');
    //     return redirect()->intended('visitor/index');
    // }


    public function checkin(Request $request)
    {
        $visitor = Visitor::create([
            'visitor_id' => $request->visitor_id,
            'nik' => $request->nik,
            'visitor_name' => $request->visitor_name,
            'alamat' => $request->alamat,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota,
            'phone' => $request->phone,
            'instansi' => $request->instansi,
            'number_plate' => $request->number_plate,
            'void' => 'false',
        ]);

        VisitLog::create([
            'visitor_id' => $visitor->visitor_id,
            'visit_date' => $request->visit_date,
            'visit_time' => now(),
            'purpose' => $request->purpose,
            'appointer' => $request->appointer,
            'dept' => $request->dept,
            'security_id' => Auth::user()->id,
            'visitor_card_id' => $request->visitor_card_id,
            'status' => $request->status,
            'void' => 'false',
        ]);

        VisitorCard::where('id', $request->visitor_card_id)->update([
            'status' => 'in-use',
        ]);

        Alert::success('Check-in Successfully!', 'Visitor successfully checked-in!');
        return redirect()->intended('visitor/index');
    }

    public function checkout(Request $request)
    {
        $exploding = explode('_', $request->visitor_code);
        $visitor_code = $exploding[0];
        $visitor_number = $exploding[1];

        $visitorCard = VisitorCard::where('visitor_code', '=', $visitor_code)->get();

        if (count($visitorCard) > 0) {
            $visitLog = VisitLog::where('visitor_card_id', '=', $visitorCard[0]->visitor_number)->first();
            if ($visitLog) {
                $visitLog->update([
                    'leave_time' => now()
                ]);

                $visitorCard[0]->update([
                    'status_card' => 'available',
                ]);

                $visitLog->update([
                    'visitor_card_id' => '',
                ]);
                return response()->json(['success' => true, 'message' => 'Leave time updated']);
            } else {
                return response()->json(['success' => false, 'message' => 'Visitor code not found'], 404);
            }
        } else {
            // Alert::error('Error!', 'Visitor Code Id' . $visitor_code . 'not exist');
            // return redirect()->back();
            return response()->json(['success' => false, 'message' => 'Visitor code not found'], 404);
        }

    }

    public function revision($id)
    {
        $visitor = Visitor::find($id);
        return view('visitor.revision', compact('visitor'));
    }

    public function update(Request $request)
    {
        $visitor = Visitor::find($request->visitor_id);
        $visitor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'instansi' => $request->instansi,
            'identity_number' => $request->identity_number,
            'number_plate' => $request->number_plate,
        ]);

        Alert::success('Updated Successfully!', 'Visitor successfully updated!');
        return redirect()->intended('visitor/index');
    }

    public function void(Request $request)
    {
        $visitor = Visitor::find($request->visitor_id);
        $visitor->update([
            'void' => 'true',
        ]);
        Alert::success('Void Successfully!', 'Document successfully void!');
        return redirect()->intended('visitor/index');
    }

    public function restore(Request $request)
    {
        $visitor = Visitor::find($request->visitor_id);
        $visitor->update([
            'void' => 'false',
        ]);
        Alert::success('Void Successfully!', 'Document successfully void!');
        return redirect()->intended('visitor/index');
    }
}
