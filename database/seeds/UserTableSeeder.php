<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "seller{$i}",
                'email' => "seller{$i}@example.com",
                'password' => bcrypt('pass'),
                'role' => 'seller',
                'join_flag' => 1,
            ]);
        }
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "appointer{$i}",
                'email' => "appointer{$i}@example.com",
                'password' => bcrypt('pass'),
                'role' => 'appointer',
                'join_flag' => 1,
            ]);
        }
    }
}
