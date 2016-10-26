<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
   /**
     * Overriding response function according to custom api
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $api_controller = new ApiController();

        return $api_controller->respondWithErrors($errors);

    }
}
 
