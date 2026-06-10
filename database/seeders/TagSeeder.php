<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'VIP',        'color' => '#f59e0b'],
            ['name' => 'Hot Lead',   'color' => '#ef4444'],
            ['name' => 'Cold Lead',  'color' => '#3b82f6'],
            ['name' => 'Follow Up',  'color' => '#8b5cf6'],
            ['name' => 'Newsletter', 'color' => '#10b981'],
            ['name' => 'Partner',    'color' => '#6366f1'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag['name']], $tag);
        }
    }
}
