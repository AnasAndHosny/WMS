<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manufacturers = [
            [
                'name_ar' => 'ليتس',
                'name_en' => 'lets',
                'state_id' => '1',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'برافو',
                'name_en' => 'bravo',
                'state_id' => '2',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'سو وايت',
                'name_en' => 'so white',
                'state_id' => '3',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'كليستو',
                'name_en' => 'klesto',
                'state_id' => '1',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'الفخامة',
                'name_en' => 'alfakhama ',
                'state_id' => '2',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'سيدي هشام',
                'name_en' => 'mr hisham',
                'state_id' => '3',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
            [
                'name_ar' => 'حسين الناصر',
                'name_en' => 'hussine alnaser',
                'state_id' => '1',
                'street_address_ar' => 'عنوان',
                'street_address_en' => 'Address'
            ],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create([
                'name_ar' => $manufacturer['name_ar'],
                'name_en' => $manufacturer['name_en'],
                'state_id' => (int)$manufacturer['state_id'],
                'street_address_ar' => $manufacturer['street_address_ar'],
                'street_address_en' => $manufacturer['street_address_en'],
            ]);
        }
    }
}
