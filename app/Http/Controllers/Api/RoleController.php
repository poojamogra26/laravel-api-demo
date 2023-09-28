<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\EditRoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
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
            'name' => 'name',
            'description' => 'description'
        ];

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $searchData = [];
        $requestData = ['name', 'description'];

        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }
        $roleData = Role::where($searchData)->orderBy($columnName, $columnOrderBy);

        try {
            $roles = $roleData->paginate(config('constant.pagination.limit'));

            return response()->json([
                'status' => true,
                'message' => 'Role get successfully',
                'payload' => $roles
            ], 200);
        } catch (Exception $e) {

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $requestData = $request->all();
            $requestData['guard_name'] = 'adminapi';
            $roleInfo = Role::create($requestData);

            return response()->json([
                "status" => true,
                "message" => "Role added successfully.",
                "payload" => $roleInfo
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
        //
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
            $roleData = Role::findOrFail($id);
            if (is_null($roleData)) {
                $this->sendError('User not found.');
            }

            return response()->json([
                "status" => true,
                "message" => "Role retrieved successfully.",
                "payload" => $roleData
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
    public function update(EditRoleRequest $request, $id)
    {
        try {
            $updateData = $request->all();
            $role = Role::findOrFail($id);
            if (is_null($role)) {
                $this->sendError('Role not found.');
            }
            $role->update($updateData);

            return response()->json([
                "status" => true,
                "message" => "Role has been updated successfully.",
                "payload" => $role
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
            $deleterole = Role::destroy($id);

            return response()->json([
                "status" => true,
                "message" => "Role deleted successfully.",
                "payload" => $deleterole
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
    public function managerolepermission($id)
    {
        $permissions = Permission::all();
        
        return response()->json([
            "status" => true,
            "message" => "Permission retrieved successfully.",
            "payload" => $permissions
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function rolepermission($id){
        try {
            // $allPermissions = Permission::select('title')->get();
            $mainArray = [];
            $allPermissions = Permission::where('parent_id',0)->select('id','title','name')->get();
            foreach ($allPermissions as $key => $value) {
                $administration = [];
                $innerPermissions = Permission::where('parent_id',$value->id)->select('title','name')->get();
                foreach ($innerPermissions as $ikey => $data) {
                    $administrationInner = [];
                    $administrationInner['title'] = $data->title ;
                    $administrationInner['name'] = $data->name ;
                    $administration[$ikey] = $administrationInner ;
                }
                $mainArray[$key]['group'] = $value->title;
                $mainArray[$key]['permissions'] = $administration;
            }
            
            $role = Role::find($id);
            
            if(is_null($role)){
                return response()->json([
                    'status' => false,
                    'message' => 'Record not found'
                ], 404);
            }
            $givenPermissions = Role::findByName($role->name);
            $givenPermissions->permissions;
            
            return response()->json([
                'status' => true,
                'message' => 'All permission get successfully',
                'payload' => array('allPermissions' => $mainArray, 'permissions' => $givenPermissions)
            ], 200);
        } catch (Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

}


