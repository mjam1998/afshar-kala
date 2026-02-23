<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('front/assets/logo-smal.png')}}">
    <link rel="icon" type="image/png" href="{{asset('front/assets/logo-smal.png')}}">

    <!-- برای دستگاه‌های اپل -->
    <link rel="apple-touch-icon" href="{{asset('front/assets/logo-smal.png')}}">
    <!-- Title با پیش‌فرض -->
    <title>@yield('page_title', 'افشار کالا')</title>

    <!-- Meta Description با پیش‌فرض -->
    <meta name="description" content="@yield('meta_description', 'فروشگاه لوازم خانگی افشار کالا در ساری')">

    <!-- سایر متا تگ‌های پایه برای SEO بهتر -->
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="فروشگاه لوازم خانگی افشار کالا در ساری">

    <!-- Open Graph پایه (برای اشتراک در شبکه‌های اجتماعی) -->
    <meta property="og:title" content="@yield('page_title', 'Columbia Iran')">
    <meta property="og:description" content="@yield('meta_description', 'فروشگاه لوازم خانگی افشار کالا در ساری')">
   <link rel="stylesheet" href="{{asset('bootstrap/bootstrap-icons.css')}}">
{{--    <link href="https://cdn.fontcdn.ir/fonts/vazir/vazir.css" rel="stylesheet">--}}

    <link rel="stylesheet" href="{{asset('front/assets/style.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('bootstrap/swiper-bundle.min.css')}}" />
    <style>

        #navbar nav ul {
            display: flex !important;
            align-items: center !important;
            gap: 25px !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        /* در بخش <style> داخل <head> master اضافه کنید */
        .color-circle {
            display: inline-block;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, transform 0.2s;
        }
        .color-circle.active,
        .color-circle:hover {
            border-color: #333;
            transform: scale(1.15);
        }
        .size-badge {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: background 0.2s, color 0.2s;
            user-select: none;
        }
        .size-badge.active,
        .size-badge:hover {
            background: #333;
            color: #fff;
            border-color: #333;
        }

    </style>
</head>
<body>



<header id="navbar">
    <a href="{{route('home.index')}}" class="logo"><img src="{{asset('front/assets/logo.png')}}" alt="Columbia Iran" style="height: 50px; width: 50px;"></a>
    <div class="navbar-inner">
        <nav >
            <ul>
                <li ><a href="{{route('home.index')}}">صفحه اصلی</a></li>
                <li class="dropdown">
                    <a href="#">محصولات</a>
                    <div class="dropdown-content" >
                        @foreach($menuCategories as $category)
                            <a href="{{route('front.category.show',$category->slug)}}">{{$category->name}}</a>
                        @endforeach


                    </div>
                </li>
                <li><a href="{{route('front.contact.us')}}"> تماس باما</a></li>
                <li><a href="{{route('front.about.us')}}">درباره ما </a></li>
                <li><a href="{{route('front.articles.show')}}">بلاگ</a></li>
                <li><a href="{{route('order.track.form')}}" style="color: red;">پیگری سفارش</a></li>
            </ul>
        </nav>
        <div class="top-icons">
            <a href="{{route('front.search')}}" class="icon-link" aria-label="جستجو" style="text-decoration: none; color: black;margin-left: 20px;" >
                <img src="{{asset('front/assets/search.png')}}" style="height: 28px; width: 28px; margin-bottom: 5px;" >
            </a>

            <a href="{{route('front.cart.list')}}" class="icon-link position-relative" aria-label="سبد خرید" style="text-decoration: none; color: black;">
                <img src="{{asset('front/assets/bag.png')}}">
                <span class="cart-badge badge rounded-pill bg-danger position-absolute top-0 start-0 translate-middle">
        {{ collect(session('cart', []))->sum('quantity') }}
    </span>
            </a>
        </div>
    </div>
