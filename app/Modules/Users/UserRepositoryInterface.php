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

    public function searchUser($input);

    public function searchCustomer($input);

    public function searchSupplier($input);

    public function people($type);

    public function professions();

    public function getProfessions();

}
