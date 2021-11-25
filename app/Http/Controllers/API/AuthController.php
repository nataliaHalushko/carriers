<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users',
        ]);

        $password = 12345;

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($password);
        $user = User::create($input);
        $success['phone'] =  $user->phone;

        return $this->sendResponse($success, 'User created successfully.');
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['phone' => $request->get("phone"), 'password' => $request->get("code")])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            $success['phone'] =  $user->phone;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'],401);
        }

    }


    public function changePassword(Request $request){

        $user = Auth::user();
        $password = $request->only([
            'current_password', 'new_password', 'new_password_confirmation'
        ]);

        $validator = Validator::make($password, [
            'current_password' => 'required|current_password_match',
            'new_password'     => 'required|min:6|confirmed',

        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);
        }
        $user->update([ 'password' => bcrypt($password['new_password']) ]);

        return $this->sendResponse('Success', 'Password changed successfully.');


    }

    public function details() {
        $user = Auth::user();
        return response()->json($user, $this->successStatus);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function unauthorized() {
        return response()->json("unauthorized", 401);
    }
}
