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
         return $this->userService->login($request->all());
      }
          
      //
  }

  public function logout()
  {
    return $this->userService->logout();
  } 

  public function validateData(Request $request)
  {
     return Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|max:8|min:5',
      ])->validate();
  }

}