</header>

 <div class="mobile-top-bar">
     <a href="{{route('home.index')}}" class="logo" style="color: black; font-size: 1.5rem;"><img src="{{asset('front/assets/logo.png')}}" style="height: 35px; width: 35px;"></a>
     <a href="{{route('front.search')}}"><img src="{{asset('front/assets/search.png')}}" style="height: 28px; width: 28px;"></a>


</div>

<div class="menu-backdrop" id="backdrop"></div>
<div class="mobile-menu-overlay" id="mobileMenu">
    <div style="display:flex; justify-content:space-between; padding-bottom:20px; border-bottom:1px solid #eee;">
        <h3>منو</h3>
        <button id="closeMenu" style="border:none; background:none; font-size:1.5rem;">✕</button>
    </div>
    <ul style="list-style:none; padding-top:20px;">
        <li style="margin-bottom:20px;">
            <a href="#" id="productsMenu" style="text-decoration:none; color:#333; font-weight:bold;">محصولات</a>
            <ul id="productsDropdown" style="display:none; list-style:none; padding-left: 20px;">

                @foreach($menuCategories as $category)
                   <li><a href="{{route('front.category.show',$category->slug)}}">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </li>
        <li style="margin-bottom:20px;"><a href="{{route('order.track.form')}}" style="text-decoration:none; color:red; font-weight:bold;">پیگیری سفارش</a></li>
        <li style="margin-bottom:20px;"><a href="{{route('front.articles.show')}}" style="text-decoration:none; color:#333; font-weight:bold;">بلاگ</a></li>
        <li style="margin-bottom:20px;"><a href="{{route('front.contact.us')}}" style="text-decoration:none; color:#333; font-weight:bold;">تماس باما</a></li>
        <li style="margin-bottom:20px;"><a href="{{route('front.about.us')}}" style="text-decoration:none; color:#333; font-weight:bold;">درباره ما</a></li>
    </ul>
</div>


<nav class="mobile-bottom-nav">
    <button id="openMenu"><img src="{{asset('front/assets/berger-menu.png')}}" ></button>
    <a href="{{route('home.index')}}"><img src="{{asset('front/assets/house.png')}}" style="height: 28px; width: 28px;" ></a>


    <a href="{{route('front.cart.list')}}" class="position-relative">
        <img src="{{asset('front/assets/bag.png')}}">
        <span class="cart-badge badge rounded-pill bg-danger position-absolute top-0 start-0 translate-middle">
        {{ count(session('cart', [])) }}
    </span>
    </a>
</nav>

@yield('content')


<footer class="main-footer">
    <div class="footer-container">

        <div class="footer-column about-col">
            <div class="footer-logo">
                <img src="{{asset('front/assets/logo.png')}}" alt="Logo" style="width: 50px;height: 50px;" >
            </div>
            <p class="footer-desc">
                افشار کالا؛ انتخاب مطمئن خانه شما
            </p>
        </div>

        <div class="footer-column">
            <h4>لینک‌های سریع</h4>
            <ul>
                <li><a href="{{route('front.rules')}}">قوانین سایت</a></li>
                <li><a href="{{route('order.track.form')}}">پیگیری سفارش</a></li>
                <li><a href="{{route('front.about.us')}}">درباره ما</a></li>
                <li><a href="{{route('front.contact.us')}}">تماس با ما</a></li>
            </ul>
        </div>

        <div class="footer-column contact-col">
            <h4>ارتباط با ما</h4>
            <ul>
                <li>
                    <span>📍 آدرس:</span>
                    ساری،خیابان 18 دی نبش خیابان خیام،لوازم خانگی افشار
                </li>
                <li>
                    <span>📞 تلفن:</span>
                    <p>   <a href="tel:09927887862">09927887862</a></p>


                </li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>مجوزها و اینماد</h4>

        </div>

    </div>

    <div class="footer-bottom">
        <p>© افشار کالا 1404-1405 - تمامی حقوق محفوظ است.</p>
    </div>
</footer>

