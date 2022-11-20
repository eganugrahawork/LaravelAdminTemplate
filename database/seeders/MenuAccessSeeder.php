<?php

namespace Database\Seeders;

use App\Models\MenuAccess;
use Illuminate\Database\Seeder;

class MenuAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuAccess::create([
            'role_id' => 1,
            'menu_id'=> 1
        ]);
        MenuAccess::create([
            'role_id' => 1,
            'menu_id'=> 2
        ]);
        MenuAccess::create([
            'role_id' => 1,
            'menu_id'=> 3
        ]);
        MenuAccess::create([
            'role_id' => 1,
            'menu_id'=> 4
        ]);
    }
}
