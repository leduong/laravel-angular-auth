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
		$token = $request->header('Authorization');

		if (empty($token)){
			if( $request->has('token') )
				$token = $request->input('token');
			else
				return response()->json(['message' => 'Authorization is required.'], 401);
		}

		$token = TokenVerfier::find($token);
		if (!$token){
			return response()->json(['message' => 'Authorization is missing'], 401);
		}

		$now = time();
		$expired_at = strtotime($token->expired_at);
		if ($now > $expired_at) {
			return response()->json(['message' => 'Authorization has expired.'], 401);
		}

		$user = User::find($token->user_id);
		
		$this->auth->setUser($user);
		return $next($request);
	}

}