<?php

namespace App\Modules\Users;

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
}
