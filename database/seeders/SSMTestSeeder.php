<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SSM\Models\Service;
use SSM\Models\Product;
use SSM\Models\Team;

class SSMTestSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'name' => 'Premium Haircut',
            'description' => 'Precision cutting and styling by our experts.',
            'price' => 500.00,
            'duration' => '45 min',
            'category' => 'Hair',
            'image_url' => 'https://example.com/haircut.jpg'
        ]);

        Product::create([
            'name' => 'Hair Growth Serum',
            'description' => 'Nutrient-rich serum for healthy hair.',
            'price' => 1200.00,
            'brand' => 'SSM Elite',
            'stock' => 50,
            'category' => 'Hair',
            'image_url' => 'https://example.com/serum.jpg'
        ]);

        Team::create([
            'name' => 'John Doe',
            'role' => 'Master Stylist',
            'bio' => '10 years of experience in luxury styling.',
            'specialty' => ['Haircut', 'Coloring'],
        ]);
    }
}
