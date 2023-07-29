<?php

namespace App\Modules\PromisedPayments;


interface PromisedPaymentRepositoryInterface
{
    public function getAll();
    
    public function getById($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);

    public function deleteIfAny($dealId);
    
    public function updateByDealId(array $data);

    public function getAllWithoutPaginate();

}
