<?php 
namespace infrastructure;
 
use App\Session; 
use Illuminate\Support\Arr;
use Illuminate\Support\Str; 
use DB;
 
class SessionHandler {
 
    public function __construct()
    { 
    }

    public function read($token_id,$lifetime) {  
        
        $user=Session::where('id', '=', $token_id)
                        ->where('last_activity', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$lifetime.' MINUTE)'))
                        ->first();
        if($user)
            return $user->payload;
        else
            return false;

    }

    public function write($data) { 
         
        return Session::create([
                        'id'=>$data['token_id'],
                        'payload'=>$data['user_id'], 
                        ]
            );
    }
    public function generateSessionId()
    {
        return sha1(uniqid('', true).Str::random(25).microtime(true));
    }

    public function update_session_lifetime($token_id){
        Session::where('id', $token_id)
               ->update(['last_activity' => DB::raw('CURRENT_TIMESTAMP')]);
    }

    public function delete_session($auth_token){
        Session::where('id', $auth_token)
                 ->delete();
    } 
}