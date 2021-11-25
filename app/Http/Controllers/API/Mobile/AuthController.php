<?php

namespace App\Http\Controllers\API\Mobile;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Password;

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
            'phone' => 'required',
        ]);

        $password = 'q12345';

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($password);

        $user = User::wherePhone($request->phone)
            ->first();
        if (empty($user)){
            $user = User::create($input);
        }elseif($user->password === null){
            $user->update(['password' =>$input['password']]);
        }else{
            return $this->sendError('Validation Error.', 'This number is already exists',422);
        }

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


    public function sendCode(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => function ($attribute, $value, $fail) {
                if (User::wherePhone($value)->count() == 0) {
                    $fail('Phone doesn\'t exist');
                }
            },

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);
        }

        $user = User::wherePhone($request->phone);
        $code = rand();
        $user->update([ 'reseting_code' => $code ]);

        $params = [
            'code' => $code
        ];
        if ($user->first()->email != null){
            Mail::to($user->first()->email)->send(new Password($params));
        }else{
            //send sms
        }

        $success['user_id'] = $user->first()->id;

        return $this->sendResponse($success, 'Success sending');


    }

    public function checkCode(Request $request){

        if (User::find($request->user_id)->reseting_code !== $request->code){
            return $this->sendError('Validation Error.', 'The code is invalid',422);
        }

        $success['user_id'] = $request->user_id;

        return $this->sendResponse($success, 'You can change your password');


       }


    public function resetPassword(Request $request)
    {

        $user = User::find($request->user_id);

        if (isset($request->new_password)) {
            $validator = Validator::make($request->all(), [

                'new_password' => 'required|min:6|max:12|confirmed',

            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $user->update(['password' => bcrypt($request->new_password)]);

            return $this->sendResponse('Success', 'Password changed successfully.');

        }
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
