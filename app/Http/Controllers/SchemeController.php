<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use Illuminate\Http\Request;
use Validator;


class SchemeController extends Controller
{
   
    public function __construct() {
       
    }    
    public function create(Request $request,int $id=0) {
        $responseData=[];
        if($id==0){
            $inputData=$request->all();
            $validate=[
                'scheme_type_id' => 'required|integer|gt:0',
                'amount_per_month' => 'required|integer|gt:0',
                'no_of_months' => 'required|integer|gt:0'
            ];

            $validator = Validator::make($inputData,  $validate);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            if($inputData['scheme_type_id']==2)
            $validate['total_amount']='required|integer|gt:0';
            else
            $validate['coins']='required|integer|gt:0';
            $validator = Validator::make($inputData,  $validate);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $schemeType = Scheme::create($inputData);
            $responseData=[
                    'message' => 'Scheme created successfully',
                    'data' => $schemeType
                ];
        }
        else{
            $existingRecord=Scheme::find($id);
            $existingRecord->scheme_type_id=$request->scheme_type_id;
            $existingRecord->amount_per_month=$request->amount_per_month;
            $existingRecord->no_of_months=$request->no_of_months;
            if($request->scheme_type_id){
                $existingRecord->scheme_type_id=$request->scheme_type_id;
            }
            if($request->amount_per_month){
                $existingRecord->amount_per_month=$request->amount_per_month;
            }
            if($request->no_of_months){
                $existingRecord->no_of_months=$request->no_of_months;
            }
            if($request->total_amount){
                $existingRecord->total_amount=$request->total_amount;
            }
            if($request->coins){
                $existingRecord->coins=$request->coins;
            }
            if($request->status){
                $existingRecord->status=$request->status;
            }
            $existingRecord->save();
            $responseData=[
                'message' => 'Scheme updated successfully',
                'data' => $existingRecord
            ];
        }
        return response()->json($responseData, 201);
    }   
    public function read(int $id=0) {
        $data=array();
        if($id==0)
        $data=Scheme::all();
        else{
            $request= ["id" => $id];
            $validator = Validator::make($request, [
                'id' => 'required|integer|gt:0'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $result=Scheme::find($id);
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
