<?php

namespace App\Policies;
use App\Models\UserAbility;

use App\Models\User;

class GoodPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user)
    {
        // Logic to determine if the user can view the post
        $ability=false;
        $professions=[];
        foreach($user->employees as $employee)
        {
          $professions[]=$employee->profession_id;
        }
        foreach($professions as $profession)
        {
            UserAbility::where([['table','good'],['method','view'],['profession_id',$profession]])->first()?->ability===1 && $ability=true;
        }
        return $ability===true || $user->role==='admin';// Only admins can create user
    }

    public function create(User $user)
    {
        $ability=false;
        $professions=[];
        // Logic to determine if the user can create user
        foreach($user->employees as $employee)
        {
          $professions[]=$employee->profession_id;
        }
        foreach($professions as $profession)
        {
            UserAbility::where([['table','good'],['method','create'],['profession_id',$profession]])->first()?->ability===1 && $ability=true;
        }
        return $ability===true || $user->role==='admin';
    }

    public function update(User $user,User $userObj)
    {   $ability=false;
        $professions=[];
        // Logic to determine if the user can update the post
        foreach($user->employees as $employee)
        {
          $professions[]=$employee->profession_id;
        }
        foreach($professions as $profession)
        {
            UserAbility::where([['table','good'],['method','edit'],['profession_id',$profession]])->first()?->ability===1 && $ability=true;
        }
        return $ability===true || $user->role==='admin';
        // Admins or the owner of the post can update
    }

    public function delete(User $user)
    {
        $ability=false;
        $professions=[];
        // Logic to determine if the user can delete the post
        foreach($user->employees as $employee)
        {
          $professions[]=$employee->profession_id;
        }
        foreach($professions as $profession)
        {
            UserAbility::where([['table','good'],['method','delete'],['profession_id',$profession]])->first()?->ability===1 && $ability=true;
        }
        return $ability===true || $user->role==='admin';
        // Admins or the owner of the post can delete
    }
}
