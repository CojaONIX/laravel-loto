<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $rounds = Ticket::select('round')->distinct()->get()->pluck('round');
        return view('admin', compact('rounds'));
    }

    public function ajaxAdminReport(Request $request)
    {
        $round = $request->get('round');
        $ticketsCount = Ticket::where(['round' => $round])->count();
        $ticketsValue = $ticketsCount * 100;
        $quotas = config('loto.quotas');
        return compact('round', 'ticketsCount', 'ticketsValue', 'quotas');
    }
}
