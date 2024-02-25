<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAbility extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['profession_id','table','method','ability']; 
}
