<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $emails = ['admin@admin.com'];
        foreach ($emails as $email) {
            User::create(['email' => $email, 'name' => 'Quang Hưng', 'password' => bcrypt('altplus')]);
        }
    }
}
