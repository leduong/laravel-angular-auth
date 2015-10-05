<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Token;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration req.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $req)
    {
        $user = null;
        $user = User::where('email',$req->input('email'))->first();

        if (!$user){
            $user = User::create([
                'name'     => $req->input('name'),
                'email'    => $req->input('email'),
                'password' => bcrypt($req->input('password')),
            ]);

            $token             = new Token();
            $token->user_id    = $user->id;
            $token->id         = md5(time() + $user->email + $user->password);
            $token->expired_at = date('Y-m-d H:i:s', strtotime("+ 24 hours"));
            $token->save();
            $user->token = $token->id;
            return response()->json($user->toArray(), 201);
        }
        else
            return response()->json(['status'=>'error', 'error'=>['message' => 'Email is exist!']], 403);
    }

    public function login(Request $req)
    {
        $user = null;
        $user = User::where('email',$req->input('email'))->first();

        if(!$user)
            return $this->error('Invalid credentials',403);

        if(!Hash::check($req->input('password'), $user->password))
            return $this->error('Invalid credentials',403);
        
        $token = Token::where('user_id',$user->id)->first();
        if(!$token)
        {
            $token             = new Token();
            $token->user_id    = $user->id;
            $token->id         = md5(time() + $user->email + $user->password);
            $token->expired_at = date('Y-m-d H:i:s', strtotime("+ 24 hours"));
            $token->save();
        }
        $user->token = $token->id;
        return response()->json($user->toArray());
    }
}
