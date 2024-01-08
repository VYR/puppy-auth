<?php
namespace App\Http\Controllers;
use App\Enums\UserTypeEnum;
use App\Exceptions\CustomException;
use App\Models\SchemeType;
use Exception;
use Illuminate\Http\RedirectResponse;
use Storage;
use Illuminate\Http\UploadedFile;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, int $id=0) {
        $responseData=array();
        $inputData=$request->all();
        $rules=[           
            'firstName' => 'required|string|between:2,100',
            'lastName' => 'required|string|between:2,100', 
            'email' => 'required|string|email|max:100|unique:users', 
            'password' => 'required|string|min:6',   
            'role' => [new Enum(RoleEnum::class)],
            'userType' => [new Enum(UserTypeEnum::class)],
            'mobilePhone'=>'required|string|min:10',
            'aadhar'=>'required|string|min:12',
            'pan'=>'required|string|min:10',
            'introducedBy'=>'required|string|min:9'
        ];
        if($id==0)
        {            
            $validate=[           
                'firstName' => 'required|string|between:2,100',
                'lastName' => 'required|string|between:2,100', 
                'email' => 'required|string|email|max:100|unique:users', 
                'password' => 'required|string|min:6',   
                'role' => [new Enum(RoleEnum::class)],
                'userType' => [new Enum(UserTypeEnum::class)]
            ];

            $validator = Validator::make($inputData,  $validate);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            } 
            if($inputData['role']==RoleEnum::SchemeMember){
                $validate['mobilePhone']='required|string|min:10';
                $validate['aadhar']='required|string|min:12';
                $validate['pan']='required|string|min:10';
                $validate['introducedBy']='required|string|min:9';
                $validator = Validator::make($request->all(), $validate);
                if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
                }
            }
            else {
                $validate=array();
                foreach ($inputData as $key => $value) {                    
                    if(key_exists($key,$rules)){
                        if(!in_array($key,['role','userType'])){
                            $x=array();
                            $x[$key]=$rules[$key];
                            array_push($validate,$x);
                        }
                        if($key == 'role')$validate['role'] = [new Enum(RoleEnum::class)];
                        if($key == 'userType')$validate['userType'] = [new Enum(UserTypeEnum::class)];
                    }
                }
                if(count($validate)>0){
                    $validator = Validator::make($request->all(), $validate);
                    if($validator->fails()){
                        return response()->json($validator->errors()->toJson(), 400);
                    }
                }
            }
           
            $inputData['userId']='SGS'.random_int(100000, 999999);
            $inputData['userName']=$inputData['firstName'].' '.$inputData['lastName'];
            $inputData['password']= bcrypt($request->password);
            $user = User::create($inputData);
            $responseData=[
                'message' => $inputData['role'].' created successfully',
                'data' => $user,
                'inputData' => $inputData
            ];
        }
        else {
            $validate=array();
            foreach ($inputData as $key => $value) {                    
                if(key_exists($key,$rules)){
                    array_push($validate,array($key => $rules[$key]));
                }
            }
            if(count($validate)>0){
                $validator = Validator::make($request->all(), $validate);
                if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
                }
            }
            $existingRecord=User::find($id);
            if($request->firstName){
                $existingRecord->firstName=$request->firstName;
            }
            if($request->lastName){
                $existingRecord->lastName=$request->lastName;
            }
            if($request->email){
                $existingRecord->email=$request->email;
            }
            if($request->role){
                $existingRecord->role=$request->role;
            }
            if($request->userType){
                $existingRecord->userType=$request->userType;
            }
            if($request->password){
                $existingRecord->password=bcrypt($request->password);
            }             
            if($request->mobilePhone){
                $existingRecord->password=$request->mobilePhone;
            } 
            if($request->aadhar){
                $existingRecord->aadhar=$request->aadhar;
            } 
            if($request->pan){
                $existingRecord->pan=$request->pan;
            } 
            if($request->introducedBy){
                $existingRecord->introducedBy=$request->introducedBy;
            } 

            if($request->status){
                $existingRecord->status=$request->status;
            }
            $existingRecord->save();
            $responseData=[
                'message' => $existingRecord->role.' updated successfully',
                'data' => $existingRecord
            ];
        }
        return response()->json($responseData, 201);
    }
    public function read(int $id=0) {
        $data=array();
        if($id==0)
        $data=User::all();
        else{
            $request= ["id" => $id];
            $validator = Validator::make($request, [
                'id' => 'required|integer|gt:0'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $result=User::find($id);
            if($result)
            $data=array($result);
        }
        $responseData=[
            'message' => count($data)>0?count($data).' record(s) found':($id==0?'No records found':'No record found with id '.$id),
            'data' => $data
        ];    
        return response()->json($responseData, 200);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        try {

            return response()->json(auth()->user());
        }
        catch(\Exception $e){
            throw new HttpException(500, $e->getMessage());
        }
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function store(Request $request)
    {
        $msg='';
        $id=0;
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $name = rand(000000,999999).'_'.time();  
        $imageName = $name.'.'.$request->image->extension(); 
         
        $request->image->move(public_path('images'), $imageName);
      
        $save = new Image();
        $save->title = $name;
        $save->path = $imageName;
        if($save->save()){
            $msg='File saved successfully';
            $id=$save->id;
        }
        else{
            $msg='File not saved';
        }
        return response()->json(['message' =>  $msg,'image_id' =>  $id], 200);
    }
    
}