<footer class="mt-20 bg-dark text-white">
    <div class="site-container py-14">
        <div class="grid gap-10 lg:grid-cols-4">
            <div>
                <a href="{{ route('site.home') }}" class="mb-5 flex items-center gap-3">
                    @if (!empty($storeSettings->logo))
                        <img
                            src="{{ asset('storage/' . $storeSettings->logo) }}"
                            alt="{{ $storeSettings->store_name }}"
                            class="h-12 w-auto max-w-36 object-contain"
                        >
                    @endif

                    <span class="text-xl font-extrabold">
                        {{ $storeSettings->store_name ?? config('app.name') }}
                    </span>
                </a>

                @if (!empty($footer['settings']?->description))
                    <p class="max-w-sm text-sm leading-7 text-gray-300">
                        {{ $footer['settings']->description }}
                    </p>
                @else
                    <p class="max-w-sm text-sm leading-7 text-gray-300">
                        {{ app()->getLocale() === 'ar'
                            ? 'متجر إلكتروني احترافي يقدم تجربة تسوق سهلة وسريعة.'
                            : 'A modern e-commerce store with a smooth shopping experience.'
                        }}
                    </p>
                @endif

                <div class="mt-6 space-y-2 text-sm text-gray-300">
                    @if (!empty($storeSettings->phone))
                        <p>{{ $storeSettings->phone }}</p>
                    @endif

                    @if (!empty($storeSettings->email))
                        <p>{{ $storeSettings->email }}</p>
                    @endif

                    @if (!empty($storeSettings->address))
                        <p>{{ $storeSettings->address }}</p>
                    @endif
                </div>
            </div>

            @forelse (($footer['grouped_links'] ?? collect()) as $group => $links)
                <div>
                    <h4 class="mb-5 text-base font-extrabold">
                        {{ $group }}
                    </h4>

                    <div class="space-y-3">
                        @foreach ($links as $link)
                            <a
                                href="{{ $link->resolved_url }}"
                                @if($link->open_in_new_tab) target="_blank" rel="noopener" @endif
                                class="block text-sm text-gray-300 hover:text-white"
                            >
                                {{ $link->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div>
                    <h4 class="mb-5 text-base font-extrabold">
                        {{ app()->getLocale() === 'ar' ? 'روابط مهمة' : 'Quick Links' }}
                    </h4>

                    <a href="{{ route('site.home') }}" class="block text-sm text-gray-300 hover:text-white">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                    </a>
                </div>
            @endforelse
        </div>

        @if (!empty($footer['social_links']) && $footer['social_links']->count())
            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-white/10 pt-8">
                @foreach ($footer['social_links'] as $social)
                    <a
                        href="{{ $social->url }}"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-white/10 px-4 text-sm font-bold text-white hover:bg-brand"
                    >
                        {{ $social->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-6 text-sm text-gray-400 md:flex-row md:items-center md:justify-between">
            <p>
                © {{ date('Y') }} {{ $storeSettings->store_name ?? config('app.name') }}.
                {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
            </p>

            <p>
                {{ app()->getLocale() === 'ar' ? 'مدعوم بتجربة تسوق احترافية' : 'Powered by a professional shopping experience' }}
            </p>
        </div>
    </div>
</footer>