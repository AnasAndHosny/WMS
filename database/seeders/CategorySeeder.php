<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_ar' => 'تسالي',
                'name_en' => 'Pastimes',
                'subcategories' => [
                    [
                        'image' => null,
                        'name_ar' => 'شيبس',
                        'name_en' => 'ships'
                    ],
                    [
                        'image' => null,
                        'name_ar' => 'بسكويت',
                        'name_en' => 'biscuit'
                    ],

                ]
            ],
            [
                'name_ar' => 'البقوليات',
                'name_en' => 'legumes',
                'subcategories' => [
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
                'subcategories' => [
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
            [
                'name_ar' => 'منظفات',
                'name_en' => 'Cleaners',
                'subcategories' => [
                    [
                        'image' => null,
                        'name_ar' => 'سائل غسيل',
                        'name_en' => 'Laundry liquid'
                    ],
                    [
                        'image' => null,
                        'name_ar' => 'سائل جلي',
                        'name_en' => 'Dishwashing liquid'
                    ],

                ]
            ]
        ];

        foreach ($categories as $category) {
            $createdCategory = Category::create([
                'name_ar' => $category['name_ar'],
                'name_en' => $category['name_en'],

            ]);

            $createdCategory->subCategories()->createMany($category['subcategories']);
        }
    }
}
