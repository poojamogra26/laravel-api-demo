<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\companyEditRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columnArr = [
            0 => null,
            'full_name' => 'first_name',
            'user_name' => 'user_name',
            'email' => 'email',
            'company.name' => 'companies.name',
            'status' => 'status'
        ];

        $searchData = [];

        $requestData = ['user_name'];
        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }
        
        if (!empty($request->full_name)) {
            $searchData[] = [DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', '%' . trim($request->full_name) . '%'];
        }

        if (isset($request->email)) {
            $searchData[] = ['users.email', 'LIKE', '%' . trim($request->email) . '%'];
        }

        if (isset($request->status)) {
            $searchData[] = ['status', '=', $request->status];
        }

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $userData = User::with('company:id,name,user_id')
                ->leftJoin('companies', 'users.id', '=', 'companies.user_id')
                ->select('users.*')
                ->where($searchData)->orderBy($columnName, $columnOrderBy);

        if (isset($request->company_name)) {
            $companyName = $request->company_name;
            $userData->whereHas('company', function ($query) use ($companyName) {
                $query->where('companies.name', 'LIKE', '%' . $companyName . '%');
            });
        }
        try {
            $userData = $userData->paginate(config('constant.pagination.limit'));

            return response()->json([
                "status" => true,
                "message" => "User retrieved successfully.",
                "payload" => $userData
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $requestData = $request->all();
            $requestData['profile_image'] = $request->file('profile_image')->store('images/users', 'public');
            $requestData['password'] = bcrypt($requestData['password']);
            $userInfo = User::create($requestData);

            return response()->json([
                "status" => true,
                "message" => "User added successfully.",
                "payload" => $userInfo
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $userData = User::with('userBillingAddress','company')->findOrFail($id);
            if(is_null($userData)) {
                $this->sendError('User not found.');
            }

            return response()->json([
                "status" => true,
                "message" => "User retrieved successfully.",
                "payload" => $userData
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $userData = User::with('userBillingAddress','company')->findOrFail($id);
            if (is_null($userData)) {
                $this->sendError('User not found.');
            }

            return response()->json([
                "status" => true,
                "message" => "User retrieved successfully.",
                "payload" => $userData
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditUserRequest  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, $id)
    {
        try {
            $updateData = $request->all();
            $user = User::with('userBillingAddress','company')->findOrFail($id);
            if (is_null($user)) {
                $this->sendError('User not found.');
            }
            if ($request->hasFile('profile_image')) {
                $imageName = $request->file('profile_image')->store('images/users', 'public');
                if ($user->profile_image) {
                    Storage::delete('public/' . $user->profile_image);
                }
                $updateData['profile_image'] = $imageName;
            } 
            if(!empty($requestData['password'])) {
                $updateData['password'] = bcrypt($requestData['password']);
            }
            $user->update($updateData);
            
            if(array_key_exists('billing_address', $updateData)){
                $user->userBillingAddress()->updateOrCreate(['user_id' => $id],$updateData['billing_address']);
            }
            $user = User::with('userBillingAddress','company')->findOrFail($id);
            return response()->json([
                "status" => true,
                "message" => "User has been updated successfully.",
                "payload" => $user
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $deleteUser = User::destroy($id);
            Storage::delete('public/images/users/' . $user->profile_image);

            return response()->json([
                "status" => true,
                "message" => "User deleted successfully.",
                "payload" => $deleteUser
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    public function personalInfoEdit(EditUserRequest $request, $id)
    {
        
        try {
            $updateData = $request->all();
            $user = User::with('userBillingAddress')->find($id);
            
            if (is_null($user)) {
                return response()->json([
                    "status" => false,
                    "message" => "User not found."
                ], 200);
            }
            $imageName = '';
            if ($request->hasFile('profile_image')) {
                $imageName = time() . '.' . $updateData['profile_image']->extension();
                $updateData['profile_image']->storeAs('public/images/users/', $imageName);
                if ($user->profile_image) {
                    Storage::delete('public/images/users/' . $user->profile_image);
                }
                $updateData['profile_image'] = $imageName;
            } 

            $user->update($updateData);
            $user->userBillingAddress()->updateOrCreate(['user_id' => $id],$updateData);

            
            $user = User::with('userBillingAddress')->findOrFail($id);
            return response()->json([
                "status" => true,
                "message" => "User has been updated successfully.",
                "payload" => $user
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    public function companyedit(companyEditRequest $request, $id)
    {
        try {
            $userId = User::find($id);
            
            if($userId != null){
                $requestData = $request->all();
                $requestData['user_id'] = Intval($id);
                $company = Company::where('user_id',$id)->first();
                $company->update($requestData);
                
                return response()->json([
                    "status" => true,
                    "message" => "User's Comapany updated successfully.",
                    "payload" => $company
                ], 200);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "User not exist."
                ], 200);
            }
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}

