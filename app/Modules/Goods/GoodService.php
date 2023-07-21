<?php

namespace App\Modules\Goods;
use App\Http\Resources\CommonResource;
use App\Modules\Deals\DealService;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\PromisedPayments\PromisedPaymentService;
use Illuminate\Support\Facades\DB;
use App\Modules\Stocks\StockService;

class GoodService 
{

    public function __construct(protected GoodRepositoryInterface $goodRepository,protected StockService $stockService,
    protected DealService $dealService, protected PromisedPaymentService $promisedPaymentService)
    {
    }

    public function getAll()
    {
        return CommonResource::collection($this->goodRepository->getAll());
    }

    public function getById($id)
    {
        return new CommonResource($this->goodRepository->getById($id));
    }

    public function create(array $data)
    {
         try
         {
            DB::beginTransaction();
            $good=   new CommonResource( $this->goodRepository->create($data)); 
            if($data['deal_type']=='income')
            {
              $this->stockService->decrement($good['item_code'],$good['quantity']);
            }
            else
            {
              $this->stockService->increment($good['item_code'],$good['quantity']); 
            }
            $deal= $this->dealService->create(['dealable_type'=>'App\Models\Good','dealable_id'=>$good['id'],'deal_type'=>$data['deal_type'],'amount'=>$data['amount']]);
            if(isset($data['promised_amount']) && isset($data['promised_deadline']))
            {
             $promisedPayment=['amount'=>$data['promised_amount'],'deadline'=>$data['promised_deadline'],'deal_id'=>$deal['id']]; 
             $this->promisedPaymentService->create($promisedPayment);  
            }
           
            DB::commit();

             return $good;
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
            if($this->goodRepository->getById($id))
            {
              $quantityToRemove=$this->goodRepository->getById($id)['quantity'];      
              $good=  new CommonResource($this->goodRepository->update($id,$data));  
              $dealId= $this->dealService->udateByDealableId($id,$data['amount']);
              $this->stockService->update($good['item_code'],$good['quantity'],$quantityToRemove);
              if(isset($data['promised_amount']) && isset($data['promised_deadline']))
              {
               $promisedPayment=['amount'=>$data['promised_amount'],'deadline'=>$data['promised_deadline'],'deal_id'=>$dealId]; 
               $this->promisedPaymentService->updateByDealId($promisedPayment);  
              }
            }else
            {
              return "item not exist";
            }
            
            DB::commit();
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
        $dealId= $this->dealService->deleteReleventToGood($id);
        $this->promisedPaymentService->deleteIfAny($dealId);  
        return $this->goodRepository->delete($id);
    }

    public function allSales(array $data)
    {
        $ids=$this->dealService->sales($data);
        return CommonResource::collection($this->goodRepository->allGoods($ids));
    }

    public function allGrns(array $data)
    {
        $ids=$this->dealService->grns($data);
        return CommonResource::collection($this->goodRepository->allGoods($ids));
    }
    public function calProfitLost(array $data)
    {
        $ids=$this->dealService->sales($data);
        $income=$this->dealService->salesIncome($data);
        $cost=$this->goodRepository->salesTotalCost($ids);
        return new CommonResource(['income'=>$income,'cost'=>$cost,'profit_or_lost'=>$income-$cost]);
    }

    public function allTimeSales()
    {
       $ids=$this->dealService->allTimeSales();
       return CommonResource::collection($this->goodRepository->allGoods($ids));
    }

    public function allTimeGrns()
    {
       $ids=$this->dealService->allTimeGrns();
       return CommonResource::collection($this->goodRepository->allGoods($ids));
    }

}