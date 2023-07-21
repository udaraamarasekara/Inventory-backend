<?php

namespace App\Modules\Employees;
use App\Http\Resources\CommonResource;
use App\Modules\Employees\EmployeeRepositoryInterface;

class EmployeeService 
{

    public function __construct(protected EmployeeRepositoryInterface $employeeRepository)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->employeeRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->employeeRepository->getById($id));
    }

    public function create(array $data)
    {  
        return  new CommonResource( $this->employeeRepository->create($data));    
    }

    public function update($id,array $data)
    {  
        return new CommonResource($this->employeeRepository->update($id,$data));     
    }

    public function delete($id)
    {
        return $this->employeeRepository->delete($id);
    }

    public function DeleteByUserId($id)
    {
      $id = $this->employeeRepository->getByUserId($id);
      $this->delete($id);
    }

    public function UpdateByUserId($id,array $data)
    {
      $id = $this->employeeRepository->getByUserId($id);
      if(!$id)
      {
        return new CommonResource(['error'=>'User not found']);
      }
      return new CommonResource($this->update($id,$data));
    }

}