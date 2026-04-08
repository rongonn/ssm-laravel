<?php

namespace Isotope\Metronic\Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uuid'     => Str::uuid(),
            'name'     => "admin",
            'email'    => "admin@isotopeit.com",
            'password' => Hash::make('admin123'),
            'role_id'  => 1
        ]);
    }
}

