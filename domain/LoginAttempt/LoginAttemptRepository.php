<?php

namespace Domain\LoginAttempt;

use App\User; 
use App\LoginAttempt;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Response;
class LoginAttemptRepository
{
   
   
    public function create(LoginAttempt $loginAttempt){
        return LoginAttempt::create($loginAttempt->toArray());
    } 

    public function getByUserId($user_id,$time_diff=false,$status = 0){ 
 
      return LoginAttempt::where('user_id',$user_id)  
              ->where('status',$status)
              ->orderBy('id', 'asc')
              ->get();
    }

    public function delete($user_id){ 
     LoginAttempt::where('user_id',$user_id)
                  ->where('status',0)
                  ->delete();
    }
 
}