<?php

namespace App\Modules\BuiltInTasks;
use App\Http\Resources\CommonResource;
use App\Modules\BuiltInTasks\BuiltInTaskRepositoryInterface;

class BuiltInTaskService 
{

    public function __construct(protected BuiltInTaskRepositoryInterface $builtInTaskRepository)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->builtInTaskRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->builtInTaskRepository->getById($id));
    }

    public function create(array $data)
    {  
        return  new CommonResource( $this->builtInTaskRepository->create($data));    
    }

    public function update($id,array $data)
    {  
        return new CommonResource($this->builtInTaskRepository->update($id,$data));     
    }

    public function delete($id)
    {
        return $this->builtInTaskRepository->delete($id);
    }

}