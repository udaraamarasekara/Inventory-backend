<?php

namespace App\Modules;

class UserFeaturesService{
 public function addGrn()
 {
    return[
                ['table'=>'good','method'=>1,'ability'=>true],
                ['table'=>'good','method'=>2,'ability'=>true],
                ['table'=>'deal','method'=>1,'ability'=>true],
                ['table'=>'deal','method'=>2,'ability'=>true],
                ['table'=>'stock','method'=>1,'ability'=>true],
                ['table'=>'stock','method'=>2,'ability'=>true],
                ['table'=>'stock','method'=>3,'ability'=>true],
                ['table'=>'brand','method'=>1,'ability'=>true],
                ['table'=>'brand','method'=>2,'ability'=>true],
                ['table'=>'modal','method'=>1,'ability'=>true],
                ['table'=>'modal','method'=>2,'ability'=>true],
                ['table'=>'category','method'=>1,'ability'=>true],
                ['table'=>'category','method'=>2,'ability'=>true],
        
          ];
 }   

 public function editGrn()
 {
    return[
                ['controller'=>'good','method'=>3,'ability'=>true],
                ['controller'=>'good','method'=>4,'ability'=>true],
                ['controller'=>'deal','method'=>3,'ability'=>true],
                ['controller'=>'deal','method'=>4,'ability'=>true],
               
          ];
 }   
public function addSale()
{
    return[
        ['table'=>'good','method'=>1,'ability'=>true],
        ['table'=>'good','method'=>2,'ability'=>true],
        ['table'=>'deal','method'=>1,'ability'=>true],
        ['table'=>'deal','method'=>2,'ability'=>true],
        ['table'=>'deal','method'=>3,'ability'=>true],
        ['table'=>'stock','method'=>1,'ability'=>true],
        ['table'=>'stock','method'=>2,'ability'=>true],
        ['table'=>'stock','method'=>3,'ability'=>true],
        ['table'=>'brand','method'=>1,'ability'=>true],
        ['table'=>'brand','method'=>2,'ability'=>true],
        ['table'=>'modal','method'=>1,'ability'=>true],
        ['table'=>'modal','method'=>2,'ability'=>true],
        ['table'=>'category','method'=>1,'ability'=>true],
        ['table'=>'category','method'=>2,'ability'=>true],

  ];



}
public function editSale()
{
   return[
               ['table'=>'good','method'=>3,'ability'=>true],
               ['table'=>'good','method'=>4,'ability'=>true],
               ['table'=>'deal','method'=>3,'ability'=>true],
               ['table'=>'deal','method'=>4,'ability'=>true],
              
         ];
} 




}