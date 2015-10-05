<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\User;
use App\Models\Token AS TokenVerfier;
class Token {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth){
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        //return $next($request);
        $token = $request->header('Token');

        if (empty($token)){
            if( $request->has('token') )
                $token = $request->input('token');
            else
                return response()->json(['status'=>'error', 'error'=>['message' => 'Please Login to continue!']], 403);
        }

        $token = TokenVerfier::where('token', '=', $token)->first();
        if (!$token){
            return response()->json(['status'=>'error', 'error'=>['message' => 'token is missing']], 403);
        }

        $user = User::find($token->user_id);
        
        $this->auth->setUser($user);
        return $next($request);
    }

}
