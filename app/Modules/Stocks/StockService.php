<?php

namespace App\Modules\Stocks;
use App\Http\Resources\CommonResource;
use App\Modules\Stocks\StockRepositoryInterface;

class StockService 
{

    public function __construct(protected StockRepositoryInterface $stockRepository)
    {
    }

    public function increment($id,int $quantity)
    {
        return new CommonResource($this->stockRepository->increment($id,$quantity));
    }

    public function decrement($id,int $quantity)
    {
        return new CommonResource($this->stockRepository->decrement($id,$quantity));
    }

    public function update($id,int $quantityToAdd,int $quantityToRemove)
    {
        return new CommonResource($this->stockRepository->update($id,$quantityToAdd,$quantityToRemove));
    }
  
}