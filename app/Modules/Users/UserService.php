<?php

namespace App\Modules\Users;
use App\Http\Resources\CommonResource;
use App\Modules\Employees\EmployeeService;
use App\Modules\GoodDetails\GoodDetailService;
use App\Modules\Goods\GoodService;
use App\Modules\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Modules\Dealers\DealerService;

class UserService 
{

    public function __construct(protected UserRepositoryInterface $userRepository,protected EmployeeService $employeeService,protected DealerService $dealerService,protected GoodService $goodService,protected GoodDetailService $goodDetailService)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->userRepository->getAll());
    }

    public function delete($id)
    {
        try
        {
            DB::beginTransaction();
            $type=$this->getById($id)['role'];
            if($type=='employee'){
               $this->employeeService->deleteByUserId($id);
            }
            else
            {
               $this->dealerService->deleteByUserId($id);
            }
            $user= $this->userRepository->delete($id);

            DB::commit();
            return $user;
        }
        catch(\Exception $e)
        {
          DB::rollBack(); 
        }
    
    }

    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }
    
    public function create(array $data)
    {
        try
        {
            DB::beginTransaction();
            $user= new CommonResource($this->userRepository->create($data));
            $data['user_id']=$user['id']; 
            if($data['role']=='employee')
            {
             $this->employeeService->create($data); 
            }
            else
            {
             $this->dealerService->create($data); 
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $e;
        }
        return $user;  
    }

    public function update($id,array $data)
    {
        try
        {
            DB::beginTransaction();
            $user= new CommonResource($this->userRepository->update($id,$data));
            if($data['role']=='employee')
            {
             $this->employeeService->UpdateByUserId($id,$data); 
            }
            else
            {
             $this->dealerService->UpdateByUserId($id,$data); 
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
        }
        return $user;      }

    public function login(array $data)
    {
          $remember=$data['remember'];
          unset($data['remember']);
        if (auth()->attempt($data,$remember)) {
            
            return new CommonResource(auth()->user());
        } else {
            // Authentication failed
            return "Invalid Entry";
        }
        
    }   

    public function searchAll($input)
    {
       $userSuggessions=$this->userRepository->searchUser($input); 
       $userSuggessions->map(function($userSuggession)  
       {
        $userSuggession['table']='user'; 
       });
       $goodSuggessions=$this->goodService->searchGood($input); 
       $goodSuggessions->map(function($goodSuggession)  
       {
        $goodSuggession['table']='good'; 
       });
       $goodDetailSuggessions=$this->goodDetailService->searchGoodDetail($input);
       $fnl= collect($userSuggessions->merge($goodSuggessions))->merge($goodDetailSuggessions);
       return $fnl;
    }
}