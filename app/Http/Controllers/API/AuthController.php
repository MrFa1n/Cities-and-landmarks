<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Profile;
use App\Models\ProfileFields;
use App\Models\ProfileFieldsTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // return response($request);

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        if($validator->fails()){
            return response(['status'=>'error', 'error' => $validator->errors(), 'Validation Error']);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);
        $profile = Profile::create(['user_id' => $user->id]);
        $fields = ProfileFieldsTypes::all();

        $accessToken = $user->createToken('authToken')->accessToken;

        event(new Registered($user));
        return response(['status'=>'ok', 'response'=>[ 'user' => $user, 'profile_id' => $profile->id, 'access_token' => $accessToken]], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['status'=>'error', 'error'=>['message' => 'Invalid Credentials']]);
        }
        
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['status'=>'ok', 'response'=>['user' => auth()->user(), 'access_token' => $accessToken]], 200);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
