<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Deal;
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
            'job_number'=>$this->job_number,
            'stock_number'=>$this->stock_number,
            'part_number'=>$this->part_number,
            'received_price_per_unit'=>$this->received_price_per_unit,
            'sale_price_per_unit'=>$this->sale_price_per_unit,
            'quantity'=>$this->quantity,
            'Received or Sold'=>Deal::where('dealable_id',$this->deal_id)->first()->deal_type=='income'? 'Sold':'Received',
            'dealer' => $this->whenNotNull($this->dealer?->user->name)


        ];
    }
}
