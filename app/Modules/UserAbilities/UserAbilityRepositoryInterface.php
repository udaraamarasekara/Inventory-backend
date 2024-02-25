<?php

namespace App\Modules\UserAbilities;


interface UserAbilityRepositoryInterface
{    
    public function getById($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);
}
