<?php  

namespace facades;
 
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

/**
 * Class LoginAttemptFacade
 * @package \Facades
 */
class LoginAttemptFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Domain\LoginAttempt\LoginAttemptService';
    } 

} 