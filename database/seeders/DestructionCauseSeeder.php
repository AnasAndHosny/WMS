<?php

namespace Database\Seeders;

use App\Models\DestructionCause;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestructionCauseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $causes = [
            [
                'name_en' => 'first cause',
                'name_ar' => 'السبب الأول'
            ],
            [
                'name_en' => 'second cause',
                'name_ar' => 'السبب الثاني'
            ],
            [
                'name_en' => 'third cause',
                'name_ar' => 'السبب الثالث'
            ],
            [
                'name_en' => 'fourth cause',
                'name_ar' => 'السبب الرابع'
            ],
        ];

        foreach ($causes as $cause) {
            DestructionCause::query()->create([
                'name_en' => $cause['name_en'],
                'name_ar' => $cause['name_ar']
            ]);
        }
    }
}
