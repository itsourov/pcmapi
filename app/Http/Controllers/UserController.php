<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class UserController extends Controller
{
    // Create New User
    public function register(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6',
            'mobile' => 'string'
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        $user = User::create($formFields);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }

    // Create New User
    public function login(Request $request)
    {
        $formFields = $request->validate([

            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {

            $user = User::find(auth()->user()->id);
            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];
            return response($response, 200);
        }

        return response(['message' => "bad creds"], 401);
    }




    public function logout(Request $request)
    {

        return $request->user()->currentAccessToken()->delete();
    }
    public function logoutAll(Request $request)
    {

        return $request->user()->tokens()->delete();
    }


    public function delete(Request $request)
    {



        $user = User::find(auth()->user()->id);
        return $user->delete();
    }

    public function requestPassword(Request $request)
    {
        $formFields = $request->validate([

            'email' => ['required', 'email'],

        ]);
        $email = $request->email;
        if (User::where('email', '=', $email)->exists()) {

            $otp = random_int(10000, 99999);
            $user = User::where('email', '=', $request->email)->update(['otp' => bcrypt($otp), 'otp_expire' => now()->addMinutes(5)]);

            $details = [
                'name' => User::where('email', '=', $request->email)->value('name'),
                'otp' => $otp,
                'subject' => "Verify OTP to reset Password"
            ];

            if ($user &&  Mail::to('sourovbuzz@gmail.com')->send(new \App\Mail\OtpMail($details))) {

                return response(["status" => 200, "message" => "OTP sent successfully"]);
            } else {
                return response(['message' => 'Unable to send otp'], 401);
            }
        } else {
            $obj = new stdClass;
            $obj->message = "Email is not registered";
            $obj->errors = array(
                'email' => [
                    'Email is not registered'
                ]
            );


            return response()->json($obj, 422);
        }
    }

    public function isValidOtp()
    {
        $databaseOtpHashed =  User::where('email', '=', request()->email)->value('otp');
        $databaseOtpExpireTime =  User::where('email', '=', request()->email)->value('otp_expire');

        if (Hash::check(request()->otp, $databaseOtpHashed) && strtotime($databaseOtpExpireTime) > strtotime(now())) {
            return true;
        } else {
            return false;
        }
    }

    public function validateOtp()
    {
        $formFields = request()->validate([
            'email' => ['required', 'email'],
            'otp' => ['required',],
        ]);

        if (self::isValidOtp()) {
            return response(["status" => 200, "message" => "OTP valid"]);
        } else {
            return response(["status" => 401, "message" => "OTP invalid"], 401);
        }
    }
}