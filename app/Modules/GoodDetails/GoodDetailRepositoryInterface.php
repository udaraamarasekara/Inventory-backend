<?php

namespace App\Modules\GoodDetails;


interface GoodDetailRepositoryInterface
{
    public function getAll(string $type);
    
    public function getById(string $type,$id);
    
    public function create(string $type,array $data);
    
    public function update(string $type,$id, array $data);
    
    public function delete(string $type,$id);

    public function getAllWithoutPaginate(string $type);

    public function searchBrands($input);

    public function searchModals($input);

    public function searchCategories($input);

    public function getBrand($text);

    public function getModal($text);

    public function getCategory($text);


}
