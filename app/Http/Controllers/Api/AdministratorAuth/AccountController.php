<?php

namespace App\Http\Controllers\Api\AdministratorAuth;

use Exception;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
            $requestData = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ],
            [
                'current_password.required' => 'Current password is required',
                'new_password.required' => 'New password is required',
                'confirm_password.required' => 'Confirm password is required.',
                'confirm_password.same' => 'Password and confirm password does not match.'
            ]);
            if ($requestData->fails()) { 

                return response()->json(['status' => false,'error'=>$requestData->messages()], 422);
            }
            $updateData = [];
            $updateData = $request->all();
            $admin = Administrator::findOrFail(auth()->user()->id);
            if ($request->has('current_password') || $request->has('new_password') || $request->has('confirm_password')) {
                if (!empty($request->new_password) && !empty($request->current_password)) {
                    if (Hash::check($request->current_password, $admin->password)) {
                        $updateData['password'] = bcrypt($request->new_password);
                    } else {
                        return response()->json([
                            "status" => false,
                            "message" => "Please enter valid passwords.",
                        ], 422);
                    }
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Please enter valid passwords.",
                    ], 422);
                }
            }
            
            if ($admin->update($updateData)) {
                return response()->json([
                    "status" => true,
                    "message" => "Profile updated sucessfully.",
                    "payload" => $admin
                ], 200);
            }
            return response()->json([
                "status" => false,
                "message" => "Profile not updated sucessfully.",
            ], 422);
        } catch (Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    public function editAvatar(Request $request)
    {
        try {

            $requestData = Validator::make($request->all(), [
                'avatar'     => 'required|image|mimes:jpeg,png,jpg'
            ],
            [
                'avatar.required'    => 'Avatar is required',
                'avatar.image'       => 'Enter only image',
                'avatar.mimes'       => 'Enter only image jpeg,png and jpg.'
            ]);
            if ($requestData->fails()) { 

                return response()->json(['status' => false,'error'=>$requestData->messages()], 422);
            }

            $updateData = [];
            $updateData = $request->all();
            $admin = Administrator::findOrFail(auth()->user()->id);
            if (!empty($request->file('avatar'))) {
                $avatarName = $request->file('avatar')->store('images/admin', 'public');
                if ($avatarName) {
                    $updateData['avatar'] = $avatarName;
                    Storage::delete('public/' . $admin->avatar);
                } else {                    
                    $updateData['avatar'] = $admin->avatar;
                }
            }
            if ($admin->update($updateData)) {

                return response()->json([
                    "status" => true,
                    "message" => "Profile updated sucessfully.",
                    "payload" => $admin
                ], 200);
            }
            return response()->json([
                "status" => false,
                "message" => "Profile not updated sucessfully.",
            ], 422);
        } catch (Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    public function editProfile(Request $request)
    {
        try {
            $requestData = Validator::make($request->all(), [
                'first_name'        => 'required',
                'last_name'         => 'required',
                'email'             => 'required|email|unique:administrators,email,'.auth()->user()->id.',id,deleted_at,NULL',
                'phone_number'      => 'required|min:10',
            ],
            [
                'first_name.required'       => 'First Name is required',
                'last_name.required'        => 'Last Name is required',
                'email.required'            => 'Email is required',
                'email.email'               => 'Entar valid email',
                'email.unique'              => 'Email is already exit',
                'phone_number.required'     => 'Phone number is required',
                'phone_number.min'          => 'Enter minimum 10 digits'
            ]);
            if ($requestData->fails()) { 

                return response()->json(['status' => false,'error'=>$requestData->messages()], 422);
            }
            $updateData = [];
            $updateData = $request->all();
            $admin = Administrator::findOrFail(auth()->user()->id);
            if ($admin->update($updateData)) {

                return response()->json([
                    "status" => true,
                    "message" => "Profile updated sucessfully.",
                    "payload" => $admin
                ], 200);
            }
            return response()->json([
                "status" => false,
                "message" => "Profile not updated sucessfully."
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}

