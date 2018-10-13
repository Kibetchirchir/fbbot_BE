<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\accounts;

class account extends Controller
{
    //
    /**
     * @param Request $request
     * @param $pid
     * @return \Illuminate\Http\JsonResponse
     * check the account
     */
    public function checkregistration(Request $request,$pid){
        $user=DB::table('accounts')
            ->where('pid', $pid)
            ->orderBy('created_at', 'desc')->first();

        if($user == null){
            return response()->json([
                'status'      => '404',
                'message'     => 'saved'
            ]);
        }else{
            return response()->json([
                'status'      => '200',
                'message'     => 'saved'
            ]);
        }
    }
}
