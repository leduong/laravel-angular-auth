<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Token;

class UserController extends Controller
{
		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index(Request $req)
		{
			$q     = $req->query('q', null);
			$limit = $req->query('limit', 20);
			$sort  = $req->query('sort', 'id');
			$order = $req->query('order', 'asc');

			$users = DB::table('users')->orderBy($sort, $order)->paginate($limit);
			return response()->json($users);
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
						return $this->error('Email is exist!', 400);
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
					$user->latlng;
					return response()->json($user->toArray());
				}
				else
					return $this->error('User is not found!', 404);
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
					$user->save();
					return response()->json($user->toArray());
				}
				else
					return $this->error('User is not found!', 404);
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
					return $this->error('User is not found!', 404);
		}
}
