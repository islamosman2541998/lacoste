<?php

namespace App\Livewire\Site;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use App\Services\ProductPricingService;
use App\Services\StockService;

class AddToCartButton extends Component
{
    public int $productId;

    #[Reactive]
    public ?int $variantId = null;

    #[Reactive]
    public ?int $quantity = 1;

    public bool $added = false;

    public function mount(int $productId, ?int $variantId = null, ?int $quantity = 1): void
    {
        $this->productId = $productId;
        $this->variantId = $variantId;
        $this->quantity = max(1, (int) ($quantity ?: 1));
    }

    public function addToCart(): void
    {
        $requestedQuantity = max(1, (int) ($this->quantity ?: 1));

        $this->quantity = $requestedQuantity;

        $product = Product::query()
            ->with([
                'transNow',
                'arabicTranslation',
                'englishTranslation',
                'discounts',
                'variants.transNow',
                'variants.arabicTranslation',
                'variants.englishTranslation',
                'variants.discounts',
                'variants.attributeValues.attribute',
                'variants.attributeValues.attributeValue',
            ])
            ->where('is_active', true)
            ->findOrFail($this->productId);

        $activeVariants = $product->variants
            ->where('is_active', true)
            ->values();

        $variant = null;

        if ($this->variantId) {
            $variant = $activeVariants
                ->firstWhere('id', $this->variantId);

            if (! $variant) {
                $this->dispatch(
                    'site-toast',
                    type: 'error',
                    icon: '!',
                    title: app()->getLocale() === 'ar' ? 'اختيار غير صحيح' : 'Invalid option',
                    message: app()->getLocale() === 'ar'
                        ? 'هذا الاختيار غير متاح حاليًا'
                        : 'This selected option is not available'
                );

                return;
            }
        }

        if ($activeVariants->count() && ! $variant) {
            $this->dispatch(
                'site-toast',
                type: 'warning',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'اختر المواصفات' : 'Choose options',
                message: app()->getLocale() === 'ar'
                    ? 'من فضلك افتح صفحة المنتج واختر المقاس أو اللون قبل الإضافة للسلة'
                    : 'Please open the product page and choose the required options before adding to cart'
            );

            return;
        }

        if (! app(StockService::class)->canAddToCart($product, $variant, $requestedQuantity)) {
            $this->dispatch(
                'site-toast',
                type: 'error',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'غير متوفر' : 'Unavailable',
                message: app()->getLocale() === 'ar'
                    ? 'الكمية المطلوبة غير متوفرة حاليًا'
                    : 'The requested quantity is not currently available'
            );

            return;
        }

        $sessionId = session()->getId();

        $customerId = null;

        try {
            $customerId = auth('customer')->id();
        } catch (\Throwable $e) {
            $customerId = null;
        }

        $cart = Cart::query()->firstOrCreate(
            [
                'session_id' => $sessionId,
                'customer_id' => $customerId,
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

        $pricing = $variant
            ? app(ProductPricingService::class)->getVariantPrice($variant)
            : app(ProductPricingService::class)->getProductPrice($product);

        $unitPrice = (float) $pricing['final_price'];

        $itemQuery = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $product->id);

        if ($variant) {
            $itemQuery->where('product_variant_id', $variant->id);
        } else {
            $itemQuery->whereNull('product_variant_id');
        }

        $item = $itemQuery->first();

        $currentQuantityInCart = $item ? (int) $item->quantity : 0;
        $newQuantity = $currentQuantityInCart + $requestedQuantity;

        if (! app(StockService::class)->canAddToCart(
            product: $product,
            variant: $variant,
            requestedQuantity: $requestedQuantity,
            currentCartQuantity: $currentQuantityInCart
        )) {
            $this->dispatch(
                'site-toast',
                type: 'error',
                icon: '!',
                title: app()->getLocale() === 'ar' ? 'كمية غير متاحة' : 'Quantity unavailable',
                message: app()->getLocale() === 'ar'
                    ? 'الكمية الموجودة في السلة مع الكمية المطلوبة أكبر من المخزون المتاح'
                    : 'The cart quantity plus requested quantity exceeds available stock'
            );

            return;
        }

        if ($item) {
            $item->update([
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $newQuantity * $unitPrice,
                'snapshot' => $this->productSnapshot($product, $variant, $pricing, $newQuantity),
            ]);
        } else {
            CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $requestedQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $requestedQuantity * $unitPrice,
                'snapshot' => $this->productSnapshot($product, $variant, $pricing, $requestedQuantity),
            ]);
        }

