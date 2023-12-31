<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class GoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
 
 
     */
    public function toArray(Request $request): array
    { 
        return [
            'Deal date'=>$this->created_at->format('Y-m-d'),
            'name'=>$this->name?$this->name:$this->item_code,
            'unit'=>$this->description,
            'brand'=>$this->brand->name,
            'modal'=> $this->modal->name,
            'category'=>$this->category->name,
            'expired_date'=>$this->expired_date,
            'received_price_per_unit'=>$this->received_price_per_unit,
            'sale_price_per_unit'=>$this->sale_price_per_unit,
            'quantity'=>$this->quantity,
            'Received or Sold'=>$this->deal->deal_type=='income'? 'Sold':'Received',
            'dealer' => $this->whenNotNull($this->dealer->user->name)


        ];
    }
}
