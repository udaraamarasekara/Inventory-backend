<?php

namespace App\Modules\Goods;


interface GoodRepositoryInterface
{
    public function getAll();
    
    public function getById($id);

    public function getByDealId($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);

    public function allGoods(array $data,int $page);
   
    public function salesTotalCost(array $data);

    public function allGoodDetailDeals(array $ids,array $data);   

    public function data(array $ids);  
    
    public function goodsCount();

    public function productTransactionCount();

    public function searchGood($input);

    public function getDealGroupId();
    
    public function deleteByDealId($id);

    public function searchColumn($type,$text);

}
