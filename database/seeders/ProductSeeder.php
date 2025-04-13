<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'image' => 'images/بوتاتو ستكس 70 غ نكهة الملح.jpeg',
                'name_ar' => 'بوتاتو ستكس 70 غ نكهة الملح',
                'name_en' => 'Potato Stakes 70 g Salt flavor',
                'description_ar' => 'بوتاتو ستكس 70 غ نكهة الملح',
                'description_en' => 'Potato Stakes 70 g Salt flavor',
                'manufacturer_id' => '3',
                'price' => '3000',
                'subcategory_id' => '1',
                'barcode' => null,
            ],
            [
                'image' => 'images/بوتاتو ستكس 70 غ يوبي.jpeg',
                'name_ar' => 'بوتاتو ستكس 70 غ يوبي',
                'name_en' => 'Potato Stakes 70 g Yubi ',
                'description_ar' => 'بوتاتو ستكس 70 غ يوبي',
                'description_en' => 'Potato Stakes 70 g Yubi ',
                'manufacturer_id' => '3',
                'price' => '3500',
                'subcategory_id' => '1',
                'barcode' => '15151555',
            ],
            [
                'image' => 'images/بوتاتو ستكس 70 غ نكهة الكتشب.jpeg',
                'name_ar' => 'بوتاتو ستكس 70 غ نكهة الكتشب',
                'name_en' => 'Potato Stakes 70 g Ketchup flavor',
                'description_ar' => 'بوتاتو ستكس 70 غ نكهة الكتشب',
                'description_en' => 'Potato Stakes 70 g Ketchup flavor',
                'manufacturer_id' => '3',
                'price' => '3500',
                'subcategory_id' => '1',
                'barcode' => '45645646',
            ],
            [
                'image' => 'images/بوتاتو ستكس 70 غ سريراتشا.jpeg',
                'name_ar' => 'بوتاتو ستكس 70 غ سريراتشا',
                'name_en' => 'Potato Stakes 70 g Sriracha flavor',
                'description_ar' => 'بوتاتو ستكس 70 غ سريراتشا',
                'description_en' => '
                Potato Stakes 70 g Sriracha',
                'manufacturer_id' => '3',
                'price' => '3000',
                'subcategory_id' => '1',
                'barcode' => '156545645',
            ],
            [
                'image' => 'images/سو وايت معجون اسنان حساسة 125مل.jpeg',
                'name_ar' => 'سو وايت معجون اسنان حساسة 125مل',
                'name_en' => 'So White Sensitive Toothpaste 125ml',
                'description_ar' => 'سو وايت معجون اسنان حساسة 125مل',
                'description_en' => 'So White Sensitive Toothpaste 125ml',
                'manufacturer_id' => '3',
                'price' => '12000',
                'subcategory_id' => '5',
                'barcode' => '564654654',
            ],
            [
                'image' => 'images/سو وايت معجون اسنان 125غ انتعاش دائم.jpeg',
                'name_ar' => 'سو وايت معجون اسنان 125غ انتعاش دائم',
                'name_en' => 'So White Toothpaste 125g Permanent Freshness',
                'description_ar' => 'سو وايت معجون اسنان 125غ انتعاش دائم',
                'description_en' => 'So White Toothpaste 125g Permanent Freshness',
                'manufacturer_id' => '3',
                'price' => '15000',
                'subcategory_id' => '5',
                'barcode' => '456456655',
            ],
            [
                'image' => 'images/سو وايت معجون اسنان بياض ناصع 125مل.jpeg',
                'name_ar' => 'سو وايت معجون اسنان بياض ناصع 125مل',
                'name_en' => 'So White Bright White Toothpaste 125 ml',
                'description_ar' => 'سو وايت معجون اسنان بياض ناصع 125مل',
                'description_en' => 'So White Bright White Toothpaste 125 ml',
                'manufacturer_id' => '3',
                'price' => '200000',
                'subcategory_id' => '5',
                'barcode' => '14545455',
            ],
            [
                'image' => 'images/اوليفا لوشن كريم الجسم 400مل.jpeg',
                'name_ar' => 'اوليفا لوشن كريم الجسم 400مل',
                'name_en' => 'Oliva body cream lotion 400 ml',
                'description_ar' => 'اوليفا لوشن كريم الجسم 400مل',
                'description_en' => 'Oliva body cream lotion 400 ml',
                'manufacturer_id' => '2',
                'price' => '250000',
                'subcategory_id' => '6',
                'barcode' => '78797788',
            ],
            [
                'image' => 'images/كليستو غسول وجه 200 مل.jpeg',
                'name_ar' => 'كليستو غسول وجه 200 مل',
                'name_en' => 'Cleisto face wash 200 ml',
                'description_ar' => 'كليستو غسول وجه 200 مل',
                'description_en' => 'Cleisto face wash 200 ml',
                'manufacturer_id' => '2',
                'price' => '700000',
                'subcategory_id' => '6',
                'barcode' => '8779779797',
            ],
            [
                'image' => 'images/ماتيز فازلين 50 مل.jpeg',
                'name_ar' => 'ماتيز فازلين 50 مل',
                'name_en' => 'Matiz Vaseline 50 ml',
                'description_ar' => 'ماتيز فازلين 50 مل',
                'description_en' => 'Matiz Vaseline 50 ml',
                'manufacturer_id' => '2',
                'price' => '455555',
                'subcategory_id' => '6',
                'barcode' => '877979878',
            ],

        ];

        foreach ($products as $product) {
            Product::create([
                'image' => $product['image'],
                'name_ar' => $product['name_ar'],
                'name_en' => $product['name_en'],
                'description_ar' => $product['description_ar'],
                'description_en' => $product['description_en'],
                'manufacturer_id' => $product['manufacturer_id'],
                'price' => $product['price'],
                'subcategory_id' => $product['subcategory_id'],
                'barcode' => $product['barcode']
            ]);
        }
    }
}
