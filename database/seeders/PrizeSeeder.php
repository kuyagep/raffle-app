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
            ['name' => 'Bin organizer-', 'quantity' => 6],
            ['name' => 'Flat plates-', 'quantity' => 6],
            ['name' => 'Comforter set king-', 'quantity' => 2],
            ['name' => 'Kitchen classic (salad pyrex)-', 'quantity' => 5],
            ['name' => 'Bluetooth speaker-', 'quantity' => 1],
            ['name' => 'Rice 5 kls..', 'quantity' => 40],
            ['name' => 'Stand fan violet (LBP, Digos)-', 'quantity' => 1],
            ['name' => 'Rice cooker medium size (LBP, Digos)-', 'quantity' => 1],
            ['name' => 'Flat iron (LBP, Digos)-', 'quantity' => 1],
            ['name' => 'BAGS mix..', 'quantity' => 22],
            ['name' => 'Unisex belt..', 'quantity' => 2],
            ['name' => 'Tumblers..', 'quantity' => 24],
            ['name' => 'Pouches', 'quantity' => 18],
            ['name' => '20 boxes Cali per 20\'s', 'quantity' => 20],
            ['name' => 'Stand fan (CLEAREX)-', 'quantity' => 2],
            ['name' => 'Rice 10 kls..', 'quantity' => 25],
            ['name' => 'Professional office laser PPT flip pen', 'quantity' => 10],
            ['name' => 'SODEXO Gift certificate@500.00 (BDO)-', 'quantity' => 10],
            ['name' => 'Stand fan (CSB)-', 'quantity' => 2],
            ['name' => 'Bluetooth speaker (CSB)-', 'quantity' => 1],
            ['name' => 'Efficascent/Bioderm soap..', 'quantity' => 23],
            ['name' => 'Gift voucher..', 'quantity' => 15],
            ['name' => 'Cash - 1,000.00..', 'quantity' => 45],
            ['name' => 'Sack rice (5 kls)..', 'quantity' => 20],

            // Province Prizes
            ['name' => 'Sacks Rice 5 kls (Province)..', 'quantity' => 200],
            ['name' => 'Mugs (Province)..', 'quantity' => 200],
            ['name' => 'Frying Fan (Province)..', 'quantity' => 5],
            ['name' => 'Oven Toaster (Province)..', 'quantity' => 5],
            ['name' => 'Stand Fan (Province)..', 'quantity' => 12],
            ['name' => 'Flat Iron (Province)..', 'quantity' => 9],
            ['name' => 'Pillow (Province)..', 'quantity' => 20],
            ['name' => 'Glass set (Province)..', 'quantity' => 5],
            ['name' => 'Bed Sheet (Province)..', 'quantity' => 5],
            ['name' => 'Towel (Province)..', 'quantity' => 20],

            // TSM and Sta. Catalina
            ['name' => 'Mugs (TSM)..', 'quantity' => 36],
            ['name' => 'Kalamansi Concentrated (TSM)..', 'quantity' => 159],
            ['name' => 'Black Rice (Sta. Catalina)..', 'quantity' => 50],
        ];

        foreach ($prizes as $prize) {
            Prize::create($prize);
        }
    }
}
