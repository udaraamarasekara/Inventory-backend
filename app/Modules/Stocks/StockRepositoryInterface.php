<?php

namespace App\Modules\Stocks;


interface StockRepositoryInterface
{   
    public function increment($id,int $quantity);
    
    public function update($id, int $quantityToAdd,int $quantityToRemove);
    
    public function decrement($id, int $quantity);

}
