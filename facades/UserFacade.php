<?php  

namespace facades;
 
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

/**
 * Class DistrictFacade
 * @package \Facades
 */
class UserFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Domain\User\UserService';
    } 

} 