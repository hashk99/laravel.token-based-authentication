<?php 

namespace Domain\UserRole;
  
use Facades\UserRoleFacade;
use App\User_role;
use Domain\UserRole\UserRoleRepository;
use Domain\UserRole\UserRoleTransformer; 

class UserRoleService {
  
    private $user_role_repository;
    protected $user_role_transformer;

    public function __construct(){ 
 
        $this->user_role_repository = new UserRoleRepository( );
 
        $this->user_role_transformer = new UserRoleTransformer();
    }
  
  
    public function getByUsernameOrEmail($username){
 
        //Find user data
        $data = $this->user_repository->getByUsernameOrEmail($username);

        if($data){
            $return_data=$this->hydrate($data->toArray()); 
            return $data;
        } else { echo 'error';
           // throw new DistrictsNotFoundException();
        }
    }
  
    //HELPERS
    public function transformCollection($data){
        return $this->user_role_transformer->transformCollection($data);
    }

    public function transform($data){
        return $this->user_role_transformer->transform($data);
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
        //Create a new instance of District
        $user_role=new User_role();
 
        if($data){
            //Setting attributes to this class
            if(isset($data['user_role_id'])){
                $user_role['id']=$data['user_role_id'];
            }else if (isset($data['id'])){
                $user_role['user_role_id']=$data['id']; 
            }

            if(isset($data['role'])){
                $user_role['user_role_text']=$data['role'];
            }else if (isset($data['user_role_text'])){
                $user_role['role']=$data['user_role_text']; 
            } 
        }
        //Return hydrated instance of District class
        return $user_role;
    }
   
    public function getById($user_role_id){
 
        //Find user data
        $data = $this->user_role_repository->getById($user_role_id);

        if($data){
            $return_data=$this->hydrate($data->toArray()); 
            return $data;
        } else {
           // throw new DistrictsNotFoundException();
        }
    }

    public function make(array $data){
        //Create District object
        $user_role = $this->hydrate($data);

        try{
            //Save object  
            $insert_data = ($this->user_role_repository->getById(($this->create($user_role))->id))->toArray() ;

        }
        catch(QueryException $ex){ 
            if($ex->getCode() == self::INTEGRITY_CONSTRAINT_VIOLATION_ERROR_CODE){
                throw new DistrictAllreadyExistException();
            }
        }
        $return_data['new_role']=$this->user_role_transformer->transform($insert_data);
        return $return_data;
    }

    public function create(User_role $user_role){
        return $this->user_role_repository->create($user_role);
    }
    
    public function update(User_role $user_role, array $data){ 
        
        //CONVERT GIVEN DATA TO DISTRICT OBJECT
        $data=$this->hydrate($data); 

        //NOW UPDATE THE TB ROW
        $return_data=$this->user_role_repository->update($user_role,$data->toArray());

        return $this->user_role_transformer->transform($return_data);

    }
  
 
}
     
 