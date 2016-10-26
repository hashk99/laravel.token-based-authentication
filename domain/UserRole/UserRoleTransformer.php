<?php

namespace Domain\UserRole; 

use Facades\UserRoleFacade;
use Infrastructure\Transformer;

class UserRoleTransformer extends Transformer{

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
		
	/**
	 * Transform a user
	 *
	 * @param  int  $user
	 * @return array
	 */
	public function transform($user_role)
	{	  
		return [
			'user_role_id' => $user_role['id'],
			'user_role_text' => $user_role['role'], 
			];
		
	}
	 

}
