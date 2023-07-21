<?php

namespace App\Http\Controllers;
use App\Modules\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __construct(protected UserService $userService)
    {
    }

    public function index()
    {
       return  $this->userService->getAll();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
      if(in_array($request->all()['role'],['seller','customer','employee']))
      {
           $validator=$this->validateData($request);
           if ($validator->fails()) {
            return response()->json($validator->errors());
          }
           return $this->userService->create($validator->validated());
               
      }
      return response()->json(["role"=>"The selected role is invalid"]);     
    }
 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->userService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       if(in_array($request->all()['role'],['admin','seller','customer','employee']))
      {
           $validator=$this->validateData($request);
           if ($validator->fails()) {
            return response()->json($validator->errors());
          }
           return $this->userService->update($id,$validator->validated());
               
      }
      return response()->json(["role"=>"The selected role is invalid"]);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      return $this->userService->delete($id);
    }

    public function validateData(Request $request)
    {
      if($request->all()['role']=='employee')
      {
          $validator= Validator::make($request->all(), [
          'email' => 'required|email|unique:users|max:800',
          'password' => 'required|max:8|min:5|confirmed|unique:users',
          'name'=>'required|max:40',
          'role'=>'required|max:20',
          'profession_id'=>'required|exists:professions,id|integer',
          ]);
      }
      else
      {
        {
          $validator= Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:800',
            'password' => 'required|max:8|min:5|confirmed|unique:users',
            'name'=>'required|max:40',
            'role'=>'required|max:20',
            'description'=>'required|max:800|string',
            'type'=>['required','max:50',Rule::in(['moneyGainer','moneySpender'])]

            ]
          );
           
       }    
      }
      return $validator;
       
    }
}
