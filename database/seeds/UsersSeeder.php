<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nickname' => 'maxflex',
            'realname' => 'Максим Колядин',
            'password' => '184005',
            'email' => 'm@kolyadin.com',
        ]);
    }
}
