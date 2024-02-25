<?php

namespace App\Modules\Deals;


interface DealRepositoryInterface
{
    public function getAll();
    
    public function getById($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);

    public function sales(array $data);

    public function goods(array $data);

    public function salesIncome(array $data);
    
    public function udateByDealableId($id,string $data);

    public function deleteReleventToGood($id);

    public function grns(array $data);

    public function allTimeSales();

    public function allTimeGrns();

    public function getAllWithoutPaginate();
    
    public function getReleventDealsForGoods(array $dealIds);



}
