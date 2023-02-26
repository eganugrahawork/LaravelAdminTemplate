<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'parent' => 0,
            'name' => 'Dashboard',
            'url'=> '/admin/dashboard',
            'icon'=> 'speedometer',
            'status'=> 1
        ]);
        Menu::create([
            'parent' => 0,
            'name' => 'Users',
            'url'=> '/admin/users',
            'icon'=> '-',
            'status'=> 1
        ]);
        Menu::create([
            'parent' => 0,
            'name' => 'Master Data',
            'url'=> '/admin/masterdata',
            'icon'=> 'clipboard-data',
            'status'=> 1
        ]);
        Menu::create([
            'parent' => 3,
            'name' => 'Partner',
            'url'=> '/admin/partner',
            'icon'=> '-',
            'status'=> 1
        ]);
    }
}
