<?php

namespace App\Http\Controllers;

use App\Models\SchemeType;
use Illuminate\Http\Request;
use Validator;

class SchemeTypeController extends Controller
{
    public function __construct() {
       
    }    
    public function create(Request $request,int $id=0) {
        $responseData=[];
        $validator = Validator::make($request->all(), [
            'scheme_type_name' => 'required|string|between:2,100'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $data=$validator->validated();
        if($id==0){
            $schemeType = SchemeType::create($data);
            $responseData=[
                    'message' => 'Scheme Type created successfully',
                    'data' => $schemeType
                ];
        }
        else{
            $existingRecord=SchemeType::find($id);
            $existingRecord->scheme_type_name=$request->scheme_type_name;
            if($request->status){
                $existingRecord->status=$request->status;
            }
            $existingRecord->save();
            $responseData=[
                'message' => 'Scheme Type updated successfully',
                'data' => $existingRecord
            ];
        }
        return response()->json($responseData, 201);
    }   
    public function read(int $id=0) {
        $data=array();
        if($id==0)
        $data=SchemeType::all();
        else{
            $request= ["id" => $id];
            $validator = Validator::make($request, [
                'id' => 'required|integer|gt:0'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $result=SchemeType::find($id);
            if($result)
            $data=array($result);
        }
        $responseData=[
            'message' => count($data)>0?count($data).' record(s) found':($id==0?'No records found':'No record found with id '.$id),
            'data' => $data
        ];    
        return response()->json($responseData, 200);
    }
}
