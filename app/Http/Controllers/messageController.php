<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pastMessage;
use DB;
use App\accounts;


class messageController extends Controller
{
    //
    /**
     * the last message
     */

    public function LastMessage(Request $request,$pid,$mesage,$value){

//        $this->validate($request,[
//            'Pid' => "required"
//        ]);

        $message = new pastMessage ;

       // $message->Pid=$request->input('Pid');
        $message->Pid=$pid;
       // $message->message=$request->input('message');
        $message->message=$mesage;
        $message->value=$request->input('value');
        $message->value=$value;
        $message->save();

        return response()->json([
            'status'      => 'success',
            'message'     => 'saved',
        ]);
    }

    public function retrieveLastMessage(Request $request ,$pid){


        //$Pid=$request->input('Pid');
        $Pid=$pid;
        $message=DB::table('past_messages')
            ->where('Pid', $Pid)
            ->orderBy('id', 'desc')->first();

        if($message == null){
            return response()->json([
                'status'      => 'success',
                'message'     => 'saved',
                'data'        => 404
            ]);
        }else {
            return response()->json([
                'status' => 'success',
                'message' => 'saved',
                'data' => $message->message
            ]);
        }
    }

    public function push(Request $request, $pid,$mesage,$amount){

        $message = new pastMessage ;

        // $message->Pid=$request->input('Pid');
        $message->Pid=$pid;
        // $message->message=$request->input('message');
        $message->message=$mesage;
        $message->value=$amount;
        $message->save();

        $Pid=$pid;
        $person=DB::table('past_messages')
            ->where('Pid', $Pid)
            ->where('message', 'phone')
            ->orderBy('created_at', 'desc')->first();

        echo $Pid;
        $number=$person->value;
       // $amount2=$amount;
        $amount2 = $amount;


        /**
         * the curl to make the push
         */

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payme.nouveta.co.ke/api/index.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionType\"\r\n\r\nCustomerPayBillOnline\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PayBillNumber\"\r\n\r\n175555\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"Amount\"\r\n\r\n$amount2\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PhoneNumber\"\r\n\r\n$number\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"AccountReference\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionDesc\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer UVZoQk9rcFhDcWxlR1RPSnJBa1RaRkZQSjdZdlVSRzdZTThHVldLUU1jZz06MTIzNDU6UTIydkozVll4RzFMWUV2MkViSDl5UVN3NFFRanZrNVJoVThQM0pXTXRIRT06MTk3LjI0OC4xNDkuNjI6MDQvMDAvMTcgMTIwMA==",
                "Cache-Control: no-cache",
                "Postman-Token: 496bc8ee-896e-302d-31c4-54d106910842",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return response()->json([
            'status'      => 'success',
            'message'     => 'saved',
            'data'        => $response,
            'data2'   => $err
        ]);
        }

        /**
         * registration
         */
        public function register(Request $request,$Pid,$phone){
//            $phone=DB::table('past_messages')
//                ->where('Pid', $Pid)
//                ->where('message', 'phone')
//                ->orderBy('created_at', 'desc')->first();
            $ID=DB::table('past_messages')
                ->where('Pid', $Pid)
                ->where('message', 'reg2')
                ->orderBy('id', 'desc')->first();
            $Otp=mt_rand(1000, 9999);
            /*$email=DB::table('past_messages')
                ->where('Pid', $Pid)
                ->where('message', 'email')
                ->orderBy('created_at', 'desc')->first();*/
            $phone1=$this->formatPhoneNumber($phone);
            $phone2=$phone1;

            $account=new accounts;

            $account->pid=$Pid;
            $account->idNo=$ID->value;
            $account->phoneNO=$phone2;
            $account->currentOtp=$Otp;
            $account->email='chirchir@nouveta.tech';

            $account->save();

            $text = "Thank you for choosing NBK your OTP is  " . $Otp .".Please enter on the messenger to complete the account setup.  ";

            $this->sendsms($phone2,$text);

            return response()->json([
                'status'      => 'success',
                'message'     => 'saved',
                'data'        => $phone2,

            ]);

        }

    public function link(Request $request,$Pid,$phone){
//            $phone=DB::table('past_messages')
//                ->where('Pid', $Pid)
//                ->where('message', 'phone')
//                ->orderBy('created_at', 'desc')->first();
        $ID=DB::table('past_messages')
            ->where('Pid', $Pid)
            ->where('message', 'ID')
            ->orderBy('id', 'desc')->first();
        $Otp=mt_rand(10000, 99999);
        /*$email=DB::table('past_messages')
            ->where('Pid', $Pid)
            ->where('message', 'email')
            ->orderBy('created_at', 'desc')->first();*/
        //$phone1=substr($phone, 2);
        $phone2=$this->formatPhoneNumber($phone);

        $account=new accounts;

        $account->pid=$Pid;
        $account->idNo=$ID->value;
        $account->phoneNO=$phone2;
        $account->currentOtp=$Otp;
        $account->email='chirchir@nouveta.tech';

        $account->save();

        $text = "Thank you for choosing NBK your OTP is ". $Otp ." .This is the OTP to link your account";

        $this->sendsms($phone2,$text);

        return response()->json([
            'status'      => 'success',
            'message'     => 'saved',
            'data'        => $phone2,
            'data2'   => $text
        ]);

    }

