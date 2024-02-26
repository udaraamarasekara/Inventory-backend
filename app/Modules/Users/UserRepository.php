<?php

namespace App\Modules\Users;

use App\Models\Profession;
use App\Models\User;
use App\Modules\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::paginate(10);
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        $data['password']=Hash::make($data['password']); 
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->getById($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->getById($id);
        $user->delete();
    }

    public function login($email)
    {
     return  User::where('email',$email)->get();
    }

    public function searchUser($input)
    {
      return  User::select( "id","name")
        ->where('name', 'LIKE', '%' .$input. '%')
        ->orWhere('email', 'LIKE', '%' .$input. '%')
        ->orWhere('role', 'LIKE', '%' .$input. '%')
        ->get();
    }

    public function searchCustomer($input)
    {
        return  User::select( "id","name")
        ->where([['name', 'LIKE', '%' .$input. '%'],['role','customer']])
        ->orWhere([['email', 'LIKE', '%' .$input. '%'],['role','customer']])
        ->get(); 
    }

    public function searchSupplier($input)
    {
        return  User::select( "id","name")
        ->where([['name', 'LIKE', '%' .$input. '%'],['role','supplier']])
        ->orWhere([['email', 'LIKE', '%' .$input. '%'],['role','supplier']])
        ->get(); 
    }

    public function people($type)
    {
      return User::where('role',$type)->count();  
    }

    public function professions()
    {
      return Profession::count();  
    }

    public function getProfessions()
    {
       return Profession::all(); 
    }
}
