<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\GoodDetails\GoodDetailService;
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
            'name'=>$this->name,
            'unit'=>$this->description,
            'brand'=>$this->brand->name,
            'modal'=> $this->modal->name,
            'category'=>$this->category->name,
            'expired_date'=>$this->expired_date,
            'received_price_per_unit'=>$this->received_price_per_unit,
            'sale_price_per_unit'=>$this->sale_price_per_unit,
            'quantity'=>$this->quantity

        ];
    }
}
