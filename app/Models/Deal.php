<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['dealable_type','dealable_id','amount','deal_type','deal_id'];

    public function dealable():MorphTo
    {
      return $this->morphTo();  
    }

    public function promisedPayment()
    {
      return $this->hasOne(PromisedPayment::class,'deal_id');
    }  

}
