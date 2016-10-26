<?php 

namespace Domain\LoginAttempt;
  
use App\LoginAttempt;
use Domain\LoginAttempt\LoginAttemptRepository;  

class LoginAttemptService {
  
    private $loginAttempt_repository; 

    public function __construct(){ 

        //Create instance of LoginAttemptRepository 
        $this->loginAttempt_repository = new LoginAttemptRepository( );
 
    }

    
    public function getByUserId($user_id){
 
        //Find loginAttempt data
        $data = $this->loginAttempt_repository->getByUserId($user_id);
 
        return $data; 
        
    }

    public function make($data){
        //Create LoginAttempt object
        $loginAttempt = $this->hydrate($data);
        return $this->create($loginAttempt);
    }

    public function create(LoginAttempt $loginAttempt){
        return $this->loginAttempt_repository->create($loginAttempt);
    }
    
    public function delete($user_id){
        $this->loginAttempt_repository->delete($user_id);
    }
   
    //HELPERS

     
    public function hydrateCollection($data){
        return array_map([$this, 'hydrate'], $data);
    }
  
    public function hydrate($data){
        //Create a new instance of LoginAttempt
        $loginAttempt=new LoginAttempt();
 
        if($data){
            //Setting attributes to this class
            if(isset($data['loginAttempt_id'])){
                $loginAttempt['id']=$data['loginAttempt_id'];
            } 

            if(isset($data['user_id'])){
                $loginAttempt['user_id']=$data['user_id'];
            } 

            if(isset($data['device'])){
                $loginAttempt['device']=$data['device'];
            } 

            if(isset($data['status'])){
                $loginAttempt['status']=$data['status'];
            } 

            if(isset($data['status_reason'])){
                $loginAttempt['status_reason']=$data['status_reason'];
            } 
           
        }
        //Return hydrated instance of LoginAttempt class
        return $loginAttempt;
    }
 
}
     
 