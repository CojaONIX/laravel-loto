<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function homeKredit()
    {

        $kredits = Credit::all();
        return view('home', compact('kredits'));
    }

    public function uplataKredita(Request $request)
    {
        // validate: amount mora biti pozitivan
        Credit::create([
            'user_id' => Auth::user()->id,
            'type' => 0,
            'amount' => $request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }

    public function isplataSaKredita(Request $request)
    {
        // validate: amount mora biti pozitivan
        Credit::create([
            'user_id' => Auth::user()->id,
            'type' => 1,
            'amount' => -$request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }

    public function uplataTiketa(Request $request)
    {
        // generisi slucajne brojeve u tabeli tikets
        Credit::create([
            'user_id' => Auth::user()->id,
            'type' => 2,
            'amount' => -100 // cena tiketa
        ]);

        return redirect()->route('kredit.home');
    }

    public function isplataDobitka(Request $request)
    {
        // validate: amount mora biti pozitivan
        Credit::create([
            'user_id' => Auth::user()->id,
            'type' => 3,
            'amount' => $request->get('amount')
        ]);

        return redirect()->route('kredit.home');
    }
}
