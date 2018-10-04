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
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pure-reef-27364.herokuapp.com/pay/".$pid."-0-".$Amount."",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                print_r(json_decode($response));
            }
            /*return response()->json([
                'status'      => 'success',
                'message'     => 'no failed',
                'data'        => $pid
            ]);*/
        }else{
            $balance = $acc->balance;
            $amount = $Amount + $balance;

            $acc->balance = $amount;
            if($acc->save()){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pure-reef-27364.herokuapp.com/pay/".$pid."-1-".$Amount."",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    print_r(json_decode($response));
                }

                /*return response()->json([
                    'status'      => 'increased',
                    'message'     => 'saved',
                    'data'        => $pid
                ]);*/
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
