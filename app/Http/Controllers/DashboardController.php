<?php

namespace App\Http\Controllers;

use App\pastMessage;
use Illuminate\Http\Request;
use App\accounts;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function DashboardData(Request $request){
        $accounts = accounts::where('balance', '>', 0)->count();
        $today = pastMessage::where('created_at','=', Carbon::now('Africa/Nairobi'))->count();;

        return view('home')->with(['accounts' => $accounts,
            'today'  => $today
        ]);

    }
}
