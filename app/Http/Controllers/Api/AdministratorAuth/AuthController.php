<?php

namespace App\Http\Controllers\Api\AdministratorAuth;

use Exception;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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

                return response()->json(['error' =>$requestData->messages()], 200);
            }
            $credentials = $request->only('email', 'password');
            $loginToken = Auth::attempt($credentials);
            if(!$loginToken) {

                    return response()->json(['status' => 401,'error'=>'These credentials do not match our records'], 401);
            } 

            return $this->createNewToken($loginToken);
         } catch(Exception $e) {

             return response()->json(['status' => false,'error' => $e->getMessage()], 500);
         }
    }

    public function refresh() 
    {

        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($loginToken)
    {
        $role = Role::findOrFail(auth()->user()->role_id);
        $givenPermissions = Role::findByName($role->name);
        $givenPermissions->permissions;
        
        return response()->json([
            'status' => 200,
            'message'=> 'You have successfully logged in.',
            'token_type' => 'bearer',
            'token' => $loginToken,
            "payload" => auth()->user(),
            "rolePermission" => $givenPermissions
        ], 200);
    }

    public function logout($id)
    {
        try {
            Auth::logout();

            return response()->json(['status' => true,'message'=> 'Logout successfully.'], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
        
    }

    public function loggedInUser()
    {
        try {       
            // dd(auth()->user()->id);
            $loggedInUserId = auth()->user()->id;
            $loggedInUser = Administrator::select('id','role_id','first_name','last_name', 'email','phone_number', 'avatar','status','last_login')->with('roles:name')->findOrFail($loggedInUserId);
            $loggedInUser->roles->makeHidden('pivot');
            
            return response()->json([
                "status" => true,
                "message" => "Logged In user data retrieved.",
                "payload" => $loggedInUser
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}

