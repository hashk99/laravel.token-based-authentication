<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Facades\AuthFacade;
use Facades\UserFacade; 
use Facades\ApiFacade;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     
        $token_id=ApiFacade::check_auth_token($request);

        if ($token_id == false) {
          
            return response()->json(APIFacade::tokenNotFound());
        }
     
        $user=AuthFacade::check($token_id);
            
        if($user != false ){ 
            //AuthFacade::update_session_lifetime($token_id);
            return response()->json(APIFacade::successData(['user'=>UserFacade::hydrate($user)])); 
        }else{
            return response()->json(APIFacade::errorData('token not valid')); 
        } 
      
    }
  
    /**
     * LOGIN USER WITH PASSWORD
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
         
        $token_id=AuthFacade::make($request);
        if($token_id) 
            return response()->json(APIFacade::successData(['token_id'=>$token_id]));
        else
            return response()->json(APIFacade::errorData('Error'));
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $token_id=ApiFacade::check_auth_token($request);

        if ($token_id == false) {  
            return response()->json(APIFacade::errorData('Need the auth token'));
        }
  
        $user=AuthFacade::check($token_id);
            
        if($user != false ){
            AuthFacade::destroy($token_id);
            return response()->json(APIFacade::successData(['auth_token'=>'token deleted'])); 
        }else{
            return response()->json(APIFacade::errorData('token not valid')); 
        } 
    }
}
