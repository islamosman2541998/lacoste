document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Header shadow on scroll
    |--------------------------------------------------------------------------
    */
    const header = document.querySelector("[data-site-header]");

    if (header) {
        function updateHeaderState() {
            header.classList.toggle("is-scrolled", window.scrollY > 30);
        }

        updateHeaderState();

        window.addEventListener("scroll", updateHeaderState);
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Menu
    |--------------------------------------------------------------------------
    */
    const mobileMenuButton = document.querySelector(
        "[data-mobile-menu-button]",
    );
    const mobileMenu = document.querySelector("[data-mobile-menu]");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
            mobileMenuButton.classList.toggle("text-brand");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Hero Slider
    |--------------------------------------------------------------------------
    */
    const slider = document.querySelector("[data-hero-slider]");

    if (!slider) {
        return;
    }

    const slides = Array.from(slider.querySelectorAll(".hero-slide"));
    const dots = Array.from(document.querySelectorAll("[data-hero-dot]"));
    const next = document.querySelector("[data-hero-next]");
    const prev = document.querySelector("[data-hero-prev]");

    if (!slides.length) {
        return;
    }

    let current = 0;
    let timer = null;

    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    const dragThreshold = 60;

    function showSlide(index) {
        current = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle("is-active", slideIndex === current);
        });

        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle("is-active", dotIndex === current);
        });
    }

    function nextSlide() {
        showSlide(current + 1);
    }

    function prevSlide() {
        showSlide(current - 1);
    }

    function stopAutoPlay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    function startAutoPlay() {
        if (slides.length <= 1) {
            return;
        }

        stopAutoPlay();

        timer = setInterval(function () {
            nextSlide();
        }, 5000);
    }

    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    if (next) {
        next.addEventListener("click", function () {
            nextSlide();
            resetAutoPlay();
        });
    }

    if (prev) {
        prev.addEventListener("click", function () {
            prevSlide();
            resetAutoPlay();
        });
    }

    dots.forEach((dot) => {
        dot.addEventListener("click", function () {
            showSlide(Number(dot.dataset.heroDot));
            resetAutoPlay();
        });
    });

    function getClientX(event) {
        if (event.touches && event.touches.length) {
            return event.touches[0].clientX;
        }

        return event.clientX;
    }

    function dragStart(event) {
        if (slides.length <= 1) {
            return;
        }

        isDragging = true;
        startX = getClientX(event);
        currentX = startX;

        slider.classList.add("is-dragging");
        stopAutoPlay();
    }

    function dragMove(event) {
        if (!isDragging) {
            return;
        }

        currentX = getClientX(event);
    }

    function dragEnd() {
        if (!isDragging) {
            return;
        }

        const diff = currentX - startX;

        if (Math.abs(diff) > dragThreshold) {
            const isRtl =
                document.documentElement.getAttribute("dir") === "rtl";

            if (!isRtl) {
                diff < 0 ? nextSlide() : prevSlide();
            } else {
                diff < 0 ? prevSlide() : nextSlide();
            }
        }

        isDragging = false;
        startX = 0;
        currentX = 0;

        slider.classList.remove("is-dragging");
        resetAutoPlay();
    }

    slider.addEventListener("touchstart", dragStart, { passive: true });
    slider.addEventListener("touchmove", dragMove, { passive: true });
    slider.addEventListener("touchend", dragEnd);

    slider.addEventListener("mousedown", dragStart);
    slider.addEventListener("mousemove", dragMove);
    slider.addEventListener("mouseup", dragEnd);
    slider.addEventListener("mouseleave", dragEnd);

    showSlide(0);
    startAutoPlay();
});
/*
|--------------------------------------------------------------------------
| Featured Categories Slider
|--------------------------------------------------------------------------
*/
const categoriesSlider = document.querySelector('[data-categories-slider]');
const categoriesNext = document.querySelector('[data-categories-next]');
const categoriesPrev = document.querySelector('[data-categories-prev]');

