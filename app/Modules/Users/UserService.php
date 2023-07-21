<?php

namespace App\Modules\Users;
use App\Http\Resources\CommonResource;
use App\Modules\Employees\EmployeeService;
use App\Modules\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Modules\Dealers\DealerService;

class UserService 
{

    public function __construct(protected UserRepositoryInterface $userRepository,protected EmployeeService $employeeService,protected DealerService $dealerService)
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

        if (auth()->attempt($data)) {
            $user= $this->userRepository->login($data['email']);
            $user->createToken($user->id)->plainTextToken;
            return new CommonResource($user);
        } else {
            // Authentication failed
            return "Invalid Entry";
        }
        
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }

    
}