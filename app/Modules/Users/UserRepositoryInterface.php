<?php

namespace App\Modules\Users;


interface UserRepositoryInterface
{
    public function getAll();
    
    public function getById($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);

    public function login($email);
}
