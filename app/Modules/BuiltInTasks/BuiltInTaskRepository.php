<?php

namespace App\Modules\BuiltInTasks;

use App\Models\BuiltInTask;



use App\Modules\BuiltInTasks\BuiltInTaskRepositoryInterface;

class BuiltInTaskRepository implements BuiltInTaskRepositoryInterface
{
   

    public function getAll()
    {
        return  BuiltInTask::paginate(10);
    }

    public function getById($id)
    {   
        return  BuiltInTask::find($id);
    }

    public function create(array $data)
    {
        return  BuiltInTask::create($data);
    }

    public function update($id, array $data)
    {
        $builtInTask = $this->getById($id);
        if($builtInTask)
        {
            $builtInTask->update($data);
        }
        else
        {
         return ['error'=>'builtInTask not found!']; 
        }
        return $builtInTask;
        
    }

    public function delete($id)
    {
        $builtInTask = $this->getById($id);
        $builtInTask->delete();
    }
   
}