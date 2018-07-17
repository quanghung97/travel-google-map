<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Repositories\Facades\UserRepository;

class AssignRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        UserRepository::find(1)->assignRole('admin');
    }
}
