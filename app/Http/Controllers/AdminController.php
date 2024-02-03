<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function winnings(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Credit::create([
            'user_id' => Auth::id(),
            'type' => 3,
            'amount' => $request->get('amount')
        ]);

        return redirect()->route('transactions.view');
    }
}
