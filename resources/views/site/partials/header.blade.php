<header data-site-header class="site-header site-header-transparent fixed left-0 top-0 z-50 w-full transition">
    @if ($storeSettings->announcement_bar_enabled && $storeSettings->announcement_bar_text)
        <div class="overflow-hidden border-b border-white/10"
            style="
                background-color: {{ $storeSettings->announcement_bar_bg_color ?: '#111827' }};
                color: {{ $storeSettings->announcement_bar_text_color ?: '#ffffff' }};
            ">
            <div class="relative flex min-h-10 items-center">
                @php
                    $announcementContent = $storeSettings->announcement_bar_text;
                    $announcementSpeed = $storeSettings->announcement_bar_speed ?: 25;
                @endphp

                @if ($storeSettings->announcement_bar_url)
                    <a href="{{ $storeSettings->announcement_bar_url }}"
                        @if ($storeSettings->announcement_bar_open_in_new_tab) target="_blank" rel="noopener" @endif
                        class="announcement-track hover:opacity-90"
                        style="animation-duration: {{ $announcementSpeed }}s;">
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                    </a>
                @else
                    <div class="announcement-track" style="animation-duration: {{ $announcementSpeed }}s;">
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                        <span>{{ $announcementContent }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="site-container">
        <div class="site-navbar flex min-h-20 items-center justify-between gap-4">
            <a href="{{ route('site.home') }}" class="flex shrink-0 items-center gap-3">
                @if (!empty($storeSettings->logo))
                    <img src="{{ asset('storage/' . $storeSettings->logo) }}" alt="{{ $storeSettings->store_name }}"
                        class="h-12 w-auto max-w-36 object-contain">
                @endif

                {{-- <span class="text-xl font-extrabold tracking-tight text-dark">
                    {{ $storeSettings->store_name ?? config('app.name') }}
                </span> --}}
            </a>

            <nav class="hidden items-center gap-7 lg:flex">
                @foreach ($navigation['header_links'] ?? collect() as $link)
                    <a href="{{ $link->resolved_url }}"
                        @if ($link->open_in_new_tab) target="_blank" rel="noopener" @endif
                        class="site-nav-link text-sm font-semibold">
                        {{ $link->title }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <a href="#" class="site-header-icon" aria-label="{{ __('admin.search') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.05 6.05a7.5 7.5 0 0 0 10.6 10.6Z" />
                    </svg>
                </a>

                @livewire('site.cart-counter')

                <a href="{{ route('switch.language', ['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
                    class="site-header-lang hidden sm:inline-flex">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}
                </a>

                <button type="button" data-mobile-menu-button class="site-header-icon site-mobile-menu-btn lg:hidden"
                    aria-label="Menu">
                    <svg class="site-mobile-menu-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div data-mobile-menu class="hidden border-t border-gray-100 py-4 lg:hidden">
            <nav class="flex flex-col gap-3">
                @foreach ($navigation['mobile_links'] ?? ($navigation['header_links'] ?? collect()) as $link)
                    <a href="{{ $link->resolved_url }}"
                        @if ($link->open_in_new_tab) target="_blank" rel="noopener" @endif
                        class="rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 hover:text-white">
                        {{ $link->title }}
                    </a>
                @endforeach

                <a href="{{ route('switch.language', ['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
                    class="rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 hover:text-white sm:hidden">
                    {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                </a>
            </nav>
        </div>
    </div>
</header>
