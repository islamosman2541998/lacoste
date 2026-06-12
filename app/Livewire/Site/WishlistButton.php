<?php

namespace App\Livewire\Site;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

class WishlistButton extends Component
{
    public int $productId;

    public bool $wished = false;

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->wished = $this->isWished();
    }

    public function toggleWishlist(): void
    {
        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($this->productId);

        $customerId = $this->customerId();

        if (! $customerId) {
            $this->dispatch('site-toast',
                type: 'warning',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'تسجيل الدخول مطلوب' : 'Login required',
                message: app()->getLocale() === 'ar'
                    ? 'سجل الدخول أولًا لإضافة المنتج إلى المفضلة'
                    : 'Please login first to add this product to wishlist'
            );

            return;
        }

        $existing = WishlistItem::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            $this->wished = false;

            $this->dispatch('site-toast',
                type: 'success',
                icon: '✓',
                title: app()->getLocale() === 'ar' ? 'تم الحذف' : 'Removed',
                message: app()->getLocale() === 'ar'
                    ? 'تم حذف المنتج من المفضلة'
                    : 'Product has been removed from wishlist'
            );

            return;
        }

        WishlistItem::query()->create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
        ]);

        $this->wished = true;

        $this->dispatch('site-toast',
            type: 'success',
            icon: '♥',
            title: app()->getLocale() === 'ar' ? 'تمت الإضافة' : 'Added',
            message: app()->getLocale() === 'ar'
                ? 'تم إضافة المنتج إلى المفضلة'
                : 'Product has been added to wishlist'
        );
    }

    private function isWished(): bool
    {
        $customerId = $this->customerId();

        if (! $customerId) {
            return false;
        }

        return WishlistItem::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $this->productId)
            ->exists();
    }

    private function customerId(): ?int
    {
        try {
            if (Auth::guard('customer')->check()) {
                return Auth::guard('customer')->id();
            }
        } catch (Throwable $e) {
            return null;
        }

        return null;
    }

    public function render()
    {
        return view('livewire.site.wishlist-button');
    }
}