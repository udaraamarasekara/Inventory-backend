<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        "item_code",
        "description",
        "brand_id",
        "modal_id",
        "category_id",
        "received_price_per_unit",
        "sale_price_per_unit",
        "expired_date",
        "unit",
        "quantity"
    ];

    public function brand(){
      return  $this->hasOne(Brand::class);
    }

}
