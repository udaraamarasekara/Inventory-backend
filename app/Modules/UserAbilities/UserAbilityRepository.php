<?php

namespace App\Modules\UserAbilities;

use App\Models\UserAbility;
use App\Modules\UserAbilities\UserAbilityRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserAbilityRepository implements UserAbilityRepositoryInterface
{
    public function getById($id)
    {
        return UserAbility::findOrFail($id);
    }

    public function create(array $data)
    {
        return UserAbility::create($data);
    }

    public function update($id, array $data)
    {
        $userAbility = $this->getById($id);
        $userAbility->update($data);
        return $userAbility;
    }

    public function delete($id)
    {
        $userAbility = $this->getById($id);
        $userAbility->delete();
    }
}
