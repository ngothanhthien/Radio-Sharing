<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function login(Request $request){
        if(!$request->has(['username', 'password'])){
            return response('',config('status.badRequest'));
        }
        [
            'username' => $username,
            'password' => $password,
        ]=$request->only('username', 'password');
        try{
            $admin=Admin::where('username', $username)->firstOrFail();
            if(!Hash::check($password,$admin->password)){
                throw new Exception('Invalid password');
            }
            return response(['token'=>$this->issueToken($admin)],config('status.ok'));
        }catch(Exception $e){
            return response(['errors'=>$e->getMessage()],config('status.badRequest'));
        }
    }
    function changePassword(Request $request){
        if(!$request->has(['new-password','old-password'])){
            return response('',config('status.badRequest'));
        }
        [
            'new-password'=>$newPassword,
            'old-password'=>$oldPassword,
        ] = $request->only(['new-password','old-password']);
        $admin=$request->user();
        if(!Hash::check($oldPassword, $admin->password)){
            return response('',config('status.forbidden'));
        }
        $admin->password=Hash::make($newPassword);
        $admin->save();
        return response(['token'=>$this->issueToken($admin)],config('status.ok'));
    }
    function logout(Request $request){
        $admin=$request->user();
        $admin->tokens()->delete();
        return response('',config('status.ok'));
    }
    private function issueToken(Admin $admin){
        $admin->tokens()->delete();
        return $admin->createToken('admin-token',['admin'])->plainTextToken;
    }
}
