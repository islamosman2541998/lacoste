@php
    $flashSales = $homepage['flash_sales'] ?? [
        'enabled' => false,
        'title' => null,
        'items' => collect(),
    ];

    $products = $flashSales['items'] ?? collect();
@endphp

@if (($flashSales['enabled'] ?? false) && $products->count())
    <section class="home-flash-sales-section">
        <div class="site-container">
            <div class="home-flash-sales-head">
                <div>
                    <span class="home-flash-badge">
                        {{ app()->getLocale() === 'ar' ? 'عروض محدودة' : 'Limited Offers' }}
                    </span>

                    <h2 class="home-flash-title">
                        {{ $flashSales['title'] }}
                    </h2>

                    <p class="home-flash-description">
                        {{ app()->getLocale() === 'ar'
                            ? 'استفد من أقوى الخصومات قبل انتهاء الكمية أو مدة العرض.'
                            : 'Catch the best deals before stock or offer time runs out.' }}
                    </p>
                </div>

                <div class="home-flash-label">
                    <span>
                        {{ app()->getLocale() === 'ar' ? 'خصومات الآن' : 'Live Deals' }}
                    </span>
                </div>
            </div>

            <div class="home-flash-sales-slider-wrap">
                @if ($products->count() > 1)
                    <button
                        type="button"
                        class="home-flash-sales-arrow home-flash-sales-prev"
                        data-flash-sales-prev
                        aria-label="Previous flash sales"
                    >
                        ‹
                    </button>
                @endif

                <div class="home-flash-sales-slider" data-flash-sales-slider>
                    @foreach ($products as $product)
                        @include('site.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                @if ($products->count() > 1)
                    <button
                        type="button"
                        class="home-flash-sales-arrow home-flash-sales-next"
                        data-flash-sales-next
                        aria-label="Next flash sales"
                    >
                        ›
                    </button>
                @endif
            </div>
        </div>
    </section>
@endif