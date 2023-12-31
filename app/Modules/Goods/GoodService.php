<?php

namespace App\Modules\Goods;
use App\Http\Resources\GoodResource;
use App\Modules\Deals\DealService;
use App\Modules\GoodDetails\GoodDetailService;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\PromisedPayments\PromisedPaymentService;
use Illuminate\Support\Facades\DB;
use App\Modules\Stocks\StockService;

class GoodService 
{

    public function __construct(protected GoodRepositoryInterface $goodRepository,protected StockService $stockService,
    protected DealService $dealService, protected PromisedPaymentService $promisedPaymentService,
    protected GoodDetailService $goodDetailService
    )
    {
    }

    public function getAll()
    {
        return GoodResource::collection($this->goodRepository->getAll());
    }

    public function getById($id)
    {
        return new GoodResource($this->goodRepository->getById($id));
    }

    public function create(array $data)
    {
         try
         {
            DB::beginTransaction();
            $good=   new GoodResource( $this->goodRepository->create($data)); 
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
              $good=  new GoodResource($this->goodRepository->update($id,$data));  
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
        return GoodResource::collection($this->goodRepository->allGoods($ids));
    }

    public function allGrns(array $data)
    {
        $ids=$this->dealService->grns($data);
        return GoodResource::collection($this->goodRepository->allGoods($ids));
    }
    public function calProfitLost(array $data)
    {
        $ids=$this->dealService->sales($data);
        $income=$this->dealService->salesIncome($data);
        $cost=$this->goodRepository->salesTotalCost($ids);
        return new GoodResource(['income'=>$income,'cost'=>$cost,'profit_or_lost'=>$income-$cost]);
    }

    public function allTimeSales()
    {
       $ids=$this->dealService->allTimeSales();
       return GoodResource::collection($this->goodRepository->allGoods($ids));
    }

    public function allTimeGrns()
    {
       $ids=$this->dealService->allTimeGrns();
       return GoodResource::collection($this->goodRepository->allGoods($ids));
    }

    public function allGoodDetailSales(array $data)
    {       
       $ids=$this->dealService->sales($data);
       return GoodResource::collection($this->goodRepository->allGoodDetailDeals($ids,$data));
    }

    public function allTimeGoodDetailSales(array $data)
    {       
      $ids=$this->dealService->allTimeSales();
      return GoodResource::collection($this->goodRepository->allGoodDetailDeals($ids,$data));
    }

    public function allTimeGoodDetailGrns(array $data)
    {       
      $ids=$this->dealService->allTimeGrns();
      return GoodResource::collection($this->goodRepository->allGoodDetailDeals($ids,$data));
    }


    public function allGoodDetailGrns(array $data)
    {       
       $ids=$this->dealService->grns($data);
       return GoodResource::collection($this->goodRepository->allGoodDetailDeals($ids,$data));
    }

    public function mostProfitedGoodDetail(array $data)
    {
      $goodDetails=$this->goodDetailService->getAllWithoutPaginate($data['goodDetail']);
      foreach($goodDetails as $goodDetail)
      {
        $goodDetail['expend']=0;
        $goodDetail['income']=0;
        $goodDetail['promisedPayments']=0;
      }
      $grnIds=$this->dealService->grns($data);
      $grnData=$this->goodRepository->data($grnIds);
      foreach($grnData as $dataRow)
      {
        foreach($goodDetails as $goodDetail)
        {
          if($goodDetail['id']==$dataRow[$data['goodDetail'].'_id'])
          {
            $goodDetail['expend']+=$dataRow['received_price_per_unit']*$dataRow['quantity'];
          }
        }
      }

      $saleIds=$this->dealService->sales($data);
      $saleData=$this->goodRepository->data($saleIds);
      foreach($saleData as $dataRow)
      {
        foreach($goodDetails as $goodDetail)
        {
          if($goodDetail['id']==$dataRow[$data['goodDetail'].'_id'])
          {
            $goodDetail['income']+=$dataRow['sale_price_per_unit']*$dataRow['quantity'];
          }
        }
      }
      $promisedPayments=$this->promisedPaymentService->getAllWithoutPaginate();
      $dealIds=[];
      foreach($promisedPayments as $promisedPayment)
      {
       $dealIds[]= $promisedPayment['deal_id'];
      }
      $deals= $this->dealService->getReleventDealsForGoods($dealIds);
      $goods=[];
      foreach($deals as $deal)
      {
        $goods[]=$this->getById($deal);  
      }
      foreach($goods as $dataRow)
      {
        foreach($goodDetails as $goodDetail)
        {
          if($goodDetail['id']==$dataRow[$data['goodDetail'].'_id'])
          {
            $goodDetail['promisedPayments']+=$promisedPayments->where('deal_id',$deal)->first()->value('amount');
          }
        }
      }
     return GoodResource::collection($goodDetails);

    }

    public function goodsCount()
    {
      return $this->goodRepository->goodsCount();
    }

    public function searchGood($input)
    {
      return $this->goodRepository->searchGood($input);
    }

    public function productTransactionCount()
    {
      return $this->goodRepository->productTransactionCount();
    }

}