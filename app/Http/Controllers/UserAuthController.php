<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8'

        ]);

        $roleExiste = Role::whereRaw('LOWER(role_name) = ?','client')->exists();

        if(!$roleExiste){
            $role = Role::create(['role_name'=>'client']);
            $roleId = $role->id;
        }else{
            $roleId = Role::whereRaw('LOWER(role_name) = ?','client')->first()->id;
        }

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'role_id'=> $roleId
        ]);

        $randomSerial = Str::random(9).$user->id;

         Wallet::create([
            'user_id'=> $user->id,
            'serial'=>strtoupper($randomSerial),
            'balance'=>0
        ]);



        return response()->json([
            'message' => 'User Created & wallet Created ',
        ]);
    }



    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'access_token' => $token,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message"=>"logged out"
        ]);
    }

}
