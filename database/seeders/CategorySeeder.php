<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Adds the default categories (like worthepenny). Safe to run again:
 * existing categories are kept, nothing is deleted.
 *   php artisan db:seed --class=CategorySeeder
 */
class CategorySeeder extends Seeder
{
    public const TREE = [
        'Food & Drink' => ['Beverages', 'Cooking & Recipes', 'Groceries', 'Restaurants & Delivery'],
        'Games' => ['Board & Card Games', 'Other Games', 'Puzzles & Brainteasers', 'Roleplaying Games', 'Video Games Consoles & Accessories'],
        'Health' => ['Dentist & Dental Services', 'Nutrition, Diets & Fitness', 'Pharmacy', 'Personal Care'],
        'Hobbies & Leisure' => ['Antiques & Collectibles', 'Camping, Scouting & Outdoors', 'Crafts', 'Music & Instruments'],
        'Home & Garden' => ['Furniture', 'Gardening', 'Home Improvement & Maintenance', 'Interior Design'],
        'Lifestyle' => ['Beauty & Cosmetics', 'Fashion & Apparel', 'Gifts & Flowers', 'Jewelry & Luxury Products'],
        'Pets & Animals' => ['Birds', 'Fish & Aquaria', 'Horses', 'Pet Food & Supplies'],
        'Sports' => ['Basketball', 'Cricket', 'Cycling & Biking', 'Fishing'],
        'Technology' => ['Computers & Laptops', 'Mobile Phones', 'Software & Apps', 'Web Hosting'],
        'Travel' => ['Flights', 'Hotels', 'Car Rental', 'Luggage & Travel Gear'],
    ];

    public function run(): void
    {
        $order = 0;
        foreach (self::TREE as $main => $subs) {
            $parent = Category::firstOrCreate(
                ['slug' => self::slug($main)],
                ['name' => $main, 'sort_order' => $order++],
            );
            foreach ($subs as $i => $sub) {
                Category::firstOrCreate(
                    ['slug' => self::slug($sub)],
                    ['name' => $sub, 'parent_id' => $parent->id, 'sort_order' => $i],
                );
            }
        }
    }

    public static function slug(string $name): string
    {
        return Str::slug(str_replace('&', 'and', $name));
    }
}
