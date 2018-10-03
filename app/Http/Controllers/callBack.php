<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\accounts;

class callBack extends Controller
{
    //
    public function paymentsReceive(Request $request){
        $PhoneNumber = $request->input('PhoneNumber');
        $MpesaReceiptNumber= $request->input('MpesaReceiptNumber');
        $AccountReference =  $request->input('AccountReference');
        $Amount = $request->input('Amount');

        $acc = accounts::where('phoneNo',$PhoneNumber)->first();

        $pid = $acc->pid;

        if($MpesaReceiptNumber == "FAILED"){
            return response()->json([
                'status'      => 'success',
                'message'     => 'no failed',
                'data'        => $pid
            ]);
        }else{
            $balance = $acc->balance;
            $amount = $Amount + $balance;

            $acc->balance = $amount;
            if($acc->save()){
                return response()->json([
                    'status'      => 'increased',
                    'message'     => 'saved',
                    'data'        => $pid
                ]);
            }else
            {
                return response()->json([
                    'status'      => 'failed increase',
                    'message'     => 'saved',
                    'data'        => $pid
                ]);
            }

              }




    }
}
