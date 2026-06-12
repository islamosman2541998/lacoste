<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $page->meta_title ?: $page->title }}</title>

    @if ($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
</head>
<body style="font-family: Arial, sans-serif; padding: 40px;">
    <h1>{{ $page->title }}</h1>

    @if ($page->short_description)
        <p>{{ $page->short_description }}</p>
    @endif

    @if ($page->main_image)
        <img src="{{ asset('storage/' . $page->main_image) }}" style="max-width: 600px; width: 100%; border-radius: 16px;">
    @endif

    <div style="margin-top: 30px;">
        {!! $page->content !!}
    </div>

    @if ($page->activeImages->count())
        <h2>Gallery</h2>

        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            @foreach ($page->activeImages as $image)
                <div>
                    <img src="{{ asset('storage/' . $image->image) }}" style="width: 180px; height: 120px; object-fit: cover; border-radius: 12px;">
                    <div>{{ $image->title }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>