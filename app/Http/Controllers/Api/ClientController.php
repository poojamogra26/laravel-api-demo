<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\EditClientRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $userId)
    {
        $columnArr = [
            0 => null,
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'price' => 'price',
            'schedule_date' => 'schedule_date'
        ];

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $searchData[] = ['user_id', intval($userId)];
        $requestData = ['name', 'phone', 'email', 'price', 'schedule_date'];

        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }
        $clientsData = Client::where($searchData)->orderBy($columnName, $columnOrderBy);

        try {
            $clientsData = $clientsData->paginate(config('constant.pagination.limit'));

            return response()->json([
                "status" => true,   
                "message" => "Client retrieved successfully.",
                "payload" => $clientsData
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
     * @param  \App\Http\ClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request, $id)
    {
        
        try {
            $userId = User::find($id);
            
            if($userId != null){
                $requestData = $request->all();
                $requestData['user_id'] = intval($id);
                $clientInfo = Client::create($requestData);
                
                return response()->json([
                    "status" => true,
                    "message" => "Client added successfully.",
                    "payload" => $clientInfo
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $id)
    {
        try {
            $clientData = Client::Where('user_id',$user_id)->Where('id',$id)->first();

            if(is_null($clientData)) {
                return response()->json([
                    "status" => false,
                    "message" => "Client not found.",
                ], 200);
            }

            return response()->json([
                "status" => true,
                "message" => "Client retrieved successfully.",
                "payload" => $clientData
            ], 200);
        } catch(Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditClientRequest  $request
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function update(EditClientRequest $request, $user_id, $id)
    {
        
        try {
            $updateData = $request->all();
            $client = Client::Where('user_id',$user_id)->Where('id',$id)->first();
            if (is_null($client)) {
                return response()->json([
                    "status" => false,
                    "message" => "Client not found."
                ], 200);
            }
            $client->update($updateData);
            
            return response()->json([
                "status" => true,
                "message" => "Client has been updated successfully.",
                "payload" => $client
            ], 200);
        } catch(Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $client = Client::find($id);
            
            if (is_null($client)) {
                return response()->json([
                    "status" => false,
                    "message" => "Client not found."
                ], 200);
            }

            $deleteClient = Client::destroy($id);
            return response()->json([
                "status" => true,
                "message" => "Client deleted successfully.",
                "payload" => $deleteClient
            ], 200);
        } catch(Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}
