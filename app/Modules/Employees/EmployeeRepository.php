<?php

namespace App\Modules\Employees;

use App\Models\Employee;



use App\Modules\Employees\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
   

    public function getAll()
    {
        return  Employee::paginate(10);
    }

    public function getById($id)
    {   
        return  Employee::find($id);
    }

    public function create(array $data)
    {
        return  Employee::create($data);
    }

    public function update($id, array $data)
    {
        $employee = $this->getById($id);
        if($employee)
        {
            $employee->update($data);
        }
        else
        {
         return ['error'=>'Employee not found!']; 
        }
        return $employee;
    }

    public function delete($id)
    {

        $employee = $this->getById($id);
        if($employee)
        {
            $employee->delete();
        }
        else
        {
         return ['error'=>'Employee not found!']; 
        }
        return ['success'=>'Employee deleted'];
    }

    public function getByUserId($id)
    {
        return Employee::where('user_id',$id)['id']; 
    }


   
}
