<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClothingProductsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = $this->createBrands();

        $products = [
            [
                'category_en' => 'Men T-Shirts',
                'brand_en' => 'Lacoste',
                'name_ar' => 'تيشيرت رجالي قطن كلاسيك',
                'name_en' => 'Classic Cotton Men T-Shirt',
                'short_ar' => 'تيشيرت قطن ناعم مناسب للاستخدام اليومي.',
                'short_en' => 'Soft cotton t-shirt made for everyday comfort.',
                'description_ar' => 'تيشيرت رجالي بتصميم بسيط وخامة قطنية مريحة، مناسب للخروج اليومي والستايل الكاجوال.',
                'description_en' => 'A simple and comfortable men t-shirt made from soft cotton, perfect for casual daily outfits.',
                'price' => 850,
                'sale_price' => 720,
                'stock' => 35,
                'featured' => true,
            ],
            [
                'category_en' => 'Men Shirts',
                'brand_en' => 'Tommy Hilfiger',
                'name_ar' => 'قميص رجالي كاجوال',
                'name_en' => 'Casual Men Shirt',
                'short_ar' => 'قميص خفيف بتصميم أنيق ومريح.',
                'short_en' => 'Lightweight shirt with a clean casual look.',
                'description_ar' => 'قميص رجالي مناسب للخروج والعمل، بخامة مريحة وتصميم عصري.',
                'description_en' => 'A modern casual shirt suitable for work and outings with comfortable fabric.',
                'price' => 1250,
                'sale_price' => 1099,
                'stock' => 20,
                'featured' => true,
            ],
            [
                'category_en' => 'Men Jeans',
                'brand_en' => 'Levi’s',
                'name_ar' => 'جينز رجالي أزرق',
                'name_en' => 'Blue Men Jeans',
                'short_ar' => 'جينز عملي بقصة مريحة.',
                'short_en' => 'Practical jeans with a comfortable fit.',
                'description_ar' => 'جينز رجالي أزرق مناسب للاستخدام اليومي، يتميز بخامة قوية وقصة مريحة.',
                'description_en' => 'Blue men jeans designed for daily use with durable fabric and a comfortable fit.',
                'price' => 1600,
                'sale_price' => null,
                'stock' => 28,
                'featured' => true,
            ],
            [
                'category_en' => 'Men Jackets',
                'brand_en' => 'Zara',
                'name_ar' => 'جاكيت رجالي خفيف',
                'name_en' => 'Lightweight Men Jacket',
                'short_ar' => 'جاكيت خفيف مناسب للتقلبات الجوية.',
                'short_en' => 'Light jacket suitable for changing weather.',
                'description_ar' => 'جاكيت رجالي عصري بخامة خفيفة وسهلة التنسيق مع الملابس اليومية.',
                'description_en' => 'A modern lightweight jacket that is easy to style with daily outfits.',
                'price' => 2200,
                'sale_price' => 1899,
                'stock' => 15,
                'featured' => true,
            ],
            [
                'category_en' => 'Men Hoodies & Sweatshirts',
                'brand_en' => 'Nike',
                'name_ar' => 'هودي رجالي شتوي',
                'name_en' => 'Winter Men Hoodie',
                'short_ar' => 'هودي دافئ ومريح للشتاء.',
                'short_en' => 'Warm and comfortable hoodie for winter.',
                'description_ar' => 'هودي رجالي بخامة دافئة وتصميم عملي مناسب للشتاء والخروج الكاجوال.',
                'description_en' => 'A warm men hoodie with a practical design, ideal for winter and casual wear.',
                'price' => 1450,
                'sale_price' => 1299,
                'stock' => 30,
                'featured' => true,
            ],
            [
                'category_en' => 'Dresses',
                'brand_en' => 'Mango',
                'name_ar' => 'فستان حريمي صيفي',
                'name_en' => 'Summer Women Dress',
                'short_ar' => 'فستان خفيف بتصميم أنثوي بسيط.',
                'short_en' => 'Lightweight dress with a simple feminine design.',
                'description_ar' => 'فستان حريمي مناسب لفصل الصيف، بخامة خفيفة وتصميم أنيق.',
                'description_en' => 'A lightweight women dress perfect for summer with an elegant design.',
                'price' => 1850,
                'sale_price' => 1599,
                'stock' => 22,
                'featured' => true,
            ],
            [
                'category_en' => 'Women Blouses',
                'brand_en' => 'Zara',
                'name_ar' => 'بلوزة حريمي أنيقة',
                'name_en' => 'Elegant Women Blouse',
                'short_ar' => 'بلوزة أنيقة للخروج والعمل.',
                'short_en' => 'Elegant blouse for work and outings.',
                'description_ar' => 'بلوزة حريمي بتصميم راقي وخامة مريحة تناسب الإطلالات اليومية والرسمية.',
                'description_en' => 'An elegant women blouse with comfortable fabric suitable for daily and formal looks.',
                'price' => 1100,
                'sale_price' => 950,
                'stock' => 26,
                'featured' => true,
            ],
            [
                'category_en' => 'Women T-Shirts',
                'brand_en' => 'H&M',
                'name_ar' => 'تيشيرت حريمي Basic',
                'name_en' => 'Basic Women T-Shirt',
                'short_ar' => 'تيشيرت بسيط ومريح للاستخدام اليومي.',
                'short_en' => 'Simple and comfortable everyday t-shirt.',
                'description_ar' => 'تيشيرت حريمي أساسي بخامة قطنية ناعمة مناسب للتنسيقات اليومية.',
                'description_en' => 'A basic women t-shirt made from soft cotton, perfect for everyday styling.',
                'price' => 650,
                'sale_price' => null,
                'stock' => 40,
                'featured' => false,
            ],
            [
                'category_en' => 'Skirts',
                'brand_en' => 'Mango',
                'name_ar' => 'تنورة حريمي ميدي',
                'name_en' => 'Midi Women Skirt',
                'short_ar' => 'تنورة ميدي بتصميم عصري.',
                'short_en' => 'Modern midi skirt with a stylish cut.',
                'description_ar' => 'تنورة حريمي ميدي مناسبة للخروج والعمل بتصميم مريح وأنيق.',
                'description_en' => 'A stylish midi skirt suitable for work and outings with a comfortable fit.',
                'price' => 1350,
                'sale_price' => 1199,
                'stock' => 18,
                'featured' => false,
            ],
            [
                'category_en' => 'Women Jeans',
                'brand_en' => 'Levi’s',
                'name_ar' => 'جينز حريمي High Waist',
                'name_en' => 'High Waist Women Jeans',
                'short_ar' => 'جينز حريمي بوسط عالي وقصة مريحة.',
                'short_en' => 'High waist jeans with a comfortable fit.',
                'description_ar' => 'جينز حريمي بوسط عالي مناسب للإطلالات اليومية والكاجوال.',
                'description_en' => 'High waist women jeans designed for daily and casual outfits.',
                'price' => 1650,
                'sale_price' => 1399,
                'stock' => 24,
                'featured' => true,
            ],
            [
                'category_en' => 'Women Bags',
                'brand_en' => 'Aldo',
                'name_ar' => 'شنطة يد حريمي',
                'name_en' => 'Women Handbag',
                'short_ar' => 'شنطة يد أنيقة للاستخدام اليومي.',
                'short_en' => 'Elegant handbag for everyday use.',
                'description_ar' => 'شنطة يد حريمي بتصميم أنيق ومساحة مناسبة للاستخدام اليومي.',
                'description_en' => 'An elegant women handbag with practical space for everyday use.',
                'price' => 2100,
                'sale_price' => 1799,
                'stock' => 12,
                'featured' => true,
            ],
            [
                'category_en' => 'Sneakers',
                'brand_en' => 'Nike',
                'name_ar' => 'سنيكرز أبيض',
                'name_en' => 'White Sneakers',
                'short_ar' => 'سنيكرز مريح بتصميم عصري.',
                'short_en' => 'Comfortable sneakers with a modern design.',
                'description_ar' => 'سنيكرز أبيض مناسب للمشي والخروج اليومي مع تصميم عملي ومريح.',
                'description_en' => 'White sneakers suitable for walking and daily outfits with a comfortable design.',
                'price' => 2500,
                'sale_price' => 2199,
                'stock' => 17,
                'featured' => true,
            ],
            [
                'category_en' => 'Formal Shoes',
                'brand_en' => 'Aldo',
                'name_ar' => 'حذاء كلاسيك رجالي',
                'name_en' => 'Men Formal Shoes',
                'short_ar' => 'حذاء كلاسيك مناسب للمناسبات والعمل.',
                'short_en' => 'Formal shoes suitable for work and occasions.',
                'description_ar' => 'حذاء رجالي كلاسيك بتصميم أنيق وخامة قوية مناسب للمناسبات الرسمية.',
                'description_en' => 'Elegant men formal shoes with durable material, perfect for formal occasions.',
                'price' => 2900,
                'sale_price' => null,
                'stock' => 10,
                'featured' => false,
            ],
            [
                'category_en' => 'Kids T-Shirts',
                'brand_en' => 'H&M',
                'name_ar' => 'تيشيرت أطفال ملون',
                'name_en' => 'Colorful Kids T-Shirt',
                'short_ar' => 'تيشيرت أطفال مريح بألوان مبهجة.',
                'short_en' => 'Comfortable kids t-shirt with bright colors.',
                'description_ar' => 'تيشيرت أطفال بخامة قطنية ناعمة وتصميم مناسب للحركة اليومية.',
                'description_en' => 'A soft cotton kids t-shirt designed for daily movement and comfort.',
                'price' => 450,
                'sale_price' => 399,
                'stock' => 50,
                'featured' => false,
            ],
            [
                'category_en' => 'Kids Dresses',
                'brand_en' => 'Zara',
                'name_ar' => 'فستان أطفال',
                'name_en' => 'Kids Dress',
                'short_ar' => 'فستان أطفال أنيق ومريح.',
                'short_en' => 'Elegant and comfortable kids dress.',
                'description_ar' => 'فستان أطفال مناسب للخروج والمناسبات بتصميم لطيف وخامة مريحة.',
                'description_en' => 'A cute kids dress suitable for outings and occasions with comfortable fabric.',
                'price' => 900,
                'sale_price' => 799,
                'stock' => 16,
                'featured' => false,
            ],
            [
                'category_en' => 'Backpacks',
                'brand_en' => 'Adidas',
                'name_ar' => 'شنطة ظهر عملية',
                'name_en' => 'Practical Backpack',
                'short_ar' => 'شنطة ظهر مناسبة للجامعة والخروج.',
                'short_en' => 'Backpack suitable for university and daily use.',
                'description_ar' => 'شنطة ظهر عملية بمساحات متعددة وتصميم مريح للاستخدام اليومي.',
                'description_en' => 'A practical backpack with multiple compartments and comfortable daily design.',
                'price' => 1200,
                'sale_price' => 999,
                'stock' => 19,
                'featured' => false,
            ],
            [
                'category_en' => 'Belts',
                'brand_en' => 'Aldo',
                'name_ar' => 'حزام جلد كلاسيك',
                'name_en' => 'Classic Leather Belt',
                'short_ar' => 'حزام جلد أنيق وسهل التنسيق.',
                'short_en' => 'Elegant leather belt easy to style.',
                'description_ar' => 'حزام جلد كلاسيك مناسب للملابس الرسمية والكاجوال.',
                'description_en' => 'A classic leather belt suitable for formal and casual outfits.',
                'price' => 600,
                'sale_price' => null,
                'stock' => 30,
                'featured' => false,
            ],
            [
                'category_en' => 'Sunglasses',
                'brand_en' => 'Aldo',
                'name_ar' => 'نظارة شمس عصرية',
                'name_en' => 'Modern Sunglasses',
                'short_ar' => 'نظارة شمس بتصميم عصري.',
                'short_en' => 'Modern sunglasses with a stylish look.',
                'description_ar' => 'نظارة شمس مناسبة للخروج اليومي بإطار أنيق وخفيف.',
                'description_en' => 'Modern sunglasses suitable for daily outings with a lightweight frame.',
                'price' => 950,
                'sale_price' => 799,
                'stock' => 14,
                'featured' => false,
            ],
            [
                'category_en' => 'Summer Wear',
                'brand_en' => 'H&M',
                'name_ar' => 'طقم صيفي خفيف',
                'name_en' => 'Light Summer Outfit',
                'short_ar' => 'طقم صيفي مريح وخفيف.',
                'short_en' => 'Light and comfortable summer outfit.',
                'description_ar' => 'طقم صيفي مناسب للأجواء الحارة بخامة خفيفة وتصميم عملي.',
                'description_en' => 'A light summer outfit designed for hot weather with practical comfort.',
                'price' => 1550,
                'sale_price' => 1399,
                'stock' => 18,
                'featured' => true,
            ],
            [
                'category_en' => 'Winter Wear',
                'brand_en' => 'Nike',
                'name_ar' => 'سويت شيرت شتوي',
                'name_en' => 'Winter Sweatshirt',
                'short_ar' => 'سويت شيرت دافئ ومريح.',
                'short_en' => 'Warm and comfortable winter sweatshirt.',
                'description_ar' => 'سويت شيرت مناسب للشتاء بخامة ناعمة وتصميم كاجوال.',
                'description_en' => 'A winter sweatshirt with soft fabric and a casual comfortable design.',
                'price' => 1350,
                'sale_price' => 1199,
                'stock' => 25,
                'featured' => true,
            ],
        ];

        foreach ($products as $index => $productData) {
            $category = $this->findCategoryByEnglishName($productData['category_en']);
            $brand = $brands[$productData['brand_en']] ?? null;

            if (! $category) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                [
                    'sku' => 'CLTH-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                ],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brand?->id,
                    'barcode' => '622' . random_int(100000000, 999999999),
                    'main_image' => null,
                    'price' => $productData['price'],
                    'sale_price' => $productData['sale_price'],
                    'cost_price' => round($productData['price'] * 0.55, 2),
                    'stock_quantity' => $productData['stock'],
                    'low_stock_alert' => 5,
                    'manage_stock' => true,
                    'allow_backorder' => false,
                    'weight' => 0.5,
                    'is_active' => true,
                    'is_featured' => $productData['featured'],
                    'published_at' => now(),
                ]
            );

            $this->createOrUpdateTranslation(
                $product,
                'ar',
                $productData['name_ar'],
                $productData['short_ar'],
                $productData['description_ar']
            );

            $this->createOrUpdateTranslation(
                $product,
                'en',
                $productData['name_en'],
                $productData['short_en'],
                $productData['description_en']
            );
        }
    }

  private function createBrands(): array
{
    $brands = [
        ['ar' => 'لاكوست', 'en' => 'Lacoste'],
        ['ar' => 'نايكي', 'en' => 'Nike'],
        ['ar' => 'أديداس', 'en' => 'Adidas'],
        ['ar' => 'زارا', 'en' => 'Zara'],
        ['ar' => 'إتش آند إم', 'en' => 'H&M'],
        ['ar' => 'مانجو', 'en' => 'Mango'],
        ['ar' => 'ليفايس', 'en' => 'Levi’s'],
        ['ar' => 'تومي هيلفيغر', 'en' => 'Tommy Hilfiger'],
        ['ar' => 'ألدو', 'en' => 'Aldo'],
    ];

    $result = [];

    foreach ($brands as $index => $brandData) {
        $existingTranslation = BrandTranslation::query()
            ->where('locale', 'en')
            ->where('name', $brandData['en'])
            ->first();

        if ($existingTranslation) {
            $brand = $existingTranslation->brand;
        } else {
            $brand = Brand::query()->create([
                'logo' => null,
                'is_active' => true,
                'is_featured' => $index < 6,
                'sort_order' => $index + 1,
            ]);
        }

        BrandTranslation::query()->updateOrCreate(
            [
                'brand_id' => $brand->id,
                'locale' => 'ar',
            ],
            [
                'name' => $brandData['ar'],
                'slug' => $this->arabicSlug($brandData['ar']),
                'description' => 'منتجات ' . $brandData['ar'] . ' الأصلية بتصميمات عصرية.',
                'meta_title' => $brandData['ar'],
                'meta_description' => 'تسوق أحدث منتجات ' . $brandData['ar'] . ' داخل المتجر.',
                'meta_keywords' => $brandData['ar'] . ', ملابس, براند, تسوق',
            ]
        );

        BrandTranslation::query()->updateOrCreate(
            [
                'brand_id' => $brand->id,
                'locale' => 'en',
            ],
            [
                'name' => $brandData['en'],
                'slug' => Str::slug(str_replace('’', '', $brandData['en'])),
                'description' => $brandData['en'] . ' original products with modern styles.',
                'meta_title' => $brandData['en'],
                'meta_description' => 'Shop the latest ' . $brandData['en'] . ' products in our store.',
                'meta_keywords' => $brandData['en'] . ', clothing, brand, fashion',
            ]
        );

        $result[$brandData['en']] = $brand;
    }

    return $result;
}

    private function findCategoryByEnglishName(string $name): ?Category
    {
        $translation = CategoryTranslation::query()
            ->where('locale', 'en')
            ->where('name', $name)
            ->first();

        return $translation?->category;
    }

    private function createOrUpdateTranslation(
        Product $product,
        string $locale,
        string $name,
        string $shortDescription,
        string $description
    ): void {
        ProductTranslation::query()->updateOrCreate(
            [
                'product_id' => $product->id,
                'locale' => $locale,
            ],
            [
                'name' => $name,
                'slug' => $locale === 'ar' ? $this->arabicSlug($name) : Str::slug($name),
                'short_description' => $shortDescription,
                'description' => $description,
                'meta_title' => $name,
                'meta_description' => $shortDescription,
                'meta_keywords' => $name . ', clothing, fashion, store',
            ]
        );
    }

    private function arabicSlug(string $text): string
    {
        return trim(preg_replace('/\s+/u', '-', $text), '-');
    }
}