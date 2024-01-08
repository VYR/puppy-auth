<?php

namespace App\Http\Controllers;
use App\Models\Scheme;
use App\Models\SchemeMember;
use App\Models\SchemeType;
use App\Models\User;
use Illuminate\Http\Request;

use Validator;
class CommonController extends Controller
{
    public function __construct() {
       
    }    
    public function delete(int $id=0,int $type=0) { 
        $request= ["id" => $id,"type"=>$type];
        $validator = Validator::make($request, [
            'id' => 'required|integer|gt:0',
            'type' => 'required|integer|gt:0'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $status=0;
        switch($type){
            case 1:
                $status=SchemeType::destroy($id);
                break;
            case 2:
                $status=Scheme::destroy($id);
                break;
            case 3:
                $status=User::destroy($id);
                break;
            case 4:
                $status=SchemeMember::destroy($id);
                break;
    
        }
        $responseData=[
            'message' => $status?'Record '.$id.' deleted successfully':'No record found with id '.$id,
            'data' => $request,
            'deleteStatus' => $status
        ];    
        return response()->json($responseData, 200);
    }
    public function getGraph(){
        $data=array("requestCount"=>array(
                
                array(
                    "uuid" => 'Individual',
                    "pendingCount"=>200,
                    "approvedCount"=>20,
                    "rejectedCount"=>156
                ),
                array(
                    "uuid" =>'Group',
                    "pendingCount"=>200,
                    "approvedCount"=>20,
                    "rejectedCount"=>156
                ),
                array(
                    "uuid"=>'ALL',
                    "pendingCount"=>123,
                    "approvedCount"=>58,
                    "rejectedCount"=>86,
                    "total"=>23
                )
            )
        );
        $responseData=[
            'data' => $data,
            'schemeTypes' => SchemeType::all()
        ];    
        return response()->json($responseData, 200);
    }
}
