<?php

namespace Domain\User;

use App\User; 

use Illuminate\Http\Response;
class UserRepository
{
  
    public function getByUsernameOrEmail($username){
 
      return User::where('username',$username) 
                  ->orWhere('email', $username)->first();
    }

    public function match_password(User $user , $password){
        return $user->match_password($user , $password);
    }
    
    public function getById($user_id,$isActive=false){
      $user = User::query();
      if ($isActive=true)
      {
        $user->where('active',1);
      }
      
      $user->where('id',$user_id);
      return $user->first();
    
    }
  }
 