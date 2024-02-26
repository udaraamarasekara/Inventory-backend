<?php

namespace App\Modules\Goods;

use App\Models\Good;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\Utilities\HelpingFunctions;
use App\Models\Deal;

use DB;
class GoodRepository implements GoodRepositoryInterface
{
    public function getAll()
    {
        return Good::paginate(10);
    }

    public function getById($id)
    {
        return Good::find($id);
    }

    public function create(array $data)
    {
        return Good::create($data);
    }

    public function update($id, array $data)
    {
        $good = $this->getByDealId($id);
        if($good)
        {
          $good->update($data);
        }
        else
        {
         return ['error'=>'Data not found!']; 
        }
        return $good;
    }

    public function delete($id)
    {
        $good = $this->getById($id);
        if($good)
        {
            $good->delete();
        }
        else
        {
         return ['error'=>'Data not found!']; 
        }
        return ['success'=>'Data deleted']; 
    }
    public function allGoods(array $ids,int $page)
    {
     $goods=[];   
     foreach($ids as $id)
     {
       foreach($this->getByDealId($id) as $single)
       {
        $goods[]=$single;
       }
     }  
     if(!$page)
     {
       $page=1; 
     }
     return  HelpingFunctions::paginate(collect($goods),$page);
    }
   
    public function salesTotalCost(array $ids)
    {
        $goods=0;   
        foreach($ids as $id)
        {
         if($this->getByDealId($id))
         {
            $goods+=$this->getByDealId($id)['received_price_per_unit'];
         }   
        }
        return $goods;  
    }

    public function allGoodDetailDeals(array $ids,array $data)
    {
     $goods=[];   
     foreach($ids as $id)
     {
      $goods[]=$this->getByDealId($id);
     }  
     $goodsRow=collect($goods);
     $fetchedGoods=  $goodsRow->filter(function($good) use ($data){
      return $good[$data['goodDetail'].'_id']==$data['id'];
     });
     return  HelpingFunctions::paginate($fetchedGoods);
    }

    public function data(array $ids)
    {
        $goods=[];   
        foreach($ids as $id)
        {
         $goods[]=$this->getByDealId($id);
        }  
         return collect($goods);
    }

    public function goodsCount()
    {
       return Good::distinct()->count('item_code'); 
    }

    public function productTransactionCount()
    {
        
    return  ['spend_for_buy_products'=> Good::whereRelation('deal','deal_type','income')->sum(DB::raw(
        'received_price_per_unit * quantity')
    ),
    'received_from_sale_products'=> Good::whereRelation('deal','deal_type','expend')->sum(DB::raw(
        'sale_price_per_unit * quantity')
    ),
    'promised_to_receive'=> Deal::where('deal_type','income')->whereRelation('promisedPayment','payed_at',null)->get()->map(function($deal){return $deal->promisedPayment;})->sum('amount'),
    'promised_to_pay'=> Deal::where('deal_type','expend')->whereRelation('promisedPayment' ,'payed_at',null)->get()->map(function($deal){return $deal->promisedPayment;})->sum('amount')

    ];

    }

    public function searchGood($input)
    {
       
      return  Good::select(["item_code","id"])
      ->where('item_code', 'LIKE', '%' .$input. '%')
      ->orWhere('unit', 'LIKE', '%' .$input. '%')
      ->orWhere('description', 'LIKE', '%' .$input. '%')
      ->orWhere('received_price_per_unit', 'LIKE', '%' .$input. '%')
      ->orWhere('job_number', 'LIKE', '%' .$input. '%')
      ->orWhere('sale_price_per_unit', 'LIKE', '%' .$input. '%')
      ->get();
    }

    public function searchColumn($type, $text)
    {
      return Good::select([$type])
      ->where($type, 'LIKE', '%' .$text. '%')->get();
    }
    public function getDealGroupId()
    {
      return Good::max('id');
    }

    public function deleteByDealId($id)
    {
      Good::where('deal_id',$id)->delete();  
    }

    public function getByDealId($id)
    {
       return Good::where('deal_id',$id)->get();  
    }

   
}
