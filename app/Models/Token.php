<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
	public $timestamps = false;
	public $incrementing = false;
	protected $table = 'tokens';
}
