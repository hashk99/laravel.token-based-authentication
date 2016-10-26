<?php

namespace Domain\User; 

use Facades\UserFacade;
use Facades\UserRoleFacade;
use Infrastructure\Transformer;

class UserTransformer extends Transformer{

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
	public function transform($user)
	{	  
		return [
			'user_id' => $user['id'],
			'user_full_name' => $user['full_name'],
			'user_username' => $user['username'],
			'user_email' => $user['email'], 
			'user_preferred_name' => $user['preferred_name'],
			'user_role' => UserRoleFacade::transform(UserRoleFacade::getById($user['user_role_id']) ),
			'user_salutation' => $user['salutation'],
			'user_gender' => $user['gender'],
			'user_address' => $user['address'],
			'user_profile_img_id' => $user['profile_img_id'],
			'user_cover_img_id' => $user['cover_img_id'],
			'user_added_by' => $user['added_by'],
			'user_active' => $user['active'],
			'user_status_reason' => $user['status_reason'],
			'user_created_at' => $user['created_at'],
			'user_updated_at' => $user['updated_at'],
			];
		
	}
	 

}
