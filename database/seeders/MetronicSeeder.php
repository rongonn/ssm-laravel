<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Isotope\Metronic\Database\Seeders\RoleTableSeeder;
use Isotope\Metronic\Database\Seeders\UserTableSeeder;

class MetronicSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleTableSeeder::class,
            UserTableSeeder::class,
        ]);
    }
}
