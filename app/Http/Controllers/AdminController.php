<?php

namespace App\Http\Controllers;
use App\Models\Profession;
use App\Modules\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
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
      $this->authorize('view',User::class);
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
      $this->authorize('create',User::class);
      $validator=$this->validateData($request);
      if ($validator->fails()) {
      return response()->json($validator->errors());
        }
      return $this->userService->create($request->all());
    }
 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view',User::class);
        return $this->userService->getById($id);
    }

    public function newProfession(Request $request){
       $this->authorize('create',Profession::class);
       $request->validate([
        'post'=>'required|max:800|string',
        'agreement'=>'required|max:800|string',
        'basic_salary'=>'required|numeric',
        'abilities'=>'required|array'
       ]);
       return $this->userService->newProfession($request->all());
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
      $this->authorize('edit',User::class);
      
           $validator=$this->validateData($request);
           if ($validator->fails()) {
            return response()->json($validator->errors());
          }
          
           return $this->userService->update($id,$request->all());
               
      
    }

    public function getProfessions()
    {
      return $this->userService->getProfessions();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $this->authorize('delete',User::class); 
      return $this->userService->delete($id);
    }

    public function validateData(Request $request)
    {
      if($request['role']=='employee')
      {
          $validator= Validator::make($request->all(), [
          'email' => 'required|email|unique:users|max:800',
          'password' => 'required|max:18|min:5|confirmed|unique:users',
          'name'=>'required|max:40',
          'role'=>['required','max:50',Rule::in([0,1,2])],
          'profession_id'=>'required|exists:professions,id|integer',
          ]);
      }
      else
      {
        {
          $validator= Validator::make($request->all(), [
            'email' => 'required|email|unique:users|max:800',
            'password' => 'required|max:18|min:5|confirmed|unique:users',
            'name'=>'required|max:40',
            'description'=>'required|max:800|string',
            'type'=>['required','max:50',Rule::in([0,1])],
            'role'=>['required','max:50',Rule::in([0,1,2])]


            ]
          );
           
       }    
      }
      return $validator;
       
    }

    public function validateAbilities(Request $request)
    {
      $features= ['addSale','addGrn'];
      foreach($request->abilities as $ability)
      {
        if(!in_array($ability,$features))
        {
          return false;
        }
      }
      return true;
    }
}
