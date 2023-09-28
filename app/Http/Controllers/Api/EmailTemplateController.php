<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Http\Requests\EmailTemplatesRequest;
use App\Http\Requests\EditEmailTemplatesRequest;

class EmailTemplateController extends Controller
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
            'subject' => 'subject',
            'description' => 'description',
            'slug' => 'slug'
        ];

        $searchData = [];

        $requestData = ['subject', 'description', 'slug'];
        foreach ($requestData as $field) {
            if (!empty($request->$field)) {
                $searchData[] = [$field, 'LIKE', '%' . trim($request->$field) . '%'];
            }
        }

        $columnValue = !empty($request->input('_sort')) ? $request->input('_sort') : 0;
        $columnName = !empty($columnArr[$columnValue]) ? $columnArr[$columnValue] : 'created_at';
        $columnOrderBy = !empty($request->input('_order')) ? $request->input('_order') : 'desc';

        $emailTemplateData = EmailTemplate::where($searchData)->orderBy($columnName, $columnOrderBy);

        try {
            $emailTemplateData = $emailTemplateData->paginate(config('constant.pagination.limit'));

            return response()->json([
                "status" => true,   
                "message" => "EmailTemplate retrieved successfully.",
                "payload" => $emailTemplateData
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
     * @param  \App\Http\EmailTemplatesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailTemplatesRequest $request)
    {
        //
        try {
            $requestData = $request->all();
            $requestData['slug'] = Str::slug($requestData['subject'], '-');
            $emailTemplateInfo = EmailTemplate::create($requestData);
            
            return response()->json([
                "status" => true,
                "message" => "EmailTemplate added successfully.",
                "payload" => $emailTemplateInfo
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
            $emailTemplateData = EmailTemplate::findOrFail($id);

            return response()->json([
                "status" => true,
                "message" => "EmailTemplate retrieved successfully.",
                "payload" => $emailTemplateData
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
     * @param  \App\Http\EditEmailTemplatesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditEmailTemplatesRequest $request, $id)
    {
        //
        
        try {
            $updateData = $request->all();
            $emailTemplate = EmailTemplate::findOrFail($id);
            $emailTemplate->update($updateData);
            
            return response()->json([
                "status" => true,
                "message" => "EmailTemplate has been updated successfully.",
                "payload" => $emailTemplate
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
            $emailTemplate = EmailTemplate::findOrFail($id);

            $deleteEmailTemplate = EmailTemplate::destroy($id);
            return response()->json([
                "status" => true,
                "message" => "EmailTemplate deleted successfully.",
                "payload" => $deleteEmailTemplate
            ], 200);
        } catch(Exception $e) {
            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}
