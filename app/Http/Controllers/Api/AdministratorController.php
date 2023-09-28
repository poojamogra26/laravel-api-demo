<?php

namespace App\Http\Controllers\Api;

use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\AdministratorRequest;
use App\Http\Requests\EditAdministratorRequest;
use Illuminate\Support\Facades\DB;

class AdministratorController extends Controller
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
            'email' => 'email',
            'role.name' => 'roles.name',
            'status' => 'administrators.status'
        ];

        $searchData = [];

        $requestData = ['email'];
        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }
        
        if (!empty($request->full_name)) {
            $searchData[] = [DB::raw("CONCAT(first_name,' ',last_name)"), 'LIKE', '%' . trim($request->full_name) . '%'];
        }

        if (isset($request->status)) {
            $searchData[] = ['administrators.status', '=', $request->status];
        }

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $administratorData = Administrator::with('role:id,name')
                ->leftJoin('roles', 'roles.id', '=', 'administrators.role_id')
                ->select('administrators.*')
                ->where($searchData)->orderBy($columnName, $columnOrderBy);

        if (isset($request->role_name)) {
            $roleName = $request->role_name;
            $administratorData->whereHas('role', function ($query) use ($roleName) {
                $query->where('roles.name', 'LIKE', '%' . $roleName . '%');
            });
        }
        try {
            $administratorData = $administratorData->paginate(config('constant.pagination.limit'));

            return response()->json([
                "status" => true,
                "message" => "Administrator retrieved successfully.",
                "payload" => $administratorData
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
     * @param  \App\Http\Requests\AdministratorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdministratorRequest $request)
    {
        try {
            $requestData = $request->all();
            $requestData['password'] = bcrypt($requestData['password']);
            $requestData['last_login'] = date('Y-m-d H:i:s');
            $administratorInfo = Administrator::create($requestData);
            $administratorInfo->assignRole($request->role_id);
            
            return response()->json([
                "status" => true,
                "message" => "Administrator has been added successfully.",
                "payload" => $administratorInfo
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $administrator = Administrator::findOrFail($id);
            if(is_null($administrator)) {
                $this->sendError('Administrator not found.');
            }

            return response()->json([
                "status" => true,
                "message" => "Administrator retrieved successfully.",
                "payload" => $administrator
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $administrator = Administrator::findOrFail($id);
            if(is_null($administrator)) {
                $this->sendError('Administrator not found.');
            }

            return response()->json([
                "status" => true,
                "message" => "Administrator retrieved successfully.",
                "payload" => $administrator
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id ,EditAdministratorRequest $request)
    {
        try {
            $administratorData = Administrator::findOrFail($id);
            if(is_null($administratorData)) {
                $this->sendError('User not found.');
            }
            $requestData = $request->all();
            // if(!empty($requestData['password'])) {
            //     $password = $request->validate(
            //         [
            //             'password' => 'required',
            //             'confirm_password' => 'required|same:password',
            //         ],
            //         [
            //             'password.required' => 'Password is required',
            //             'confirm_password.required' => 'Confirm password is required.',
            //             'confirm_password.same' => 'Password and confirm password does not match.',
            //     ]);
            //     $requestData['password'] = bcrypt($password['password']);
            // }
            $administratorData->update($requestData);

            return response()->json([
                "status" => true,
                "message" => "User information has been updated successfully.",
                "payload" => $administratorData
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $user = auth()->user()->id;
            if($user != $id)
            {
                $deleteAdministrator = Administrator::destroy($id);

                return response()->json([
                    "status" => true,
                    "message" => "Administrator deleted successfully.",
                    "payload" => $deleteAdministrator
                ], 200);
            }else{
                return response()->json([
                    "status" => true,
                    "message" => "User logged in so you can not delete it."
                ], 200);
            }
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    public function editpermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $editPermission = Role::findByName($role->name);
        $allpermissions = [];
        foreach ($request->request as $value) {
            array_push($allpermissions, $value['permission_name']);
        }
        $editPermission->syncPermissions($allpermissions);
        
        return response()->json([
            "status" => true,
            "message" => "Permission Added successfully.",
            "payload" => $editPermission
        ], 200);
        
        
    }

}