        $this->recalculateCart($cart);

        $this->added = true;

        $this->dispatch('cart-updated');

        $this->dispatch('cart-updated')->to(CartCounter::class);

        $this->dispatch(
            'site-toast',
            type: 'success',
            icon: '✓',
            title: app()->getLocale() === 'ar' ? 'تمت الإضافة' : 'Added',
            message: app()->getLocale() === 'ar'
                ? 'تم إضافة المنتج إلى السلة'
                : 'Product has been added to cart'
        );

        $this->dispatch(
            'site-toast',
            type: 'success',
            icon: '✓',
            title: app()->getLocale() === 'ar' ? 'تمت الإضافة' : 'Added',
            message: app()->getLocale() === 'ar'
                ? 'تم إضافة المنتج إلى السلة'
                : 'Product has been added to cart'
        );
    }





    private function runningDiscounts(Product $product, ?ProductVariant $variant): Collection
    {
        $productDiscounts = $product->discounts
            ->filter(function ($discount) use ($variant) {
                if (! $discount->isRunning()) {
                    return false;
                }

                if ($discount->product_variant_id && ! $variant) {
                    return false;
                }

                if ($discount->product_variant_id && $variant) {
                    return (int) $discount->product_variant_id === (int) $variant->id;
                }

                return true;
            });

        $variantDiscounts = $variant
            ? $variant->discounts->filter(fn($discount) => $discount->isRunning())
            : collect();

        return $productDiscounts
            ->merge($variantDiscounts)
            ->unique('id')
            ->sortByDesc('priority')
            ->values();
    }

    private function productSnapshot(Product $product, ?ProductVariant $variant, array $pricing, int $quantity): array
    {
        $productTranslation = $product->transNow
            ?? $product->arabicTranslation
            ?? $product->englishTranslation;

        $variantTranslation = $variant
            ? ($variant->transNow
                ?? $variant->arabicTranslation
                ?? $variant->englishTranslation)
            : null;

        return [
            'product' => [
                'id' => $product->id,
                'name' => $productTranslation?->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'image' => $product->main_image,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
            ],

            'variant' => $variant ? [
                'id' => $variant->id,
                'name' => $variantTranslation?->name,
                'sku' => $variant->sku,
                'barcode' => $variant->barcode,
                'image' => $variant->image,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'attributes' => $this->variantAttributesSnapshot($variant),
            ] : null,

            'unit_price' => (float) $pricing['final_price'],
            'original_unit_price' => (float) $pricing['original_price'],
            'discount_amount' => (float) $pricing['discount_amount'],
            'discount_source' => $pricing['discount_source'],
            'flash_sale_item_id' => $pricing['flash_sale_item_id'],
            'product_discount_id' => $pricing['product_discount_id'],
            'quantity' => $quantity,
        ];
    }

    private function variantAttributesSnapshot(ProductVariant $variant): array
    {
        return $variant->attributeValues
            ->map(function ($item) {
                $attribute = $item->attribute;
                $value = $item->attributeValue;

                $attributeName =
                    $attribute?->transNow?->name
                    ?? $attribute?->arabicTranslation?->name
                    ?? $attribute?->englishTranslation?->name
                    ?? $attribute?->name
                    ?? $attribute?->name_ar
                    ?? $attribute?->name_en
                    ?? null;

                $valueName =
                    $value?->transNow?->name
                    ?? $value?->arabicTranslation?->name
                    ?? $value?->englishTranslation?->name
                    ?? $value?->name
                    ?? $value?->name_ar
                    ?? $value?->name_en
                    ?? null;

                return [
                    'attribute_id' => $item->attribute_id,
                    'attribute_value_id' => $item->attribute_value_id,
                    'attribute_name' => $attributeName,
                    'value_name' => $valueName,
                ];
            })
            ->values()
            ->toArray();
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