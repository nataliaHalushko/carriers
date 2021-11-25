<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        return $this->sendResponse($user);

    }

    public function changePassword(Request $request){

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) use($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Old Password didn\'t match');
                }
            }
            ],
            'new_password'     => 'required|min:6|max:12|confirmed',

        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);
        }

        $user->update([ 'password' => bcrypt($request->new_password) ]);

        return $this->sendResponse('Success', 'Password changed successfully.');


    }
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {

        return $this->sendResponse($user);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'phone'=>'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->sendResponse([], 'Mixers created successfully.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'phone'=>'max:255',
            'first_name' => 'max:255',
            'last_name' => 'max:255',
            'email' => 'max:255'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $img = str_replace('public', 'storage', $request->file('photo')->store('public/photo'));
            $user->img = $img;
            $user->update();
        }
        foreach ($input as $key => $value) {
            $user->update([$key => $value]);
        }

        return $this->sendResponse($user, 'User updated.');

    }
}