        public function sendsms($phone,$text){
            $username = 'info@nouveta.tech';
            $password = 'Tech.N0uv3t4.2018';


            $header = "Basic " . base64_encode($username . ":" . $password);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{ \"from\":\"NOUVETA\", \"to\":\"$phone\", \"text\":\"$text\" }",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: $header",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

        }

        public function confirmOtp(Request $request,$pid,$otp){
            $user=DB::table('accounts')
                ->where('pid', $pid)
                ->where('currentOtp', $otp)
                ->orderBy('created_at', 'desc')->first();

            if($user == null){
                return response()->json([
                    'status'      => '404',
                    'message'     => 'saved'
                ]);
            }
            else
            {
               $phone=$user->phoneNo;
               $account=$user->pid;
//               $text="Your account number is  ". $account ."  it will be active once you top up";
//               $this->sendsms($phone,$text);
                return response()->json([
                    'status'      => '200',
                    'message'     => 'saved'
                ]);
            }
        }

        public  function balance(Request $request,$pid){
            $user=DB::table('accounts')
                ->where('pid', $pid)
                ->orderBy('created_at', 'desc')->first();

            $phone=$this->formatPhoneNumber($user->phoneNo);
            $balance=$user->balance;
            $message= "Your account balance is ".$balance;

            $this->sendsms($phone,$message);

            return response()->json([
                'status'      => '200',
                'message'     => 'saved',
                'phone'       => $phone,
                'balance'     => $balance
            ]);
        }
    public  function ministatement(Request $request,$pid){
        $user=DB::table('accounts')
            ->where('pid', $pid)
            ->orderBy('created_at', 'desc')->first();

        $phone=$user->phoneNo;
        $message= "Here are the last 5 transactions for account 010****1200,KES 101,203 chq deposit,KES 300 airtime bought,KES 10,000 rent paid,KES 23,000 loan repaid,KES 32,000 cr card debit";

//        $this->sendsms($phone,$message);

        return response()->json([
            'status'      => '200',
            'message'     => 'saved',
            'data'        =>  $phone
        ]);
    }
    public function pushAcc(Request $request,$pid){
        $user=DB::table('accounts')
            ->where('pid', $pid)
            ->orderBy('created_at', 'desc')->first();

        $phone=$user->phoneNo;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payme.nouveta.co.ke/api/index.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionType\"\r\n\r\nCustomerPayBillOnline\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PayBillNumber\"\r\n\r\n175555\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"Amount\"\r\n\r\n1\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PhoneNumber\"\r\n\r\n$phone\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"AccountReference\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionDesc\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer UVZoQk9rcFhDcWxlR1RPSnJBa1RaRkZQSjdZdlVSRzdZTThHVldLUU1jZz06MTIzNDU6UTIydkozVll4RzFMWUV2MkViSDl5UVN3NFFRanZrNVJoVThQM0pXTXRIRT06MTk3LjI0OC4xNDkuNjI6MDQvMDAvMTcgMTIwMA==",
                "Cache-Control: no-cache",
                "Postman-Token: 496bc8ee-896e-302d-31c4-54d106910842",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        echo $response;
        echo $err;
    }
    public function push2(Request $request,$pid,$amount){
        $user=DB::table('accounts')
            ->where('pid', $pid)
            ->orderBy('created_at', 'desc')->first();

        $phone=$user->phoneNo;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payme.nouveta.co.ke/api/index.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionType\"\r\n\r\nCustomerPayBillOnline\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PayBillNumber\"\r\n\r\n175555\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"Amount\"\r\n\r\n$amount\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PhoneNumber\"\r\n\r\n$phone\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"AccountReference\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionDesc\"\r\n\r\nBot\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer UVZoQk9rcFhDcWxlR1RPSnJBa1RaRkZQSjdZdlVSRzdZTThHVldLUU1jZz06MTIzNDU6UTIydkozVll4RzFMWUV2MkViSDl5UVN3NFFRanZrNVJoVThQM0pXTXRIRT06MTk3LjI0OC4xNDkuNjI6MDQvMDAvMTcgMTIwMA==",
                "Cache-Control: no-cache",
                "Postman-Token: 496bc8ee-896e-302d-31c4-54d106910842",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        echo $response;
    }
    public function formatPhoneNumber($phoneNumber) {
        $phoneNumber = preg_replace('/[^\dxX]/', '', $phoneNumber);
        $phoneNumber = preg_replace('/^0/','254',$phoneNumber);

        $phoneNumber = $phone = preg_replace('/\D+/', '', $phoneNumber);

        return $phoneNumber;
    }
    public function resendOTP(Request $request, $pid){
        $user=DB::table('accounts')
            ->where('pid', $pid)
            ->orderBy('created_at', 'desc')->first();

        $otp = $user->currentOtp;
        $phone = $user->phoneNo;
        $text = "Thank you for choosing NBK your OTP is ". $otp ." .This is the OTP to link your account";

        $this->sendsms($phone,$text);
        return response()->json([
            'status'      => '200',
            'message'     => 'saved',
            'phone'       => $phone
        ]);
    }
}
