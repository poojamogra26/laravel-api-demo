<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPlanRequest;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;

class PlanController extends Controller
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
            'price' => 'price',
            'interval' => 'interval'
        ];

        $searchData = [];

        $requestData = ['name', 'price', 'interval'];
        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $planData = Plan::where($searchData)->orderBy($columnName, $columnOrderBy);

        try {
            $planData = $planData->paginate(config('constant.pagination.limit'));

            return response()->json([
                "status" => true,
                "message" => "Data retrieved successfully.",
                "payload" => $planData
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
     * @param  \App\Http\Requests\PlanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        //
        try {
            $requestData = $request->all();
            $planInfo = Plan::create($requestData);
            
            return response()->json([
                "status" => true,
                "message" => "Data added successfully.",
                "payload" => $planInfo
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
        try {
            $planData = Plan::findOrFail($id);
            return response()->json([
                "status" => true,
                "message" => "Data retrieved successfully.",
                "payload" => $planData
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditPlanRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPlanRequest $request, $id)
    {
        //
        try {
            $updateData = $request->only('name', 'number_of_clients', 'free_trial');
            $plan = Plan::findOrFail($id);
            $plan->update($updateData);
            return response()->json([
                "status" => true,
                "message" => "Data has been updated successfully.",
                "payload" => $plan
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
        //
        try {
            $plan = Plan::findOrFail($id);
            $deletePlan = Plan::destroy($id);
            return response()->json([
                "status" => true,
                "message" => "Data deleted successfully.",
                "payload" => $deletePlan
            ], 200);
        } catch(Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}
