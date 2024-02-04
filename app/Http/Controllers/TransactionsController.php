<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function index()
    {
        $credits = Credit::where('user_id', Auth::id())->orderByDesc('id')->paginate(10);
        $creditsSum = Credit::where('user_id', Auth::id())->sum('amount');
        return view('transactions', compact('credits', 'creditsSum'));
    }

    public function addCredit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 0,
            'amount' => $request->get('amount')
        ]);

        return redirect()->back();
    }

    public function withdrawCredit(Request $request)
    {
        if(Credit::where('user_id', Auth::id())->sum('amount') < $request->get('amount'))
        {
            return redirect()->back()->withErrors(['message'=>'Nemate dovoljno kredita za isplatu trazenog iznosa!']);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 1,
            'amount' => -$request->get('amount')
        ]);

        return redirect()->back();
    }

}
