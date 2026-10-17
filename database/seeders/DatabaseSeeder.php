<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * php artisan migrate --seed
 * Creates: the admin user, the default categories, and a few demo stores + coupons.
 * Safe to run again (it updates instead of duplicating).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin user for /admin  (change these in .env BEFORE seeding!)
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            ['name' => 'Admin', 'password' => env('ADMIN_PASSWORD', 'password')],
        );

        // 2. Categories
        $this->call(CategorySeeder::class);
        $cat = fn (string $name) => Category::where('slug', CategorySeeder::slug($name))->value('id');

        // 3. Demo stores with their categories and coupons
        $stores = [
            [
                'store' => ['name' => 'Temu', 'slug' => 'temu', 'url' => 'https://www.temu.com', 'rating' => 4.5,
                    'button_text' => 'Visit Temu', 'is_featured' => true,
                    'about' => 'Temu is an online marketplace with low prices on fashion, home and electronics.'],
                'categories' => ['Fashion & Apparel', 'Gifts & Flowers', 'Furniture'],
                'coupons' => [
                    ['title' => 'Best Temu Coupon: Up To 30% Off', 'type' => 'code', 'code' => 'SAVE30', 'discount' => '30% OFF', 'verified' => true],
                    ['title' => 'Find Temu 99 Discount Code Here', 'type' => 'code', 'code' => 'TEMU99', 'discount' => '40% OFF'],
                    ['title' => 'Shop Temu Lightning Deals', 'type' => 'deal', 'discount' => 'UP TO 90%'],
                ],
            ],
            [
                'store' => ['name' => 'Chewy', 'slug' => 'chewy', 'url' => 'https://www.chewy.com', 'rating' => 4.7,
                    'button_text' => 'Visit Chewy', 'is_featured' => true,
                    'about' => 'Chewy sells pet food, toys and supplies.'],
                'categories' => ['Pet Food & Supplies'],
                'coupons' => [
                    ['title' => 'New Customer: $20 eGift Card With $49+ Order', 'type' => 'code', 'code' => 'WELCOME', 'discount' => '$20 OFF', 'verified' => true],
                ],
            ],
            [
                'store' => ['name' => "Domino's", 'slug' => 'dominos', 'url' => 'https://www.dominos.co.in', 'rating' => 3.8,
                    'button_text' => "Order at Domino's", 'is_featured' => true,
                    'about' => "Domino's Pizza delivery and takeaway."],
                'categories' => ['Restaurants & Delivery'],
                'coupons' => [
                    ['title' => 'Get 20% Off By Redeeming This Offer At Checkout', 'type' => 'code', 'code' => '1126', 'discount' => '20% OFF', 'verified' => true],
                    ['title' => 'Old expired coupon (hidden on the website)', 'type' => 'code', 'code' => 'OLD', 'discount' => '10% OFF', 'expires_at' => '2020-01-01'],
                ],
            ],
            [
                'store' => ['name' => '2K', 'slug' => '2k', 'url' => 'https://2k.com', 'rating' => 4.2],
                'categories' => ['Video Games Consoles & Accessories'],
                'coupons' => [
                    ['title' => 'Up To 50% Off Select Games', 'type' => 'deal', 'discount' => '50% OFF'],
                ],
            ],
            [
                'store' => ['name' => 'Animal Jam', 'slug' => 'animal-jam', 'url' => 'https://www.animaljam.com', 'rating' => 4.0],
                'categories' => ['Video Games Consoles & Accessories'],
                'coupons' => [],
            ],
            [
                'store' => ['name' => 'Bethesda', 'slug' => 'bethesda', 'url' => 'https://bethesda.net', 'rating' => 4.4],
                'categories' => ['Video Games Consoles & Accessories', 'Roleplaying Games'],
                'coupons' => [
                    ['title' => 'Extra 15% Off Bethesda Store', 'type' => 'code', 'code' => 'BETH15', 'discount' => '15% OFF'],
                ],
            ],
        ];

        foreach ($stores as $data) {
            $store = Store::updateOrCreate(['slug' => $data['store']['slug']], $data['store']);
            $store->categories()->sync(array_filter(array_map($cat, $data['categories'])));
            foreach ($data['coupons'] as $c) {
                Coupon::updateOrCreate(['store_id' => $store->id, 'title' => $c['title']], $c);
            }
        }
    }
}
