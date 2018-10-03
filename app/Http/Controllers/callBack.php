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

        if($MpesaReceiptNumber == "FAILED"){
            return response()->json([
                'status'      => 'success',
                'message'     => 'saved',
                'data'        => 'failed'
            ]);
        }else{
            accounts::find($PhoneNumber)->increment('balance', $Amount);

            return response()->json([
                'status'      => 'success',
                'message'     => 'saved',
                'data'        => 'success'
            ]);
        }




    }
}
