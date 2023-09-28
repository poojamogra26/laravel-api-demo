<?php

namespace App\Http\Controllers\Api\UserAuth;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try
        {
            $requestData = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                ],
                [
                    'first_name.required' => 'FirstName is required.',
                    'last_name.required' => 'LastName is required.',
                    'email.required' => 'Emial is required.',
                    'email.email' => 'Enter valid email.',
                    'email.unique' => 'Email is already register.',
                    'password.required' => 'Password is required',
                ]);
            if ($requestData->fails()) { 

                return response()->json(['status' => false,'error'=>$requestData->messages()], 200);
            }
            $createUser = array(
                'first_name'=> $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            );
            $response = User::create($createUser);

            return response()->json(['status' => true,'message' => 'You have successfully register','payload' => $response], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
    
    public function login(Request $request)
    {
        try {
            $requestData = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                ],
                [
                    'email.required' => 'Emial is required.',
                    'email.email' => 'Enter valid email.',
                    'password.required' => 'Password is required',
            ]);
            if ($requestData->fails()) { 

                return response()->json(['status' => false,'error'=>$requestData->messages()], 200);
            }
            User::where('email',$request->email);
            $credentials = $request->only('email', 'password');
            if(!auth()->attempt($credentials)) {

                return response()->json(['status' => false,'error' => 'These credentials do not match our records.'], 401);
            }
            $loginToken = auth()->user()->createToken('UserAuth',['user-api'])->accessToken;

            return response()->json(['status' => true,'token' => $loginToken,'message'=> 'You have successfully logged in.'], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
    

    public function logout(Request $request,$id)
    {
        try {
            $request->user()->token()->revoke();

            return response()->json(['status' => true,'message'=> 'Logout successfully.'], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
        
    }
    
}
