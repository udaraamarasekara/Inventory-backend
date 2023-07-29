<?php

namespace App\Modules\PromisedPayments;
use App\Http\Resources\CommonResource;
use App\Modules\PromisedPayments\PromisedPaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PromisedPaymentService 
{

    public function __construct(protected PromisedPaymentRepositoryInterface $promisedPaymentRepository)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->promisedPaymentRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->promisedPaymentRepository->getById($id));
    }

    public function create(array $data)
    {
         try
         {
            DB::beginTransaction();
            new CommonResource($this->promisedPaymentRepository->create($data));
            DB::commit();

         }
         catch(\Exception $e)
         {
           DB::rollBack();
           return $e;
         }
        
    }

    public function update($id,array $data)
    {
        try
        {
            DB::beginTransaction();
            if($this->promisedPaymentRepository->getById($id))
            {
              $good=  new CommonResource($this->promisedPaymentRepository->update($id,$data));  
            }else
            {
              return "item not exist";
            }
            
            return $good;
        }
        catch(\Exception $e)
        {
          DB::rollBack();
          return $e;
        }
    }

    public function delete($id)
    {
        return $this->promisedPaymentRepository->delete($id);
    }
    public function deleteIfAny($dealId)
    {
        return $this->promisedPaymentRepository->deleteIfAny($dealId);
    }
    public function updateByDealId($data)
    {
        return $this->promisedPaymentRepository->updateByDealId($data);
    }

    public function getAllWithoutPaginate()
    {
      return $this->promisedPaymentRepository->getAllWithoutPaginate();

    }
}