<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dealer;

class DealerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $dealers=[['user_id'=>User::where('name','unknownSupplier')->first()->id,'description'=>'none','type'=>1],
      ['user_id'=>User::where('name','unknownCustomer')->first()->id,'description'=>'none','type'=>2]
    ];
      foreach($dealers as $dealer)
      {
        Dealer::create($dealer); 
      }
    }
}
