<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\UserSubscribed;
use App\Models\User;
use Defuse\Crypto\File;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class AuthController extends Controller
{
    public function register(Request $request)
    {

     


        // $validatedData = $request->validate(
        //     [
        //         'name' => 'required|max:55|unique:users',
        //         'email' => 'unique:users|required',
        //         'phone_no' => 'unique:users',
        //         'password' => 'required'
        //     ]
        // );
        // $validatedData['name'] = $request->name;
        // $validatedData['phone_no'] =   $request->phone_no;
        // $validatedData['email'] =   $request->email;
        // $validatedData['role_id'] =   4;
        // $validatedData['password'] = bcrypt($request->password);
        // $validatedData['device_token'] =   $request->device_token;


        // $user = User::create(
        //     $validatedData
        // );


        $username = $request->name;
        $phone_no =   $request->phone_no;
        $email =   $request->email;
        $password = bcrypt($request->password);
        $device_token =   $request->device_token;

        $validateUserName = $this->validateUserName($username);
        $validateEmail = $this->validateEmail($email);
        $validatePhoneNo = $this->validatePhoneNo($phone_no);

        if ($validateUserName || $validateEmail || $validatePhoneNo) {
            return [
                'message' => 'invalid data',
                'errors' =>   [$validateUserName, $validateEmail, $validatePhoneNo]
                // 'errors' => implode(',', [$validateUserName, $validateEmail, $validatePhoneNo])
            ];
        }




        $user = User::create(
            [
                'name' => $username,
                'phone_no' => $phone_no,
                'email' => $email,
                'role_id' => 4,
                'password' => $password,
                'device_token' => $device_token
            ]
        );



        $accessToken = $user->createToken('authToken')->accessToken;


        return response(['user' => $user, 'token' => $accessToken]);
    }

    public function validateUserName($name)
    {

        if (
            User::where('name', $name)->first() && User::where('name', $name)->first()->name == $name
            // ||  (Auth::user() && User::where('name', $name)->first()->id != Auth::user()->id)

        )
            return 1;
    }
    public function validateEmail($email)
    {

        if (
            User::where('email', $email)->first() && User::where('email', $email)->first()->email == $email
            // || (Auth::user() &&  User::where('email', $email)->first()->id != Auth::user()->id)
        )
            return 1;
    }
    public function validatePhoneNo($phone_no)
    {
        if (
            User::where('phone_no', $phone_no)->first() && User::where('phone_no', $phone_no)->first()->phone_no == $phone_no
            // || (Auth::user() &&  User::where('phone_no', $phone_no)->first()->id != Auth::user()->id)
        )
            return 1;
    }




    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = [

                    'token' => $token,
                    'user' => $user
                ];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function update(Request $request)
    {
        $user = $request->user();

        
        
        if (isset($request->image)) {
            Image::make(file_get_contents(base64_decode($request->image)))->save('/storage/users');
            $imageName = Str::random(10) . '.jpg';
            Storage::disk('local')->put('public/users' . '/' . $imageName, base64_decode($request->image), 'public');
            $imageName = 'users/' . $imageName;
            $user->avatar = $imageName;
        }



        if (isset($request->phone_no)) {

            $user->phone_no = $request->phone_no;
        }

        if (isset($request->name)) {

            $user->name = $request->name;
        }

        if (isset($request->email)) {

            $user->email = $request->email;
        }

        if (isset($request->device_token)) {

            $user->device_token = $request->device_token;
        }

        if (isset($request->password)) {

            $user->password = $request->password;
        }

        // $validateUserName = $this->validateUserName($request->name);
        // $validateEmail = $this->validateEmail($request->email);
        // $validatePhoneNo = $this->validatePhoneNo($request->phone_no);


        // if ($validateUserName || $validateEmail || $validatePhoneNo) {
        //     return [
        //         'message' => 'invalid data',
        //         'errors' =>   [$validateUserName, $validateEmail, $validatePhoneNo]
        //         // 'errors' => implode(',', [$validateUserName, $validateEmail, $validatePhoneNo])
        //     ];
        // }

        $save =  $user->save();

        if ($save) {
            return
                $user;
        }
    }

    public function verify(Request $request)
    {
        $ver_code = $request->ver_code;
        if ($ver_code == $request->user()->ver_code) {
            $verUser = User::find($request->user()->id);
            $verUser->email_verified_at = date("Y-m-d h:i");
            $verUser->save();
            $result = ['done'];
        } else {
            $result = ['faild'];
        }

        return $result;
    }


    public function forgotPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user) {
            // $code = $user->id . '-' . rand(1000, 10000);
            $code =  rand(1000, 1000000);
            Mail::to($user->email)->send(new ResetPassword($code));

            $updateUser = User::find($user->id);
            $updateUser->ver_code =  $code;
            $updateUser->save();
            $result = [
                'result' => 'success',
                'msg' => 'done check your mail box'
            ];
        } else {
            $result = [
                'result' => 'error',
                'msg' => 'there is no user with email ' . $email
            ];
        }

        return $result;
    }


    public function reset(Request $request)
    {

        $code = $request->reset_code;

        $user = User::where('ver_code', $code)->first();

        if ($user && $user->ver_code == $code) {

            $user->password = bcrypt($request->password);
            $user->save();
            $result = [
                'result' => 'success',
                'msg' => 'done'
            ];
        } else {
            $result =  [
                'result' => 'error',
                'msg' => 'your code is not found'
            ];
        }

        return $result;
    }
}
