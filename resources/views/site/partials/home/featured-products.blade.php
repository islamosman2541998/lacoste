@php
    $featuredProducts = $homepage['featured_products'] ?? [
        'enabled' => false,
        'title' => null,
        'items' => collect(),
    ];

    $products = $featuredProducts['items'] ?? collect();
@endphp

@if (($featuredProducts['enabled'] ?? false) && $products->count())
    <section class="home-products-section">
        <div class="site-container">
            <div class="home-section-head">
                <span class="home-section-badge">
                    {{ app()->getLocale() === 'ar' ? 'اختيارات مميزة' : 'Featured Picks' }}
                </span>

                <h2 class="home-section-title">
                    {{ $featuredProducts['title'] }}
                </h2>

                <p class="home-section-description">
                    {{ app()->getLocale() === 'ar'
                        ? 'منتجات مختارة بعناية لتجربة تسوق أفضل.'
                        : 'Carefully selected products for a better shopping experience.' }}
                </p>
            </div>

            <div class="home-products-slider-wrap">
                @if ($products->count() > 1)
                    <button
                        type="button"
                        class="home-products-arrow home-products-prev"
                        data-products-prev
                        aria-label="Previous products"
                    >
                        ‹
                    </button>
                @endif

                <div class="home-products-slider" data-products-slider>
                    @foreach ($products as $product)
                        @include('site.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                @if ($products->count() > 1)
                    <button
                        type="button"
                        class="home-products-arrow home-products-next"
                        data-products-next
                        aria-label="Next products"
                    >
                        ›
                    </button>
                @endif
            </div>
        </div>
    </section>
@endif