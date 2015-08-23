<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
	public function photos()
	{
		return $this->hasMany('App\Photo');
	}

	public function topselected()
	{
		return $this->hasMany('App\Photo')->orderBy('votes', 'desc')->take(5);
	}

	public function subscriber()
	{
		return $this->belongsTo('App\Subscriber');
	}

	public function url()
	{
		return url($this->subscriber->username . '/' . $this->id);
	}
}
