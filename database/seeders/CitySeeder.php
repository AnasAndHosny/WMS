<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name_en' => 'Aleppo',
                'name_ar' => 'حلب'
            ],
            [
                'name_en' => 'Raqqa',
                'name_ar' => 'الرقة'
            ],
            [
                'name_en' => 'As_Suwayda',
                'name_ar' => 'السويداء'
            ],
            [
                'name_en' => 'Damascus',
                'name_ar' => 'دمشق'
            ],
            [
                'name_en' => 'Daraa',
                'name_ar' => 'درعا'
            ],
            [
                'name_en' => 'Deir ez_zor',
                'name_ar' => 'دير الزور'
            ],
            [
                'name_en' => 'Hama',
                'name_ar' => 'حماه'
            ],
            [
                'name_en' => 'Al-Hasakah',
                'name_ar' => 'الحسكة'
            ],
            [
                'name_en' => 'Homs',
                'name_ar' => 'حمص'
            ],
            [
                'name_en' => 'Idlib',
                'name_ar' => 'إدلب'
            ],
            [
                'name_en' => 'Latakia',
                'name_ar' => 'اللاذقية'
            ],
            [
                'name_en' => 'Quneitra',
                'name_ar' => 'القنيطرة'
            ],
            [
                'name_en' => 'Rif Dimashq',
                'name_ar' => 'ريف دمشق'
            ],
            [
                'name_en' => 'Tartus',
                'name_ar' => 'طرطوس'
            ]
        ];

        foreach ($cities as $city){
            City::query()->create([
                'name_en' => $city['name_en'],
                'name_ar' => $city['name_ar']
            ]);
        }
    }
}