if (categoriesSlider) {
    let categoryTimer = null;
    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    function getCategoryScrollAmount() {
        const firstCard = categoriesSlider.querySelector('.home-category-card');

        if (!firstCard) {
            return 245;
        }

        const gap = window.innerWidth < 768 ? 16 : 20;
        const cardsToMove = window.innerWidth < 768 ? 2 : 1;

        return (firstCard.offsetWidth + gap) * cardsToMove;
    }

    function categoriesScrollNext() {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        const amount = getCategoryScrollAmount();

        categoriesSlider.scrollBy({
            left: isRtl ? -amount : amount,
            behavior: 'smooth',
        });

        const maxScroll = categoriesSlider.scrollWidth - categoriesSlider.clientWidth;

        setTimeout(function () {
            if (!isRtl && categoriesSlider.scrollLeft >= maxScroll - 10) {
                categoriesSlider.scrollTo({ left: 0, behavior: 'smooth' });
            }

            if (isRtl && Math.abs(categoriesSlider.scrollLeft) >= maxScroll - 10) {
                categoriesSlider.scrollTo({ left: 0, behavior: 'smooth' });
            }
        }, 450);
    }

    function categoriesScrollPrev() {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        const amount = getCategoryScrollAmount();

        categoriesSlider.scrollBy({
            left: isRtl ? amount : -amount,
            behavior: 'smooth',
        });
    }

    function startCategoriesAutoPlay() {
        stopCategoriesAutoPlay();

        if (categoriesSlider.scrollWidth <= categoriesSlider.clientWidth) {
            return;
        }

        categoryTimer = setInterval(categoriesScrollNext, 3000);
    }

    function stopCategoriesAutoPlay() {
        if (categoryTimer) {
            clearInterval(categoryTimer);
            categoryTimer = null;
        }
    }

    if (categoriesNext) {
        categoriesNext.addEventListener('click', function () {
            categoriesScrollNext();
            startCategoriesAutoPlay();
        });
    }

    if (categoriesPrev) {
        categoriesPrev.addEventListener('click', function () {
            categoriesScrollPrev();
            startCategoriesAutoPlay();
        });
    }

    categoriesSlider.addEventListener('mousedown', function (event) {
        isDown = true;
        categoriesSlider.classList.add('is-dragging');
        startX = event.pageX - categoriesSlider.offsetLeft;
        scrollLeft = categoriesSlider.scrollLeft;
        stopCategoriesAutoPlay();
    });

    categoriesSlider.addEventListener('mouseleave', function () {
        if (!isDown) {
            return;
        }

        isDown = false;
        categoriesSlider.classList.remove('is-dragging');
        startCategoriesAutoPlay();
    });

    categoriesSlider.addEventListener('mouseup', function () {
        isDown = false;
        categoriesSlider.classList.remove('is-dragging');
        startCategoriesAutoPlay();
    });

    categoriesSlider.addEventListener('mousemove', function (event) {
        if (!isDown) {
            return;
        }

        event.preventDefault();

        const x = event.pageX - categoriesSlider.offsetLeft;
        const walk = (x - startX) * 1.5;

        categoriesSlider.scrollLeft = scrollLeft - walk;
    });

    categoriesSlider.addEventListener('touchstart', function () {
        stopCategoriesAutoPlay();
    }, { passive: true });

    categoriesSlider.addEventListener('touchend', function () {
        startCategoriesAutoPlay();
    });

    window.addEventListener('resize', function () {
        startCategoriesAutoPlay();
    });

    startCategoriesAutoPlay();
}
/*
|--------------------------------------------------------------------------
| Featured Products Slider
|--------------------------------------------------------------------------
*/
const productsSlider = document.querySelector('[data-products-slider]');
const productsNext = document.querySelector('[data-products-next]');
const productsPrev = document.querySelector('[data-products-prev]');

