<?php 

namespace Domain\User;
  
use Facades\UserFacade;
use Facades\UserRoleFacade;
use App\User;
use Domain\User\UserRepository;
use Domain\User\UserTransformer; 
use Domain\User\Exceptions\UserNotFoundException; 

class UserService {

    const INTEGRITY_CONSTRAINT_VIOLATION_ERROR_CODE = 23000;
 
    private $user_repository;
    protected $user_transformer;

    public function __construct(){ 

        //Create instance of DistrictRepository 
        $this->user_repository = new UserRepository( );

        //Create instance of DistrictTransformer
        $this->user_transformer = new UserTransformer();
    } 

    public function match_password(User $user , $password){
        $a=$this->user_repository->match_password($user , $password);
        return $a;
    }

    public function getByUsernameOrEmail($username){
 
        //Find user data
        $data = $this->user_repository->getByUsernameOrEmail($username);

        if($data){
            $return_data=$data->toArray(); 
            return $data;
        } else { echo 'error';
           // throw new DistrictsNotFoundException();
        }
    }
  
    //HELPERS
    public function transformCollection($data){
        return $this->user_transformer->transformCollection($data);
    }

    public function transform($data){
        return $this->user_transformer->transform($data);
    }

    public function hydrateCollection($data){
        return array_map([$this, 'hydrate'], $data);
    }

    /**
     * Return filled User object with given data
     * @param $data
     * @return User
     */
    public function hydrate($data){
        //Create a new instance of User
        $user=new User();
 
        if($data){
            //Setting attributes to this class
            if(isset($data['user_id'])){
                $user['id']=$data['user_id'];
            }else if (isset($data['id'])){
                $user['user_id']=$data['id']; 
            }

            if(isset($data['full_name'])){
                $user['user_full_name']=$data['full_name'];
            }else if (isset($data['user_full_name'])){
                $user['full_name']=$data['user_full_name']; 
            }

            if(isset($data['username'])){
                $user['username']=$data['username'];
            } 

            if(isset($data['email'])){
                $user['user_email']=$data['email'];
            }else if (isset($data['user_email'])){
                $user['email']=$data['user_email']; 
            }

         /*   if(isset($data['password'])){
                $user['user_password']=$data['password'];
            }else if (isset($data['user_password'])){
                $user['password']=$data['user_password']; 
            }*/

            if(isset($data['preferred_name'])){
                $user['user_preferred_name']=$data['preferred_name'];
            }else if (isset($data['user_preferred_name'])){
                $user['preferred_name']=$data['user_preferred_name']; 
            }

            if(isset($data['user_role_id'])){
                $user['user_role']=UserRoleFacade::transform(UserRoleFacade::getById($data['user_role_id']) );
                
              //  $user['user_role']=$data->userRole();


            }else if (isset($data['user_new_role_id'])){
                $user['user_role_id']=$data['user_new_role_id']; 
            }

            if(isset($data['salutation'])){
                $user['user_salutation']=$data['salutation'];
            }else if (isset($data['user_salutation'])){
                $user['salutation']=$data['user_salutation']; 
            }

            if(isset($data['gender'])){
                $user['user_gender']=$data['gender'];
            }else if (isset($data['user_gender'])){
                $user['gender']=$data['user_gender']; 
            }

            if(isset($data['address'])){
                $user['user_address']=$data['address'];
            }else if (isset($data['user_address'])){
                $user['address']=$data['user_address']; 
            }

            if(isset($data['profile_img_id'])){
                $user['user_profile_img_id']=$data['profile_img_id'];
            }else if (isset($data['user_profile_img_id'])){
                $user['profile_img_id']=$data['user_profile_img_id']; 
            }

            if(isset($data['cover_img_id'])){
                $user['user_cover_img_id']=$data['cover_img_id'];
            }else if (isset($data['user_cover_img_id'])){
                $user['cover_img_id']=$data['user_cover_img_id']; 
            }

            if(isset($data['added_by'])){
                $user['user_added_by']=$data['added_by'];
            }else if (isset($data['user_added_by'])){
                $user['added_by']=$data['user_added_by']; 
            }

            if(isset($data['active'])){
                $user['user_active']=$data['active'];
            }else if (isset($data['user_active'])){
                $user['active']=$data['user_active']; 
            }
            
            if(isset($data['status_reason'])){
                $user['user_status_reason']=$data['status_reason'];
            }else if (isset($data['user_status_reason'])){
                $user['status_reason']=$data['user_status_reason']; 
            }
            
            if(isset($data['created_at'])){
                $user['user_created_at']=$data['created_at'];
            }else if (isset($data['user_created_at'])){
                $user['created_at']=$data['user_created_at']; 
            }
            
            if(isset($data['updated_at'])){
                $user['user_updated_at']=$data['updated_at'];
            }else if (isset($data['user_updated_at'])){
                $user['updated_at']=$data['user_updated_at']; 
            }
           
        }
        //Return hydrated instance of District class
        return $user;
    }
   
    public function getById($user_id){
 
        //Find user data
        $data = $this->user_repository->getById($user_id);

        if($data){
            $return_data=$data->toArray(); 
            return $data;
        } else {
            throw new UserNotFoundException();
        }
    }

    public function make(array $data){
        //Create District object
        $user = $this->hydrate($data);

        try{
            //Save object  
            $insert_data = ($this->user_repository->getById(($this->create($user))->id))->toArray() ;

        }
        catch(QueryException $ex){ 
            if($ex->getCode() == self::INTEGRITY_CONSTRAINT_VIOLATION_ERROR_CODE){
                throw new DistrictAllreadyExistException();
            }
        }
        $return_data['new_user']=$this->user_transformer->transform($insert_data);
        return $return_data;
    }

    public function create(District $user){
        return $this->user_repository->create($user);
    }
    
    public function update(District $user, array $data){ 
        
        //CONVERT GIVEN DATA TO DISTRICT OBJECT
        $data=$this->hydrate($data); 

        //NOW UPDATE THE TB ROW
        $return_data=$this->user_repository->update($user,$data->toArray());

        return $this->user_transformer->transform($return_data);

    }
  
 
}
    