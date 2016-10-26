<?php
 
namespace domain\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception {

    public function __construct($message = 'No User Found', $code = 3, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 