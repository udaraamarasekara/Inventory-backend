<?php

namespace App\Modules\Goods;

use App\Models\Good;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\Utilities\HelpingFunctions;

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
        $good = $this->getById($id);
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

    public function allGoods(array $ids)
    {
     $goods=[];   
     foreach($ids as $id)
     {
      $goods[]=$this->getById($id);
     }  
     
     return  HelpingFunctions::paginate(collect($goods));
    }
   
    public function salesTotalCost(array $ids)
    {
        $goods=0;   
        foreach($ids as $id)
        {
         if($this->getById($id))
         {
            $goods+=$this->getById($id)['received_price_per_unit'];
         }   
        }
        return $goods;  
    }

    public function allGoodDetailDeals(array $ids,array $data)
    {
     $goods=[];   
     foreach($ids as $id)
     {
      $goods[]=$this->getById($id);
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
         $goods[]=$this->getById($id);
        }  
         return collect($goods);
    }

    public function goodsCount()
    {
       return Good::distinct()->count('item_code'); 
    }


    public function searchGood($input)
    {
       
      return  Good::select(["item_code","id"])
      ->where('item_code', 'LIKE', '%' .$input. '%')
      ->orWhere('unit', 'LIKE', '%' .$input. '%')
      ->orWhere('description', 'LIKE', '%' .$input. '%')
      ->orWhere('received_price_per_unit', 'LIKE', '%' .$input. '%')
      ->orWhere('expired_date', 'LIKE', '%' .$input. '%')
      ->orWhere('sale_price_per_unit', 'LIKE', '%' .$input. '%')
      ->get();
    }

}
