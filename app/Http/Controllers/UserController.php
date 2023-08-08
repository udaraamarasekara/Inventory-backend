<?php

namespace App\Http\Controllers;
use App\Modules\Users\UserService;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function __construct(protected UserService $userService)
   {
   }
  public function login(Request $request)
  {
    $validated=$this->validateData($request);
    if($validated)
      { 
        $response=[]; 
        $response['auth'] = $request->session()->regenerate();
        $response['user'] = $this->userService->login($request->all());
         return $response;
      }
          
      //
  }

  public function logout(Request $request)
  {
    return $request->session()->invalidate();
  } 

  public function validateData(Request $request)
  {
     return Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|max:8|min:5',
      ])->validate();
  }

}
