<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategoties = [
            [
                'name_ar' => 'تسالي',
                'name_en' => 'Pastimes',
                'products' => [
                    [
                        'image' => null,
                        'name_ar' => '',
                        'name_en' => ''
                    ],
                    [
                        'image' => null,
                        'name_ar' => '',
                        'name_en' => ''
                    ],

                ]
            ],
            [
                'name_ar' => 'البقوليات',
                'name_en' => 'legumes',
                'products' => [
                    [
                        'image' => '',
                        'name_ar' => 'برغل',
                        'name_en' => 'Bulgur'
                    ],
                    [
                        'image' => '',
                        'name_ar' => 'رز',
                        'name_en' => 'Rice'
                    ],
                    
                ]
            ],
            [
                'name_ar' => 'مستلزمات شخصية',
                'name_en' => 'Personal supplies',
                'products' => [
                    [
                        'image' => '',
                        'name_ar' => 'عناية بالأسنان',
                        'name_en' => 'Toothpaste and toothbrushes '
                    ],
                    [
                        'image' => '',
                        'name_ar' => 'عناية بالبشرة',
                        'name_en' => 'Skin Crae'
                    ],
                   
                ]
            ],
        ]
    }
}
