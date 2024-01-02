<?php

namespace App\Http\Controllers;
use App\Modules\GoodDetails\GoodDetailService;
use App\Modules\Goods\GoodService;
use App\Modules\Users\UserService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function __construct(protected UserService $userService,protected GoodService $goodService,protected GoodDetailService $goodDetailService)
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

  public function searchAll(String $inputText)
  {
    return $this->userService->searchAll($inputText);  
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

  public function singleItem(String $table,String $id)
  {
    if($table=='user')
    {
      try{
      return $this->userService->getById($id);
      }catch(Exception $e)
      {
       return 'No such item'; 
      }
    }
    else if($table=='good')
    {
       $result= $this->goodService->getById($id);
       if($result)
       {
        $result['name']=$result['item_code'];
        unset($result['item_code']);
       }

       return $result;

    }
    else if($table=='brand'|| $table=='category'|| $table=='modal' )
    {
      
       $result=  $this->goodDetailService->getById($table,$id);
    }

  }

}
