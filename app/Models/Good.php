<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        "job_number",
        "unit",
        "quantity",
        "dealer_id",
        "deal_id"
        
    ];

    public function brand(){
      return  $this->belongsTo(Brand::class);
    }
    public function modal(){
      return  $this->belongsTo(Modal::class);
    }
    public function category(){
      return  $this->belongsTo(Category::class);
    }

    public function deal(){
      return $this->morphOne(Deal::class, 'dealable');
    }

    public function dealer(){
      return $this->belongsTo(Dealer::class);
    }
}
