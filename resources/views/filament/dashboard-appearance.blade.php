@php
    use App\Models\StoreSetting;

    $settings = StoreSetting::current();

    $primaryColor = $settings->dashboard_primary_color ?: '';
    $sidebarColor = $settings->dashboard_sidebar_color ?: '';
    $sidebarTextColor = $settings->dashboard_sidebar_text_color ?: '';
    $topbarColor = $settings->dashboard_topbar_color ?: '';

    $buttonRadius = (int) ($settings->dashboard_button_radius ?: 8);
    $cardRadius = (int) ($settings->dashboard_card_radius ?: 12);
    $loginBackgroundImage = $settings->login_background_image
    ? asset('storage/' . $settings->login_background_image)
    : '';

$loginBackgroundOpacity = $settings->login_background_opacity ?: 0.35;
$loginCardBackgroundColor = $settings->login_card_background_color ?: '#ffffff';
$loginCardOpacity = $settings->login_card_opacity ?: 0.92;
$loginCardBlur = (bool) $settings->login_card_blur;

$hexToRgb = function (?string $hex, string $fallback = '255, 255, 255') {
    if (! $hex) {
        return $fallback;
    }

    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (strlen($hex) !== 6) {
        return $fallback;
    }

    return hexdec(substr($hex, 0, 2)) . ', ' .
        hexdec(substr($hex, 2, 2)) . ', ' .
        hexdec(substr($hex, 4, 2));
};

$loginCardRgb = $hexToRgb($loginCardBackgroundColor);
$loginLogo = $settings->dashboard_logo
    ? asset('storage/' . $settings->dashboard_logo)
    : '';
 $loginLogoWidth = (int) ($settings->login_logo_width ?: 96);
$loginLogoHeight = (int) ($settings->login_logo_height ?: 96);
@endphp

<style>
    :root {
        --dashboard-primary-color: {{ $primaryColor }};
        --dashboard-sidebar-color: {{ $sidebarColor }};
        --dashboard-sidebar-text-color: {{ $sidebarTextColor }};
        --dashboard-topbar-color: {{ $topbarColor }};
        --dashboard-button-radius: {{ $buttonRadius }}px;
        --dashboard-card-radius: {{ $cardRadius }}px;
    }

    .fi-sidebar {
        background-color: var(--dashboard-sidebar-color) !important;
    }

    .fi-sidebar a,
    .fi-sidebar button,
    .fi-sidebar span,
    .fi-sidebar svg {
        color: var(--dashboard-sidebar-text-color) !important;
    }

    /* Active sidebar item */
    .fi-sidebar a[aria-current="page"],
    .fi-sidebar .fi-sidebar-item.fi-active>a,
    .fi-sidebar .fi-sidebar-item.fi-active button,
    .fi-sidebar .fi-sidebar-item-active>a {
        background: linear-gradient(90deg,
                color-mix(in srgb, var(--dashboard-primary-color) 18%, white),
                color-mix(in srgb, var(--dashboard-primary-color) 7%, white)) !important;
        border-radius: 14px !important;
        border-inline-start: 4px solid var(--dashboard-primary-color) !important;
        font-weight: 700 !important;
    }

    /* Active item text */
    .fi-sidebar a[aria-current="page"] span,
    .fi-sidebar .fi-sidebar-item.fi-active span,
    .fi-sidebar .fi-sidebar-item-active span {
        color: #111827 !important;
        font-weight: 700 !important;
    }

    /* Active item icon */
    .fi-sidebar a[aria-current="page"] svg,
    .fi-sidebar .fi-sidebar-item.fi-active svg,
    .fi-sidebar .fi-sidebar-item-active svg {
        color: var(--dashboard-primary-color) !important;
    }

    .fi-topbar {
        background-color: var(--dashboard-topbar-color) !important;
    }

    .fi-btn {
        border-radius: var(--dashboard-button-radius) !important;
    }

    .fi-section,
    .fi-ta,
    .fi-fo-section,
    .fi-in-section {
        border-radius: var(--dashboard-card-radius) !important;
    }

    .fi-btn-color-primary {
        background-color: var(--dashboard-primary-color) !important;
    }

    .fi-btn-color-primary:hover {
        filter: brightness(0.95);
    }

    .fi-tabs-item-active {
        color: var(--dashboard-primary-color) !important;
        border-color: var(--dashboard-primary-color) !important;
    }

    .fi-link,
    .fi-breadcrumbs a {
        color: var(--dashboard-primary-color) !important;
    }

    input[type="checkbox"]:checked,
    input[type="radio"]:checked {
        background-color: var(--dashboard-primary-color) !important;
        border-color: var(--dashboard-primary-color) !important;
    }
    /* Login Page Appearance */
.fi-simple-layout {
    position: relative !important;
    min-height: 100vh !important;
    background: transparent !important;
    overflow: hidden;
}

.fi-simple-layout::before {
    content: "";
    position: fixed;
    inset: 0;
    background-image: url('{{ $loginBackgroundImage }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: {{ $loginBackgroundImage ? $loginBackgroundOpacity : 0 }};
    z-index: -2;
}

.fi-simple-layout::after {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.18);
    z-index: -1;
}

.fi-simple-main {
    background: rgba({{ $loginCardRgb }}, {{ $loginCardOpacity }}) !important;
    border-radius: 24px !important;
    border: 1px solid rgba(255, 255, 255, 0.35) !important;
    box-shadow: 0 24px 70px rgba(15, 23, 42, 0.12) !important;
    padding: 20px !important;
    max-width: 520px !important;
    width: 100% !important;
    height: auto !important;
    {{ $loginCardBlur ? 'backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);' : '' }}
    
}

.fi-simple-main .fi-logo {
    margin-bottom: 10px !important;
    justify-content: center !important;
}

.fi-simple-main .fi-logo img {
    max-height: 76px !important;
    object-fit: contain !important;
}

.fi-simple-main h1,
.fi-simple-main .fi-simple-header-heading {
    text-align: center !important;
}

.fi-simple-main form {
    margin-top: 24px !important;
}
/* Force Login Logo Above Store Name */

/* Store name under logo */
.fi-simple-main .fi-logo {
    margin-bottom: 8px !important;
    justify-content: center !important;
    text-align: center !important;
    font-size: 22px !important;
    font-weight: 800 !important;
}

/* Login title */
.fi-simple-main .fi-simple-header-heading {
    margin-top: 4px !important;
    text-align: center !important;
}
/* Login Page Header Order: Logo then Sign in only */
.fi-simple-header {
    gap: 14px !important;
}

/* Keep only Filament real logo */
.fi-simple-header .fi-logo {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin-bottom: 0 !important;
}

/* Logo image size */
/* Login Logo Size */
.fi-simple-header img.fi-logo {
    width: {{ $loginLogoWidth }}px !important;
    height: {{ $loginLogoHeight }}px !important;
    max-width: {{ $loginLogoWidth }}px !important;
    max-height: {{ $loginLogoHeight }}px !important;
    object-fit: contain !important;
}

/* Remove Filament default small logo height */
.fi-simple-header .fi-logo {
    height: {{ $loginLogoHeight }}px !important;
}

/* If logo is image, hide any text beside it */
.fi-simple-header .fi-logo span {
    display: none !important;
}

/* Sign in directly under logo */
.fi-simple-header-heading {
    margin-top: 0 !important;
    text-align: center !important;
}
</style>
