@php
    $translation = $product->transNow
        ?? $product->arabicTranslation
        ?? $product->englishTranslation;

    $name = $translation?->name ?? (app()->getLocale() === 'ar' ? 'منتج' : 'Product');

    $shortDescription = $translation?->short_description;

    $image = $product->main_image
        ? asset('storage/' . $product->main_image)
        : null;

    $price = (float) $product->price;
    $salePrice = $product->sale_price ? (float) $product->sale_price : null;

    $hasSale = $salePrice && $salePrice > 0 && $salePrice < $price;

    $finalPrice = $hasSale ? $salePrice : $price;

    $currency = $storeSettings->currency_symbol ?? $storeSettings->currency_code ?? 'EGP';

    $productUrl = \Illuminate\Support\Facades\Route::has('site.products.show')
        ? route('site.products.show', $translation?->slug ?? $product->id)
        : '#';

    $inStock = ! $product->manage_stock || $product->stock_quantity > 0;

    $categoryName = $product->category?->transNow?->name
        ?? $product->category?->arabicTranslation?->name
        ?? $product->category?->englishTranslation?->name;

    $brandName = $product->brand?->transNow?->name
        ?? $product->brand?->arabicTranslation?->name
        ?? $product->brand?->englishTranslation?->name;

    $discountPercentage = $hasSale
        ? round((($price - $salePrice) / $price) * 100)
        : null;
@endphp

<div class="product-card group">
    <div class="product-card-image-wrap">
        <a href="{{ $productUrl }}" class="block h-full w-full">
            @if ($image)
                <img
                    src="{{ $image }}"
                    alt="{{ $name }}"
                    class="product-card-image"
                    loading="lazy"
                    draggable="false"
                >
            @else
                <div class="product-card-placeholder">
                    {{ mb_substr($name, 0, 1) }}
                </div>
            @endif
        </a>

        <div class="product-card-top-actions">
            @if ($hasSale)
                <span class="product-card-sale-badge">
                    -{{ $discountPercentage }}%
                </span>
            @endif

            <div class="product-card-wishlist">
                @livewire('site.wishlist-button', ['productId' => $product->id], key('wishlist-' . $product->id))
            </div>
        </div>

        @if (! $inStock)
            <span class="product-card-stock-badge">
                {{ app()->getLocale() === 'ar' ? 'نفد المخزون' : 'Out of stock' }}
            </span>
        @endif
    </div>

    <div class="product-card-body">
        <div class="product-card-meta">
            @if ($categoryName)
                <span class="product-card-category">
                    {{ $categoryName }}
                </span>
            @endif

            @if ($brandName)
                <span class="product-card-brand">
                    {{ $brandName }}
                </span>
            @endif
        </div>

        <a href="{{ $productUrl }}">
            <h3 class="product-card-title">
                {{ $name }}
            </h3>
        </a>

        @if ($shortDescription)
            <p class="product-card-description">
                {{ $shortDescription }}
            </p>
        @endif

        <div class="product-card-footer">
            <div class="product-card-prices">
                <div class="product-card-price">
                    {{ number_format($finalPrice, 2) }} {{ $currency }}
                </div>

                @if ($hasSale)
                    <div class="product-card-old-price">
                        {{ number_format($price, 2) }} {{ $currency }}
                    </div>
                @endif
            </div>

            @livewire('site.add-to-cart-button', ['productId' => $product->id], key('add-to-cart-' . $product->id))
        </div>
    </div>
</div>