<?php 

namespace Infrastructure;
 
use Illuminate\Database\QueryException;
use App\User;
use Hash;
use Config;
use App\Session;
use Facades\AuthFacade;
use Facades\UserFacade;
use Facades\LoginAttemptFacade;
use Facades\ApiFacade;
use Domain\User\UserRepository;
use Domain\User\UserTransformer;
use Facades\SessionHandlerFacade;
use Carbon\Carbon;

class AuthService {

    const INTEGRITY_CONSTRAINT_VIOLATION_ERROR_CODE = 23000;
    const SUCCESS_PASSWORD_STATUS_REASON = 'Success Username and password matching';
    const FAIL_USERNAME_STATUS_REASON = 'Failed username matching';
    const FAIL_PASSWORD_STATUS_REASON = 'Failed password matching';

    const FIRST_TIME_BLOCK = 10;
    const SECOND_TIME_BLOCK = 30;
    const THIRD_TIME_BLOCK = 60;

    const FIRST_TIME_COUNT = 10;
    const SECOND_TIME_COUNT = 20;
    const THIRD_TIME_COUNT = 30;

    const FIRST_TIME_CHECK = 10;
    const SECOND_TIME_CHECK = 30;
    const THIRD_TIME_CHECK = 60;

    public function __construct(){
  
         
    }
    
    /* 
     *CHECK LOGIN DATA AND CREATE / RETURN A TOKEN
     *LOGIN DETAILS
     */
    public function make($request){
         
        $data=$request->all();
        $requesting_user = $this->hydrate($data); 
         
         
        $user =  UserFacade::getByUsernameOrEmail($requesting_user->username);
        if($user){ 
            //username matches
            
            $attemptPass=$this->checkLoginAttempts(LoginAttemptFacade::getByUserId($user->id));
            if($attemptPass != 200){
                if($attemptPass == 1){
                    echo 'first step block';
                    //try again in 1 time
                }else if($attemptPass == 2){
                    echo 'second step block';
                    //try again in 2 time
                }else if($attemptPass == 3){
                    echo 'third step block';
                    //reset the password
                }
                return false;
            }


            if (UserFacade::match_password($user,$data['password'])) {
                //password matching
                
                $token_id=$this->genarate_token_id(); 
                  
                $make_arr=array('token_id'=>$token_id,'user_id'=>$user->id);
                $this->create_session($make_arr);
                
                LoginAttemptFacade::delete($user->id);
                LoginAttemptFacade::make($this->loginAttemptsArray($request,$user,1,self::SUCCESS_PASSWORD_STATUS_REASON));

                return $token_id;
             }else{
                LoginAttemptFacade::make($this->loginAttemptsArray($request,$user,0,self::FAIL_PASSWORD_STATUS_REASON));
                return false;
            }

        } else{

            LoginAttemptFacade::make($this->loginAttemptsArray($request,false,0,self::FAIL_USERNAME_STATUS_REASON));
            return false;
        }
        
    }

    public function checkLoginAttempts($history){
         $count=sizeof($history);
            if($count < 10){
                //echo 'below 10'; 
                return 200;
            }else if($count == self::FIRST_TIME_COUNT){
                //echo 'equal to 10 Should block now';
                
                if($history[self::FIRST_TIME_COUNT-1]->created_at >= Carbon::now()->subMinutes(self::FIRST_TIME_CHECK) ){
                    //echo 'should still blocked';
                    return 1;
                }else {
                    //echo 'can try again';
                    return 200;
                }

            }
            else if($count > self::FIRST_TIME_COUNT && $count < self::SECOND_TIME_COUNT){
                
                 echo 'between 10 and 20';
                return 200;
            }else if($count  == self::SECOND_TIME_COUNT){
                //echo 'equal to 20';

             if($history[self::SECOND_TIME_COUNT-1]->created_at >= Carbon::now()->subMinutes(self::SECOND_TIME_CHECK) ){
                  //  echo 'should still blocked';
                    return 2;
                }else {
                    //echo 'can try again';
                    return 200;
                }

            }else if($count >self::SECOND_TIME_COUNT AND $count < self::THIRD_TIME_COUNT){
                //echo 'between 20 and 30';
                return 200;

            }else if($count >= self::THIRD_TIME_COUNT){
                //echo 'after 30';
                return 3;
            }
    }
    /*public function */

    /*
     *DESTROY A SESSION TOKEN
     *@PARAM - TOKEN ID
     */
    public function destroy ($token_id){
        SessionHandlerFacade::delete_session($token_id);
    }

    /*
     *CHECK AND VERIFY A TOKEN ID
     *@PARAM - TOKEN ID
     */
    public function check($token_id){ 
        $lifetime=$this->session_lifetime();  
        $user_id=SessionHandlerFacade::read($token_id,$lifetime);
         
        if(!empty($user_id)){
            //get active user
            $this->update_session_lifetime($token_id);
           return UserFacade::getById($user_id,true) ;
        }else{
            return false;
        }
    }
    public function checkByRequest($request){
        $have_toen=ApiFacade::check_auth_token($request);
        if($have_toen == false)
            return false;
        return $this->check($have_toen);
    }
    /*
     * UPDATE TOKEN SESSION LIFETIME ON DATABASE SESSSION TABLE
     */
    public function update_session_lifetime($token_id){
        SessionHandlerFacade::update_session_lifetime($token_id);
    }
    public function session_lifetime(){
        return Config::get('session.lifetime');
    }
    public function genarate_token_id(){
        return SessionHandlerFacade::generateSessionId();
    }

    public function create_session($data){
        SessionHandlerFacade::write($data);
    }

    //HELPERS
    public function hydrateCollection($data){
        return array_map([$this, 'hydrate'], $data);
    }

    /**
     * Return filled District object with given data
     * @param $data
     * @return District
     */
    public function hydrate($data){
        //Create a new instance of authorized user
        $user=new User();
 
        if($data){
            //Setting attributes to this class
            if(isset($data['username'])){
                $user['username']=$data['username'];
            }else if (isset($data['password'])){
                $user['password']=$data['password']; 
            }

            
           
        }
        //Return hydrated instance of District class
        return $user;
    }

    public function loginAttemptsArray ($request,$user,$status,$status_reason){
        
        return array(
            'user_id' => $user? $user->id : null,
            'device' => $request->header('user-agent'),
            'status'=> $status,
            'status_reason' => $status_reason,
  
        );
    }
 
}