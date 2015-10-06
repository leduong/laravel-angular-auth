<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
	protected $table = 'users_location';

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}