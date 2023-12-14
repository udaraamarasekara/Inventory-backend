<?php

namespace App\Http\Controllers;
use App\Modules\Users\UserService;
use Illuminate\Support\Facades\Validator;
use Auth;
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
    $validator=$this->validateData($request);
    if ($validator->fails()) {
     return response()->json(['error'=>$validator->errors()]);
    }
    $response=[]; 
    if($response['user'] = $this->userService->login($request->all()))
    {
      $response['auth'] = $request->session()->regenerate();
    }

    return $response;      
      //
  }

  public function logout(Request $request)
  {
    return $request->session()->invalidate();
  } 

  public function invalidRequest(Request $request)
  {
    return response('Invalid request!');
  } 

  public function validateData(Request $request)
  {
     return Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|max:18|min:5',
      'remember'=>'boolean'
      ]);
  }

}
