<?php

namespace App\Modules\Stocks;

use App\Models\Stock;
use App\Modules\Stocks\StockRepositoryInterface;
use Exception;

class StockRepository implements StockRepositoryInterface
{
    public function increment($id,int $quantity)
    {

        $stock = $this->getById($id);

        if($stock===null)
        {   
            return Stock::create(['item_code'=>$id,'quantity'=>$quantity]);
        }
        return $stock->update(['quantity'=>$stock->quantity+$quantity]);
    }

    public function update($id, int $quantityToAdd,int $quantityToRemove)
    {
        if($this->checkForAvailability($id,$quantityToAdd-$quantityToRemove))
        {
            $stock = $this->getById($id);
            $stock->update(['quantity'=>$stock->quantity-$quantityToRemove+$quantityToAdd]);
            return $stock;
        }
       
    }

    public function decrement($id,int $quantity)
    {
       if($this->checkForAvailability($id,$quantity))
       {
        $stock = $this->getById($id);
        return $stock->update(['quantity'=>$stock->quantity-$quantity]);
       } 
       return null;
    }


    public function getById($id)
    {
        return Stock::where('item_code',$id)->first();
    }

    public function checkForAvailability($id,int $quantity)
    {
        $stock = $this->getById($id);
        if($stock!==null && $stock->quantity>=$quantity)
        {
          return true;  
        }
         throw new Exception("insufficient stock.");
         
    }
   
}