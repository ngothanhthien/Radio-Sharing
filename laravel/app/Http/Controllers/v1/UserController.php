<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSocialRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function login(Request $request)
    {
        [
            'email' => $email,
            'password' => $password
        ] = $request->only(['email', 'password']);
        try{
            $user=User::where('email', $email)->firstOrFail();
            if(!Hash::check($password, $user->password)){
                throw new Exception('Password is incorrect');
            }
            return response([
                'token'=>$this->issueUserToken($user),
                'user'=>new UserResource($user),
            ],config('status.ok'));
        }catch(Exception $e){
            return response($e->getMessage(),config('status.badRequest'));
        }
    }
    public function loginSocial($social){
        $socialData=Socialite::driver($social)->stateless()->user();
        try{
            $user=User::where('email', $socialData->email)->firstOrFail();
            return response([
                'token'=>$this->issueUserToken($user),
                'user'=>new UserResource($user),
            ],config('status.ok'));
        }catch(Exception $e){
            return response($e->getMessage(),config('status.notFound'));
        }
    }
    public function register(UserRegisterRequest $request){
        $form=$request->only(['email', 'password', 'username']);
        $form['password']=Hash::make($form['password']);
        try{
            $user=User::create($form);
            return response([
                'token'=>$this->issueUserToken($user),
                'user'=>new UserResource($user),
            ],config('status.ok'));
        }catch(Exception $e){
            return response($e->getMessage(),config('status.badRequest'));
        }
    }
    public function registerSocial($social,RegisterSocialRequest $request){
        $form=$request->only(['email', 'password', 'username']);
        $socialData=Socialite::driver($social)->stateless()->user();
        if(strcmp($socialData->getEmail(),$form['email'])!=0){
            return response(['errors'=>'Invalid Token',config('status.badRequest')]);
        }
        $form['password']=Hash::make($form['password']);
        $form['email_verified_at']=Carbon::now();
        try{
            $user=User::create($form);
            return response([
                'token'=>$this->issueUserToken($user),
                'user'=>new UserResource($user),
            ]);
        }catch(Exception $e) {
            return response($e->getMessage(),config('status.badRequest'));
        }
    }
    public function logout(Request $request){
        $user=$request->user();
        $user->tokens()->delete();
        return response(config('status.ok '));
    }
    public function getSocialLink($social){
        return response(
            ['link' =>Socialite::driver($social)->stateless()->redirect()->getTargetUrl()],
            config('status.ok')
        );
    }
    public function ban(User $user){
        try{
            $user->state=User::STATE_BANNED;
            $user->save();
            return response(config('status.ok'));
        }catch(Exception $e){
            return response($e->getMessage(),config('status.badRequest'));
        }
    }
    public function unBan(User $user){
        try{
            $user->state=User::STATE_ACTIVE;
            $user->save();
            return response(config('status.ok'));
        }catch(Exception $e){
            return response($e->getMessage(),config('status.badRequest'));
        }
    }
    public function changePassword(Request $request){
        if(!$request->has(['new-password','old-password'])){
            return response('',config('status.badRequest'));
        }
        [
            'new-password'=>$newPassword,
            'old-password'=>$oldPassword,
        ] = $request->only(['new-password','old-password']);
        $user=$request->user();
        if(!Hash::check($oldPassword, $user->password)){
            return response('',config('status.forbidden'));
        }
        $user->password=Hash::make($newPassword);
        $user->save();
        return response(['token'=>$this->issueToken($user)],config('status.ok'));
    }
    private function issueUserToken(User $user){
        $user->tokens()->delete();
        return $user->createToken('user-token',['user'])->plainTextToken;
    }
}
