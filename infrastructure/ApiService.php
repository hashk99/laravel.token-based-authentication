<?php 

namespace Infrastructure;
 
class ApiService {
  public function __construct(){
 
 
    }
    const AUTH_TOKEN_NAME = 'auth-token';

    const DEF_SUCCESS_STATUS = "success";  
    const DEF_ERROR_STATUS = "error";  

    public function successData($data){
		 return array('status' => self::DEF_SUCCESS_STATUS, 'data' => $data ); 
    }

    public function errorData($data){
		 return array('status' => self::DEF_ERROR_STATUS, 'data' => $data ); 
    }
    
    public function tokenNotFound( $message = "Need the auth token" ){
        return $this->errorData($message);
    }

    public function check_auth_token($request){
        if( $request->header(self::AUTH_TOKEN_NAME) == null){
           return false;
        }
        return $request->header(self::AUTH_TOKEN_NAME);
    }

}