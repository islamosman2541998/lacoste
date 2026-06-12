<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClothingCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'ar' => 'رجالي',
                'en' => 'Men',
                'featured' => true,
                'children' => [
                    ['ar' => 'تيشيرتات رجالي', 'en' => 'Men T-Shirts'],
                    ['ar' => 'قمصان رجالي', 'en' => 'Men Shirts'],
                    ['ar' => 'بناطيل رجالي', 'en' => 'Men Pants'],
                    ['ar' => 'جينز رجالي', 'en' => 'Men Jeans'],
                    ['ar' => 'جاكيتات رجالي', 'en' => 'Men Jackets'],
                    ['ar' => 'هوديز وسويت شيرت رجالي', 'en' => 'Men Hoodies & Sweatshirts'],
                    ['ar' => 'ملابس رياضية رجالي', 'en' => 'Men Sportswear'],
                    ['ar' => 'أحذية رجالي', 'en' => 'Men Shoes'],
                    ['ar' => 'إكسسوارات رجالي', 'en' => 'Men Accessories'],
                ],
            ],
            [
                'ar' => 'حريمي',
                'en' => 'Women',
                'featured' => true,
                'children' => [
                    ['ar' => 'فساتين', 'en' => 'Dresses'],
                    ['ar' => 'بلوزات حريمي', 'en' => 'Women Blouses'],
                    ['ar' => 'تيشيرتات حريمي', 'en' => 'Women T-Shirts'],
                    ['ar' => 'تنانير', 'en' => 'Skirts'],
                    ['ar' => 'بناطيل حريمي', 'en' => 'Women Pants'],
                    ['ar' => 'جينز حريمي', 'en' => 'Women Jeans'],
                    ['ar' => 'جاكيتات حريمي', 'en' => 'Women Jackets'],
                    ['ar' => 'عبايات وكيمونو', 'en' => 'Abayas & Kimonos'],
                    ['ar' => 'ملابس رياضية حريمي', 'en' => 'Women Sportswear'],
                    ['ar' => 'أحذية حريمي', 'en' => 'Women Shoes'],
                    ['ar' => 'شنط حريمي', 'en' => 'Women Bags'],
                    ['ar' => 'إكسسوارات حريمي', 'en' => 'Women Accessories'],
                ],
            ],
            [
                'ar' => 'أطفال',
                'en' => 'Kids',
                'featured' => true,
                'children' => [
                    ['ar' => 'أولاد', 'en' => 'Boys'],
                    ['ar' => 'بنات', 'en' => 'Girls'],
                    ['ar' => 'بيبي', 'en' => 'Baby'],
                    ['ar' => 'تيشيرتات أطفال', 'en' => 'Kids T-Shirts'],
                    ['ar' => 'بناطيل أطفال', 'en' => 'Kids Pants'],
                    ['ar' => 'فساتين أطفال', 'en' => 'Kids Dresses'],
                    ['ar' => 'أحذية أطفال', 'en' => 'Kids Shoes'],
                ],
            ],
            [
                'ar' => 'أحذية',
                'en' => 'Shoes',
                'featured' => true,
                'children' => [
                    ['ar' => 'سنيكرز', 'en' => 'Sneakers'],
                    ['ar' => 'أحذية كلاسيك', 'en' => 'Formal Shoes'],
                    ['ar' => 'صنادل', 'en' => 'Sandals'],
                    ['ar' => 'بوت', 'en' => 'Boots'],
                    ['ar' => 'أحذية رياضية', 'en' => 'Sport Shoes'],
                ],
            ],
            [
                'ar' => 'شنط وإكسسوارات',
                'en' => 'Bags & Accessories',
                'featured' => true,
                'children' => [
                    ['ar' => 'شنط يد', 'en' => 'Handbags'],
                    ['ar' => 'شنط ظهر', 'en' => 'Backpacks'],
                    ['ar' => 'محافظ', 'en' => 'Wallets'],
                    ['ar' => 'أحزمة', 'en' => 'Belts'],
                    ['ar' => 'نظارات شمس', 'en' => 'Sunglasses'],
                    ['ar' => 'قبعات', 'en' => 'Caps'],
                    ['ar' => 'ساعات', 'en' => 'Watches'],
                ],
            ],
            [
                'ar' => 'ملابس موسمية',
                'en' => 'Seasonal Wear',
                'featured' => true,
                'children' => [
                    ['ar' => 'ملابس صيفية', 'en' => 'Summer Wear'],
                    ['ar' => 'ملابس شتوية', 'en' => 'Winter Wear'],
                    ['ar' => 'ملابس خروج', 'en' => 'Casual Wear'],
                    ['ar' => 'ملابس رسمية', 'en' => 'Formal Wear'],
                    ['ar' => 'ملابس منزلية', 'en' => 'Loungewear'],
                ],
            ],
            [
                'ar' => 'عروض وخصومات',
                'en' => 'Sale',
                'featured' => true,
                'children' => [
                    ['ar' => 'خصومات رجالي', 'en' => 'Men Sale'],
                    ['ar' => 'خصومات حريمي', 'en' => 'Women Sale'],
                    ['ar' => 'خصومات أطفال', 'en' => 'Kids Sale'],
                    ['ar' => 'تصفية الموسم', 'en' => 'Season Clearance'],
                ],
            ],
        ];

        $sortOrder = 1;

        foreach ($categories as $mainCategory) {
            $parent = Category::query()->create([
                'parent_id' => null,
                'image' => null,
                'is_active' => true,
                'is_featured' => $mainCategory['featured'] ?? false,
                'sort_order' => $sortOrder++,
            ]);

            $this->createTranslations(
                category: $parent,
                nameAr: $mainCategory['ar'],
                nameEn: $mainCategory['en']
            );

            $childSortOrder = 1;

            foreach ($mainCategory['children'] as $childCategory) {
                $child = Category::query()->create([
                    'parent_id' => $parent->id,
                    'image' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => $childSortOrder++,
                ]);

                $this->createTranslations(
                    category: $child,
                    nameAr: $childCategory['ar'],
                    nameEn: $childCategory['en']
                );
            }
        }
    }

    private function createTranslations(Category $category, string $nameAr, string $nameEn): void
    {
        CategoryTranslation::query()->create([
            'category_id' => $category->id,
            'locale' => 'ar',
            'name' => $nameAr,
            'slug' => $this->arabicSlug($nameAr),
            'description' => 'تسوق أحدث منتجات ' . $nameAr . ' بتصميمات عصرية وخامات مميزة.',
            'meta_title' => $nameAr,
            'meta_description' => 'اكتشف أفضل منتجات ' . $nameAr . ' داخل المتجر.',
            'meta_keywords' => $nameAr . ', ملابس, أزياء, تسوق, متجر ملابس',
        ]);

        CategoryTranslation::query()->create([
            'category_id' => $category->id,
            'locale' => 'en',
            'name' => $nameEn,
            'slug' => Str::slug($nameEn),
            'description' => 'Shop the latest ' . $nameEn . ' products with modern styles and premium quality.',
            'meta_title' => $nameEn,
            'meta_description' => 'Discover the best ' . $nameEn . ' products in our store.',
            'meta_keywords' => $nameEn . ', clothing, fashion, store, shopping',
        ]);
    }

    private function arabicSlug(string $text): string
    {
        return trim(preg_replace('/\s+/u', '-', $text), '-');
    }
}