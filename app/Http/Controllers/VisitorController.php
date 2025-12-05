<?php

namespace App\Http\Controllers;

use Alimranahmed\LaraOCR\Facades\OCR;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorCard;
use App\Models\VisitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        // if ($request->void) {
        //     $visitors = Visitor::where('void', $request->void)->orderBy('created_at', 'desc')
        //         ->get();
        // } else {
        //     $visitors = Visitor::where('void', 'false')->orderBy('created_at', 'desc')
        //         ->get();
        // }

        if ($request->void) {
            $visitors = Visitor::join('visit_logs', 'visitors.id', '=', 'visit_logs.visitor_id')->where('visit_logs.void', $request->void)->orderBy('visit_logs.created_at', 'desc')
                ->get();
        } else {
            $visitors = Visitor::join('visit_logs', 'visitors.id', '=', 'visit_logs.visitor_id')->where('visit_logs.void', 'false')->orderBy('visit_logs.created_at', 'desc')
                ->get();
        }
        return view('visitor.index', compact('visitors'));
    }

    public function create()
    {
        $users = User::all();
        $employees = DB::connection('cii')->table('BIODATA')->select('BIODATA.*')->get();
        $securities = DB::connection('cii')->table('BIODATA')->select('BIODATA.*')->where('ID_DEPT', '304')->get();
        return view('visitor.create', compact('users', 'employees', 'securities'));
    }

    public function leave()
    {
        return view('visitor.leave');
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
        // dd($request->all());
        $exploding = explode('_', $request->visitor_code);
        $visitor_code = $exploding[0];
        $visitor_number = $exploding[1];

        $visitorCard = VisitorCard::where('visitor_code', $visitor_code)->get();

        if (count($visitorCard) == 0) {
            Alert::error('Error!', 'Visitor Code Id' . $visitor_code . 'not exist');
            return redirect()->back();
        }

        if ($visitorCard[0]->status_card == 'available') {
            if (count($visitorCard) > 0) {
                $visitorCard[0]->update([
                    'status_card' => 'in-use',
                ]);
            }

            $visitor = Visitor::create([
                'id' => $request->visitor_id,
                'nik' => $request->nik,
                'name' => $request->visitor_name,
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
                'visitor_id' => $visitor->id,
                'visit_date' => date('Y-m-d'),
                'visit_time' => now(),
                'purpose' => $request->purpose,
                'appointer' => $request->appointer_id,
                'dept' => $request->dept,
                'security_id' => $request->security_id,
                'visitor_card_id' => $visitorCard[0]->id,
                'void' => 'false',
            ]);

            Alert::success('Check-in Successfully!', 'Visitor successfully checked-in!');
            return redirect()->intended('visitor/index');
        } else {
            Alert::error('Error!', 'Visitor Code Id' . $visitor_code . 'still in use');
            return redirect()->back();
        }
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

    public function fetchEmployee($npk)
    {
        try {
            $employee = DB::connection('cii') ->table('BIODATA') ->select('BAG') ->where('NPK', $npk) ->first();
            
            if ($employee) {
                return response()->json([ 'success' => true, 'BAG' => $employee->BAG ], 200);
            } else {
                return response()->json([ 'success' => false, 'message' => 'Employee not found' ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([ 'success' => false, 'message' => 'Database error: ' . $th->getMessage() ], 500);
        }
    }

    public function revision($id)
    {
        $visitor = Visitor::find($id);
        return view('visitor.revision', compact('visitor'));
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
