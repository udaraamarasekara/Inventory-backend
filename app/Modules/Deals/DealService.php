<?php

namespace App\Modules\Deals;
use App\Http\Resources\CommonResource;
use App\Modules\Deals\DealRepositoryInterface;

class DealService 
{

    public function __construct(protected DealRepositoryInterface $dealRepository)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->dealRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->dealRepository->getById($id));
    }

    public function create(array $data)
    {  
        return  new CommonResource( $this->dealRepository->create($data));    
    }

    public function update($id,array $data)
    {  
        return new CommonResource($this->dealRepository->update($id,$data));     
    }

    public function delete($id)
    {
        return $this->dealRepository->delete($id);
    }

    public function sales(array $data)
    {
       return $this->dealRepository->sales($data);
    }

    public function grns(array $data)
    {
       return $this->dealRepository->grns($data);
    }

    public function goods(array $data)
    {
       return $this->dealRepository->goods($data);
    }

    public function salesIncome(array $data)
    {
       return $this->dealRepository->salesIncome($data);
    }

    public function deleteReleventToGood($id)
    {
       return $this->dealRepository->deleteReleventToGood($id);
    }
    
    public function udateByDealableId($id ,string $amount)
    {
       return $this->dealRepository->udateByDealableId($id,$amount);
    }

    public function allTimeSales()
    {
       return $this->dealRepository->allTimeSales(); 
    }

    public function allTimeGrns()
    {
       return $this->dealRepository->allTimeGrns();
    }

    public function getAllWithoutPaginate()
    {
       return $this->dealRepository->getAllWithoutPaginate();
    }

    public function getReleventDealsForGoods(array $dealIds)
    {
       return $this->dealRepository->getReleventDealsForGoods($dealIds);
    }

   
    
}