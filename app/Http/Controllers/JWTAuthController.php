<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator,Hash;
use JWTAuth;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

            try {
                if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (Exception $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

        return response()->json([
            'done'=>true,
            'message'=>'Login successful',
            'token' => $token,
            'token_type' => 'Bearer',
            //'expires_in' => '',
            'user' => auth()->user()
        ]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            //return response()->json($validator->errors()->toJson(), 400);
            return response()->json($validator->errors(), 422);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => Hash::make($request->password)]
                ));

        return response()->json([
            'done'=>true,
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
    public function user_profile() {
        return response()->json(auth()->user());
    }


     public function verify_token(Request $request) {
        return response()->json([
            'message'=>'verifyied',
            'token' => trim($request->bearerToken()),
            'user' => auth()->user(),
         ]);
    }

}