if (productsSlider) {
    let productTimer = null;
    let isProductDown = false;
    let productStartX = 0;
    let productScrollLeft = 0;

    function getProductScrollAmount() {
        const firstCard = productsSlider.querySelector('.product-card');

        if (!firstCard) {
            return 260;
        }

        const gap = window.innerWidth < 768 ? 16 : 20;
        const cardsToMove = window.innerWidth < 768 ? 2 : 1;

        return (firstCard.offsetWidth + gap) * cardsToMove;
    }

    function productsScrollNext() {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        const amount = getProductScrollAmount();

        productsSlider.scrollBy({
            left: isRtl ? -amount : amount,
            behavior: 'smooth',
        });

        const maxScroll = productsSlider.scrollWidth - productsSlider.clientWidth;

        setTimeout(function () {
            if (!isRtl && productsSlider.scrollLeft >= maxScroll - 10) {
                productsSlider.scrollTo({ left: 0, behavior: 'smooth' });
            }

            if (isRtl && Math.abs(productsSlider.scrollLeft) >= maxScroll - 10) {
                productsSlider.scrollTo({ left: 0, behavior: 'smooth' });
            }
        }, 450);
    }

    function productsScrollPrev() {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        const amount = getProductScrollAmount();

        productsSlider.scrollBy({
            left: isRtl ? amount : -amount,
            behavior: 'smooth',
        });
    }

    function startProductsAutoPlay() {
        stopProductsAutoPlay();

        if (productsSlider.scrollWidth <= productsSlider.clientWidth) {
            return;
        }

        productTimer = setInterval(productsScrollNext, 3500);
    }

    function stopProductsAutoPlay() {
        if (productTimer) {
            clearInterval(productTimer);
            productTimer = null;
        }
    }

    if (productsNext) {
        productsNext.addEventListener('click', function () {
            productsScrollNext();
            startProductsAutoPlay();
        });
    }

    if (productsPrev) {
        productsPrev.addEventListener('click', function () {
            productsScrollPrev();
            startProductsAutoPlay();
        });
    }

    productsSlider.addEventListener('mousedown', function (event) {
        isProductDown = true;
        productsSlider.classList.add('is-dragging');
        productStartX = event.pageX - productsSlider.offsetLeft;
        productScrollLeft = productsSlider.scrollLeft;
        stopProductsAutoPlay();
    });

    productsSlider.addEventListener('mouseleave', function () {
        if (!isProductDown) {
            return;
        }

        isProductDown = false;
        productsSlider.classList.remove('is-dragging');
        startProductsAutoPlay();
    });

    productsSlider.addEventListener('mouseup', function () {
        isProductDown = false;
        productsSlider.classList.remove('is-dragging');
        startProductsAutoPlay();
    });

    productsSlider.addEventListener('mousemove', function (event) {
        if (!isProductDown) {
            return;
        }

        event.preventDefault();

        const x = event.pageX - productsSlider.offsetLeft;
        const walk = (x - productStartX) * 1.4;

        productsSlider.scrollLeft = productScrollLeft - walk;
    });

    productsSlider.addEventListener('touchstart', function () {
        stopProductsAutoPlay();
    }, { passive: true });

    productsSlider.addEventListener('touchend', function () {
        startProductsAutoPlay();
    });

    window.addEventListener('resize', function () {
        startProductsAutoPlay();
    });

    startProductsAutoPlay();
}
/*
|--------------------------------------------------------------------------
| Site Toast
|--------------------------------------------------------------------------
*/
const siteToast = document.getElementById('site-toast');
let siteToastTimer = null;

function showSiteToast(options = {}) {
    if (!siteToast) {
        return;
    }

    const type = options.type || 'success';
    const title = options.title || 'Success';
    const message = options.message || 'Action completed successfully';
    const icon = options.icon || '✓';

    const titleElement = siteToast.querySelector('[data-toast-title]');
    const messageElement = siteToast.querySelector('[data-toast-message]');
    const iconElement = siteToast.querySelector('[data-toast-icon]');

    if (titleElement) {
        titleElement.textContent = title;
    }

    if (messageElement) {
        messageElement.textContent = message;
    }

    if (iconElement) {
        iconElement.textContent = icon;
    }

    siteToast.classList.remove('is-success', 'is-error', 'is-warning');
    siteToast.classList.add('is-' + type);
    siteToast.classList.add('is-visible');

    if (siteToastTimer) {
        clearTimeout(siteToastTimer);
    }

    siteToastTimer = setTimeout(function () {
        siteToast.classList.remove('is-visible');
    }, options.duration || 2600);
}

window.showSiteToast = showSiteToast;

window.addEventListener('site-toast', function (event) {
    showSiteToast(event.detail || {});
});

window.addEventListener('notify', function (event) {
    showSiteToast(event.detail || {});
});
