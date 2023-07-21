<?php

namespace App\Modules\GoodDetails;


interface GoodDetailRepositoryInterface
{
    public function getAll(string $type);
    
    public function getById(string $type,$id);
    
    public function create(string $type,array $data);
    
    public function update(string $type,$id, array $data);
    
    public function delete(string $type,$id);

}
