<?php

namespace Domain\UserRole;

use App\User_role; 

use Illuminate\Http\Response;
class UserRoleRepository
{
  
      
    
    public function getById($role_id){
    
      return User_role::find($role_id);
       
    }
    
    public function create(User_role $User_role){
    
      return User_role::create($User_role->toArray());
    
    }

    public function update(User_role $User_role,$data){ 
      
      $User_role->update($data);
      return $this->getById($User_role->id);
    
    }
    
}