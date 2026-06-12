<?php

namespace App\Livewire\Site;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $productId;

    public bool $added = false;

    public function addToCart(): void
    {
        $product = Product::query()
            ->with('transNow')
            ->where('is_active', true)
            ->findOrFail($this->productId);

        if ($product->manage_stock && $product->stock_quantity <= 0) {
            $this->dispatch('site-toast',
                type: 'error',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'غير متوفر' : 'Unavailable',
                message: app()->getLocale() === 'ar'
                    ? 'هذا المنتج غير متوفر حاليًا'
                    : 'This product is currently out of stock'
            );

            return;
        }

        $sessionId = session()->getId();

        $cart = Cart::query()->firstOrCreate(
            [
                'session_id' => $sessionId,
                'customer_id' => null,
                'status' => 'active',
            ],
            [
                'subtotal' => 0,
                'discount_total' => 0,
                'shipping_total' => 0,
                'tax_total' => 0,
                'grand_total' => 0,
                'last_activity_at' => now(),
            ]
        );

        $unitPrice = $this->getProductPrice($product);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->whereNull('product_variant_id')
            ->first();

        if ($item) {
            $newQuantity = $item->quantity + 1;

            $item->update([
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $newQuantity * $unitPrice,
                'snapshot' => $this->productSnapshot($product),
            ]);
        } else {
            CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => null,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice,
                'snapshot' => $this->productSnapshot($product),
            ]);
        }

        $this->recalculateCart($cart);

        $this->added = true;

        $this->dispatch('cart-updated');

        $this->dispatch('site-toast',
            type: 'success',
            icon: '✓',
            title: app()->getLocale() === 'ar' ? 'تمت الإضافة' : 'Added',
            message: app()->getLocale() === 'ar'
                ? 'تم إضافة المنتج إلى السلة'
                : 'Product has been added to cart'
        );
    }

    private function getProductPrice(Product $product): float
    {
        $price = (float) $product->price;
        $salePrice = $product->sale_price ? (float) $product->sale_price : null;

        if ($salePrice && $salePrice > 0 && $salePrice < $price) {
            return $salePrice;
        }

        return $price;
    }

    private function productSnapshot(Product $product): array
    {
        return [
            'name' => $product->transNow?->name,
            'sku' => $product->sku,
            'image' => $product->main_image,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
        ];
    }

    private function recalculateCart(Cart $cart): void
    {
        $subtotal = (float) $cart->items()->sum('subtotal');

        $cart->update([
            'subtotal' => $subtotal,
            'grand_total' => $subtotal
                - (float) $cart->discount_total
                + (float) $cart->shipping_total
                + (float) $cart->tax_total,
            'last_activity_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.site.add-to-cart-button');
    }
}