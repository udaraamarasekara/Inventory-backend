<?php

namespace App\Modules\Deals;

use App\Models\Deal;
use App\Modules\Deals\DealRepositoryInterface;

class DealRepository implements DealRepositoryInterface
{
    public function getAll()
    {
        return Deal::paginate(10);
    }

    public function getById($id)
    {
        return Deal::find($id);
    }

    public function create(array $data)
    {
        return Deal::create($data);
    }

    public function update($id, array $data)
    {
        $deal = $this->getById($id);
        if($deal)
        {
            $deal->update($data);
        }
        else
        {
         return ['error'=>'Deal not found!']; 
        }
        return $deal;
    }

    public function delete($id)
    {
        $deal = $this->getById($id);
        if($deal)
        {
            $deal->delete();
        }
        else
        {
         return ['error'=>'Deal not found!']; 
        }
        return ['success'=>'Deal deleted'];

    }
    public function sales(array $data)
    {   
      return  Deal::where('deal_type','income')->whereBetween('created_at',[$data['from'],$data['to']])->where('dealable_type','App\Models\Good')->get()->pluck('dealable_id')->toArray();  
    }

    public function goods(array $data)
    {
      return  Deal::whereBetween('created_at',[$data['from'],$data['to']])->where('dealable_type','App\Models\Good')->get()->pluck('dealable_id')->toArray();  
    }

    public function grns(array $data)
    {   
      return  Deal::where('deal_type','expend')->whereBetween('created_at',[$data['from'],$data['to']])->where('dealable_type','App\Models\Good')->get()->pluck('dealable_id')->toArray();  
    }

    public function salesIncome(array $data)
    {   
      $totIncome=0;  
      $amounts=  Deal::where('deal_type','income')->whereBetween('created_at',[$data['from'],$data['to']])->where('dealable_type','App\Models\Good')->get()->pluck('amount')->toArray();  
      foreach($amounts as $amount)
      {
        $totIncome+=$amount;
      }
      return $totIncome;
    }

    public function deleteReleventToGood($id)
    {
       if($deal= Deal::where([['dealable_id',$id],['dealable_type','App\Models\Good']]))
       {
       $dealData=[]; 
       $dealData['deal_type']=$deal->first()['deal_type'];
       $dealData['id']=$deal->first()['id']; 
        $deal->delete();
       return $dealData;

       } 
    }
   
    public function udateByDealableId($id,$amount)
    {
     $deal= Deal::where('dealable_id',$id);   
     $deal->update(['amount'=>$amount]);
     return $deal->value('id');
    }

    public function allTimeSales()
    {
      return  Deal::where('deal_type','income')->where('dealable_type','App\Models\Good')->get()->pluck('dealable_id')->toArray();  
    }

    public function allTimeGrns()
    {
      return  Deal::where('deal_type','expend')->where('dealable_type','App\Models\Good')->get()->pluck('dealable_id')->toArray();   
    }

    public function getAllWithoutPaginate()
    {
      return Deal::all();
    }

    public function getReleventDealsForGoods(array $dealIds)
    {
     $validDealIds=[]; 
     foreach($dealIds as $dealId) 
     {
      $deal=$this->getById($dealId);
      if($deal['dealable_type']=='App\Models\Good')
      {
        $validDealIds[]=$deal['dealable_id'];
      }

     }
      return $validDealIds;
    }

  
    
}