<div class="floating-support">
    <a href="https://wa.me/989927887862" target="_blank"> <img src="{{asset('front/assets/support.png')}}" alt="Support"></a>
</div>




<script src="{{asset('bootstrap/bootstrap.min.js')}}" ></script>
<script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}" ></script>
<script src="{{asset('bootstrap/swiper-bundle.min.js')}}"></script>
<script>
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.innerWidth > 1024) {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        }
    });

    const openBtn = document.getElementById('openMenu');
    const closeBtn = document.getElementById('closeMenu');
    const menu = document.getElementById('mobileMenu');
    const backdrop = document.getElementById('backdrop');

    function toggleMenu() {
        const isActive = menu.classList.toggle('active');
        backdrop.style.display = isActive ? 'block' : 'none';
        document.body.style.overflow = isActive ? 'hidden' : 'auto';
    }

    openBtn.addEventListener('click', toggleMenu);
    closeBtn.addEventListener('click', toggleMenu);
    backdrop.addEventListener('click', toggleMenu);
    // اینجا برای باز و بسته کردن منوی دراپ‌دان در موبایل
    document.getElementById('productsMenu').addEventListener('click', function(event) {
        event.preventDefault();  // از انجام عملیات پیش‌فرض جلوگیری می‌کند (مثل تغییر صفحه)
        const dropdown = document.getElementById('productsDropdown');

        // بررسی می‌کنیم که آیا دراپ‌دان باز است یا نه
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block";  // اگر بسته بود، آن را باز می‌کنیم
        } else {
            dropdown.style.display = "none";  // اگر باز بود، آن را می‌بندیم
        }
    });

    // برای بستن منو وقتی روی دکمه بستن کلیک می‌شود
    document.getElementById('closeMenu').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.remove('active');  // مخفی کردن منو
        document.body.style.overflow = 'auto';  // بازگشت به حالت طبیعی اسکرول
    });
    // اینجا برای باز و بسته کردن منوی دراپ‌دان در موبایل با انیمیشن
    document.getElementById('productsMenu').addEventListener('click', function(event) {
        event.preventDefault();  // از انجام عملیات پیش‌فرض جلوگیری می‌کند (مثل تغییر صفحه)
        const dropdown = document.getElementById('productsDropdown');

        // اضافه کردن یا حذف کلاس open برای نمایش و مخفی کردن منو
        dropdown.classList.toggle('open');
    });

    // برای بستن منو وقتی روی دکمه بستن کلیک می‌شود
    document.getElementById('closeMenu').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.remove('active');  // مخفی کردن منو
        document.body.style.overflow = 'auto';  // بازگشت به حالت طبیعی اسکرول
    });

</script>
<script>
    function startTimer() {
        const timerElement = document.getElementById('timer');
        if (!timerElement) return;

        let endTime = localStorage.getItem('timerEndTime');

        // اگر زمانی ذخیره نشده بود یا زمان منقضی شده بود، زمان جدید (48 ساعت بعد) را تنظیم کن
        if (!endTime || Date.now() > parseInt(endTime)) {
            endTime = Date.now() + (48 * 60 * 60 * 1000); // 48 ساعت
            localStorage.setItem('timerEndTime', endTime);
        }

        const interval = setInterval(() => {
            const now = Date.now();
            const difference = parseInt(endTime) - now;

            if (difference <= 0) {
                clearInterval(interval);
                localStorage.removeItem('timerEndTime');
                startTimer();
                return;
            }

            const hours = String(Math.floor(difference / (1000 * 60 * 60))).padStart(2, '0');
            const minutes = String(Math.floor((difference / (1000 * 60)) % 60)).padStart(2, '0');
            const seconds = String(Math.floor((difference / 1000) % 60)).padStart(2, '0');

            timerElement.textContent = `${hours}:${minutes}:${seconds}`;
        }, 1000);
    }

    window.onload = startTimer;
</script>


