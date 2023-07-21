<?php

namespace App\Modules\Dealers;
use App\Http\Resources\CommonResource;
use App\Modules\Dealers\DealerRepositoryInterface;

class DealerService 
{

    public function __construct(protected DealerRepositoryInterface $dealerRepository)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->dealerRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->dealerRepository->getById($id));
    }

    public function create(array $data)
    {  
        return  new CommonResource( $this->dealerRepository->create($data));    
    }

    public function update($id,array $data)
    {  
        return new CommonResource($this->dealerRepository->update($id,$data));     
    }

    public function delete($id)
    {
        return $this->dealerRepository->delete($id);
    }

    public function DeleteByUserId($id)
    {
      $id = $this->dealerRepository->getByUserId($id);
      $this->delete($id);
    }

    public function UpdateByUserId($id,array $data)
    {
      $id = $this->dealerRepository->getByUserId($id);
      
      $this->update($id,$data);
    }

}