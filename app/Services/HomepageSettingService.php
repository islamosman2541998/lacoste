<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\HomepageSetting;
use App\Models\HomepageSlider;
use App\Models\Product;

class HomepageSettingService
{
    public function settings(): HomepageSetting
    {
        return HomepageSetting::current();
    }

    public function isActive(): bool
    {
        return (bool) $this->settings()->is_active;
    }

    public function sliderEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->slider_enabled;
    }

    public function featuredCategoriesEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->featured_categories_enabled;
    }

    public function featuredProductsEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->featured_products_enabled;
    }

    public function newProductsEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->new_products_enabled;
    }

    public function flashSalesEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->flash_sales_enabled;
    }

    public function brandsEnabled(): bool
    {
        return $this->isActive() && (bool) $this->settings()->brands_enabled;
    }

    public function featuredCategoriesTitle(): string
    {
        return $this->settings()->featured_categories_title;
    }

    public function featuredProductsTitle(): string
    {
        return $this->settings()->featured_products_title;
    }

    public function newProductsTitle(): string
    {
        return $this->settings()->new_products_title;
    }

    public function flashSalesTitle(): string
    {
        return $this->settings()->flash_sales_title;
    }

    public function brandsTitle(): string
    {
        return $this->settings()->brands_title;
    }

    public function featuredCategories()
    {
        if (! $this->featuredCategoriesEnabled()) {
            return collect();
        }

        return Category::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('transNow')
            ->orderBy('sort_order')
            ->limit($this->settings()->featured_categories_limit)
            ->get();
    }

    public function featuredProducts()
    {
        if (! $this->featuredProductsEnabled()) {
            return collect();
        }

        return Product::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with([
                'transNow',
                'brand.transNow',
                'category.transNow',
            ])
            ->latest()
            ->limit($this->settings()->featured_products_limit)
            ->get();
    }

    public function newProducts()
    {
        if (! $this->newProductsEnabled()) {
            return collect();
        }

        return Product::query()
            ->where('is_active', true)
            ->with([
                'transNow',
                'brand.transNow',
                'category.transNow',
            ])
            ->latest()
            ->limit($this->settings()->new_products_limit)
            ->get();
    }

    public function activeFlashSales()
    {
        if (! $this->flashSalesEnabled()) {
            return collect();
        }

        return FlashSale::query()
            ->running()
            ->with('activeItems.product.transNow')
            ->orderBy('sort_order')
            ->limit($this->settings()->flash_sales_limit)
            ->get();
    }

    public function brands()
    {
        if (! $this->brandsEnabled()) {
            return collect();
        }

        return Brand::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('transNow')
            ->orderBy('sort_order')
            ->limit($this->settings()->brands_limit)
            ->get();
    }
    public function sliders()
    {
        if (! $this->sliderEnabled()) {
            return collect();
        }

        return HomepageSlider::query()
            ->running()
            ->orderBy('sort_order')
            ->get();
    }
    public function homepageData(): array
    {
        return [
            'settings' => $this->settings(),

            'sliders' => $this->sliders(),

            'featured_categories' => [
                'enabled' => $this->featuredCategoriesEnabled(),
                'title' => $this->featuredCategoriesTitle(),
                'items' => $this->featuredCategories(),
            ],

            'featured_products' => [
                'enabled' => $this->featuredProductsEnabled(),
                'title' => $this->featuredProductsTitle(),
                'items' => $this->featuredProducts(),
            ],

            'new_products' => [
                'enabled' => $this->newProductsEnabled(),
                'title' => $this->newProductsTitle(),
                'items' => $this->newProducts(),
            ],

            'flash_sales' => [
                'enabled' => $this->flashSalesEnabled(),
                'title' => $this->flashSalesTitle(),
                'items' => $this->activeFlashSales(),
            ],

            'brands' => [
                'enabled' => $this->brandsEnabled(),
                'title' => $this->brandsTitle(),
                'items' => $this->brands(),
            ],
        ];
    }
}