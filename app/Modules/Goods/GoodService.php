<?php

namespace App\Modules\Goods;
use App\Http\Resources\GoodResource;
use App\Modules\Deals\DealService;
use App\Modules\GoodDetails\GoodDetailService;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\PromisedPayments\PromisedPaymentService;
use Illuminate\Support\Facades\DB;
use App\Modules\Users\UserRepositoryInterface;
use App\Modules\Stocks\StockService;


class GoodService 
{

    public function __construct(protected GoodRepositoryInterface $goodRepository,protected StockService $stockService,
    protected DealService $dealService, protected PromisedPaymentService $promisedPaymentService,
    protected GoodDetailService $goodDetailService, protected UserRepositoryInterface $userRepository)
    {
    }

    public function getAll()
    {
        return GoodResource::collection($this->goodRepository->getAll());
    }

    public function getAllWithinPeriod(array $data)
    {
      isset($data['page']) ? $page =$data['page'] :$page=0; 
      $ids=$this->dealService->goods($data);
       return GoodResource::collection($this->goodRepository->allGoods($ids,$page));
    }

    public function getById($id)
    {
        return new GoodResource($this->goodRepository->getById($id));
    }

    public function getByDealId($id)
    {
      return $this->goodRepository->getByDealId($id);
    }

    public function create(array $dataArray)
    {
      try{
        $goodRowArry=[];
          DB::beginTransaction();
           $deal_group_id= $this->goodRepository->getDealGroupId()+1;
          foreach ($dataArray['data'] as $data )
         {  
            
            try
            {
              
                DB::beginTransaction();
                $data['dealer_id']=2;
                $data['deal_id']=$deal_group_id;
                $data['stock_id']=$this->goodRepository->getDealGroupId()+1;
                $data['part_id']=$this->goodRepository->getDealGroupId()+1;
                $goodRow=   new GoodResource( $this->goodRepository->create($data)); 
                if($dataArray['deal_type']=='income')
                {
                  $this->stockService->decrement($goodRow['item_code'],$goodRow['quantity']);
                }
                else
                {
                  $this->stockService->increment($goodRow['item_code'],$goodRow['quantity']); 
                }
            

                DB::commit();
                $goodRowArry= $goodRow;
            }
            catch(\Exception $e)
            {
              DB::rollBack();
              dd($e);
              return $e;
            }
            }
            $deal_id= $this->dealService->create(['dealable_type'=>'App\Models\Good','dealable_id'=>$goodRowArry['deal_id'],'deal_type'=>$dataArray['deal_type'],'amount'=>$dataArray['amount'],'relevent_user'=>auth()->user()->id])->id;
            if(isset($dataArray['promised_amount']) && isset($dataArray['promised_deadline']))
            {
            $promisedPayment=['amount'=>$dataArray['promised_amount'],'deadline'=>$dataArray['promised_deadline'],'deal_id'=>$deal_id]; 
            $this->promisedPaymentService->create($promisedPayment);  
            }
            DB::commit(); 
          }   
          catch(\Exception $e)
          {
            dd($e);
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
    { try{
       DB::beginTransaction();
        $deal= $this->dealService->deleteReleventToGood($id);
        $this->promisedPaymentService->deleteIfAny($deal['id']); 
        foreach($this->getByDealId($id) as $goodRow)
        {
          if($deal['deal_type']=='expend')
          {
            $this->stockService->decrement($goodRow['item_code'],$goodRow['quantity']);
          }
          else
          {
            $this->stockService->increment($goodRow['item_code'],$goodRow['quantity']); 
          }
      
        }
         $result=  $this->goodRepository->deleteByDealId($id);
         DB::commit();

       return $result;
    
      }catch(\Exception $e)
      {dd($e);
          DB::rollBack(); 
      }
   }
    public function allSales(array $data)
    {
      isset($data['page']) ? $page =$data['page'] :$page=0; 
        $ids=$this->dealService->sales($data);
        return GoodResource::collection($this->goodRepository->allGoods($ids,$page));
    }

    public function allGrns(array $data)
    {
      isset($data['page']) ? $page =$data['page'] :$page=0; 

        $ids=$this->dealService->grns($data);
        return GoodResource::collection($this->goodRepository->allGoods($ids,$page));
    }
    public function calProfitLost(array $data)
    {
        $ids=$this->dealService->sales($data);
        $income=$this->dealService->salesIncome($data);
        $cost=$this->goodRepository->salesTotalCost($ids);
        return new GoodResource(['income'=>$income,'cost'=>$cost,'profit_or_lost'=>$income-$cost]);
    }

    public function allTimeSales(array $data)
    {
      isset($data['page']) ? $page =$data['page'] :$page=0; 
       $ids=$this->dealService->allTimeSales();
       return GoodResource::collection($this->goodRepository->allGoods($ids,$page));
    }

    public function allTimeGrns(array $data)
    {
      isset($data['page']) ? $page =$data['page'] :$page=0; 
       $ids=$this->dealService->allTimeGrns();
       return GoodResource::collection($this->goodRepository->allGoods($ids,$page));
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

    public function searchAll(String $type, String $inputText)
    {
      if($type=='brand'||$type=='modal'||$type=='category')
      {
       return $this->goodDetailService->searchSpecificGoodDetail($type,$inputText);
      }
      else if($type=='customer')
      {
        return $this->userRepository->searchCustomer($inputText); 
      }
      elseif($type=='supplier')
      {
        return $this->userRepository->searchSupplier($inputText); 
      }
      else{
       return $this->goodRepository->searchColumn($type,$inputText);
      }
    }
    public function getDealGroupId()
    {
      return $this->goodRepository->getDealGroupId();
    }


}