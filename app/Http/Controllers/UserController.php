<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

class UserController extends Controller
{
		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index()
		{
				return response()->json(User::all());
		}

		/**
		 * Show the form for creating a new resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function create()
		{
				//
		}

		/**
		 * Store a newly created resource in storage.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @return \Illuminate\Http\Response
		 */
		public function store(Request $req)
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

		/**
		 * Display the specified resource.
		 *
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
		public function show($id)
		{
				$user = null;
				$user = User::find($id);

				if ($user){
					return response()->json($user->toArray());
				}
				else
					return response()->json(['status'=>'error', 'error'=>['message' => 'User is not found!']], 404);
		}

		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
		public function edit($id)
		{
				//
		}

		/**
		 * Update the specified resource in storage.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
		public function update(Request $req, $id)
		{
				//
				$user = null;
				$user = User::find($id);

				if ($user){
					$user->name  = $req->input('name');
					$user->email = $req->input('email');
							
					// check blank password for not change.
					if ($password = $req->input('password')){
						if(Hash::check($req->input('password'), $password))
							return $this->error('New password not same current password', 403);
						$user->password = bcrypt($req->input('password'));
					}
					$save->save();
					return response()->json($user->toArray());
				}
				else
					return response()->json(['status'=>'error', 'error'=>['message' => 'User is not found!']], 404);
		}

		/**
		 * Remove the specified resource from storage.
		 *
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
		public function destroy($id)
		{
				$user = null;
				$user = User::find($id);

				if ($user){
					$user->delete();
					return response()->json(array(), 204);
				}
				else
					return response()->json(['status'=>'error', 'error'=>['message' => 'User is not found!']], 404);
		}
}
