<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Round;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TestController extends Controller
{
    public function showTest(Request $request)
    {
        return view('test', ['buttons' => [
            'users',
            'user by id',
            'logged user',
            'logged user with all',
            'logged user tickets',
            '2 latest',
            'latest report'

        ]]);
    }

    public function ajaxGetTestData(Request $request)
    {
        $item = $request->item;
        switch($request->action) {

            case('users'):
                return User::all();

            case('user by id'):
                try {
                    return User::findOrFail($item);
                } catch (Throwable $e) {
                    return [
                        'code' => 404,
                        'message' => 'User Not found - id=' . $item,
                        'Try with' => User::all()->pluck('id')
                    ];
                }

            case('logged user'):
                return Auth::user();

            case('logged user with all'):
                return User::with('credits')->with('tickets')->find(Auth::id());

            case('logged user tickets'):
                return User::find(Auth::id())->tickets()->where(['round' => 99])->get();

            case('2 latest'):
                return Round::latest('id')->take(2)->get()[1];

            case('latest report'):
                return Round::latest('id')->first()->report;


            default:
                return [
                    'msg' => 'Bad action'
                ];
        }

    }
}
