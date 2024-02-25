<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=[['name'=>'udara','email'=>'admin@gmail.com','role'=>'admin','password'=>Hash::make('flashback1')],
        ['name'=>'unknownSupplier','email'=>'unknownSupplier@gmail.com','role'=>'supplier','password'=>Hash::make('unknownSupplier')],
        ['name'=>'unknownCustomer','email'=>'unknownCustomer@gmail.com','role'=>'customer','password'=>Hash::make('unknownCustomer')]
    ];
        foreach($users as $user)
        {
            User::create($user);
        }


    }
}
