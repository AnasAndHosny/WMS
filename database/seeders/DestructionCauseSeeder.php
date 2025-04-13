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
                'name_en' => 'Product Expired',
                'name_ar' => 'انتهت صلاحية المنتج'
            ],
            [
                'name_en' => 'Product Spoiled',
                'name_ar' => 'المنتج فسد'
            ],
            [
                'name_en' => 'Product Damaged',
                'name_ar' => 'المنتج تضرر'
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
