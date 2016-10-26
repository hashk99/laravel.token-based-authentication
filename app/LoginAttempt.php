<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{

	//add the table name schools in our case
	protected $table = 'login_attempts'; 

	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = [
		'user_id',
		'device',
		'status',
		'status_reason',
		'created_at',
		'updated_at', 
	]; 

	// column name that will not be shown 
	/*protected $hidden = ['type'];*/
}