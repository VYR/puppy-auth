<?php
namespace App\Http\Controllers;
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
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|between:2,100',
            'role' => [new Enum(RoleEnum::class)],
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
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