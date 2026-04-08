<?php

namespace Isotope\Metronic\Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Isotope\Metronic\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id'    => 1,
            'uuid'  => Str::uuid(),
            'title' => 'Admin',
            'alias' => 'admin',
        ]);
        
        Role::create([
            'id'    => 2,
            'uuid'  => Str::uuid(),
            'title' => 'Company Admin',
            'alias' => 'company_admin',
        ]);
    }
}