<script>
 /*   function changeProductImage(element, targetImgId) {
        const newImageUrl = element.getAttribute('data-image');
        const mainImg = document.getElementById(targetImgId);

        if (mainImg && newImageUrl) {
            // افکت محو شدن هنگام تغییر
            mainImg.style.opacity = '0.4';

            setTimeout(() => {
                mainImg.src = newImageUrl;
                mainImg.style.opacity = '1';
            }, 150);
        }

        // تغییر کلاس فعال (Active) بین سواچ‌ها
        const swatches = element.parentElement.querySelectorAll('.swatch');
        swatches.forEach(s => s.classList.remove('active'));
        element.classList.add('active');
    }*/
 function getVariants(productId) {
     const el = document.getElementById('variants-data-' + productId);
     return el ? JSON.parse(el.textContent) : [];
 }

 function updatePrice(productId) {
     const variants = getVariants(productId);
     const activeColor = document.querySelector(`#colors-${productId} .color-circle.active`);
     const activeSize  = document.querySelector(`#sizes-${productId} .size-badge.active`);

     const colorId = activeColor ? parseInt(activeColor.dataset.colorId) : null;
     const sizeId  = activeSize  ? parseInt(activeSize.dataset.sizeId)   : null;

     let variant = null;
     if (colorId && sizeId) {
         variant = variants.find(v => v.color_id === colorId && v.size_id === sizeId);
     }
     if (!variant && colorId) {
         variant = variants.find(v => v.color_id === colorId);
     }
     if (!variant) variant = variants[0];

     const box = document.getElementById('price-box-' + productId);
     if (!box || !variant) return;

     const fmt = (n) => Number(n).toLocaleString('en-US');

     if (variant.discount > 0) {
         const percent = Math.round((variant.discount / variant.price) * 100);
         box.innerHTML = `
           <div class="d-flex align-items-center gap-2 mb-1">
               <span class="badge bg-danger text-white" style="font-size:0.7rem;">${percent}% تخفیف</span>
               <span class="old-price text-muted small text-decoration-line-through">${fmt(variant.price)}</span>
           </div>
           <span class="current-price text-danger fw-bold fs-5">${fmt(variant.price - variant.discount)} <small style="font-size:0.6em">تومان</small></span>`;
     } else {
         box.innerHTML = `<span class="current-price fw-bold fs-5">${fmt(variant.price)} <small style="font-size:0.6em">تومان</small></span>`;
     }
 }


 function selectColor(el) {
     const productId = el.dataset.productId;
     document.querySelectorAll(`#colors-${productId} .color-circle`).forEach(c => c.classList.remove('active'));
     el.classList.add('active');
     updatePrice(productId);
 }

 function selectSize(el) {
     const productId = el.dataset.productId;
     document.querySelectorAll(`#sizes-${productId} .size-badge`).forEach(s => s.classList.remove('active'));
     el.classList.add('active');
     updatePrice(productId);
 }

 function changeProductImage(element, productId) {
     const newImageUrl = element.getAttribute('data-image');

     // پیدا کردن نزدیک‌ترین کارت محصول
     const productCard = element.closest('.product-item');
     // پیدا کردن عکس اصلی فقط داخل همین کارت
     const mainImg = productCard.querySelector('.main-product-img');

     if (mainImg && newImageUrl) {
         mainImg.style.opacity = '0.4';

         setTimeout(() => {
             mainImg.src = newImageUrl;
             mainImg.style.opacity = '1';
         }, 150);
     }

     // تغییر کلاس فعال برای سواچ‌های داخل همین کارت
     const swatches = productCard.querySelectorAll('.swatch');
     swatches.forEach(s => s.classList.remove('active'));
     element.classList.add('active');
 }
</script>
<script>
    function updateCartBadge(count) {
        document.querySelectorAll('.cart-badge').forEach(function(badge) {
            badge.textContent = count;
        });
    }
</script>
@stack('scripts')
</body>
</html>
