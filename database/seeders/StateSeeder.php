<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            [
                'name_ar'=> 'المزه',
                'name_en' => 'Mazzeh',
                'city_id' => 4
            ],
            [
                'name_ar'=> 'الميدان',
                'name_en' => 'Middan',
                'city_id' => 4
            ],
            [
                'name_ar'=> 'قدسيا',
                'name_en' => 'Qudsia',
                'city_id' => 4
            ],
        ];

        foreach ($states as $state) {
            State::create([
                'name_ar' => $state['name_ar'],
                'name_en' => $state['name_en'],
                'city_id' => $state['city_id'],
            ]);
        }
    }
}
