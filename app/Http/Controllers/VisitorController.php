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

    public function store(Request $request)
    {
        $visitor = Visitor::create([
            'name' => $request->visitor_name,
            'phone' => $request->phone,
            'instansi' => $request->instansi,
            'identity_number' => $request->identity_number,
            'number_plate' => $request->number_plate,
            'void' => 'false',
        ]);

        Alert::success('Created Successfully!', 'Visitor successfully created!');
        return redirect()->intended('visitor/index');

        // $request->validate([
        //     'image' => 'required|mimes:png,jpg,jpeg'
        // ]);

        // $image = $request->image;

        // $ocr = new TesseractOCR($image); //Pakai TesseractOCR
        // $text = $ocr->lang('eng')->run();

        // // $ocrText = OCR::scan($image); //Pakai LaraOCR

        // dd($text);
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
