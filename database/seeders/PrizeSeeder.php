<?php

namespace Database\Seeders;

use App\Models\Prize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prizes = [
            ['name' => 'Smart Television 55 inches DEVANT*', 'quantity' => 1],
            ['name' => 'BLAKK Chiller freezer*', 'quantity' => 1],
            ['name' => 'Wide storage shelves 5 layers-', 'quantity' => 4],
        ];

        foreach ($prizes as $prize) {
            Prize::create($prize);
        }
    }
}
