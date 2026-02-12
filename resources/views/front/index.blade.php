@extends('front.layout.master')

@section('page_title')
    {{ $videoBanner->page_title ?? 'columbia' }}
@endsection

@section('meta_description')
    {{$videoBanner->meta_description ?? 'columbia'}}
@endsection

@section('content')
{{--    <section class="hero-new">
        <div class="hero-container">
            <img src="{{asset('video/'.$videoBanner->photo)}}" alt="Columbia Banner" class="hero-img">
            <div class="hero-content-new">

                <h1 style="font-size: 2em;" class="hero-main-title">فروشگاه لوازم خانگی افشار کالا</h1>
                <p class="hero-description" style="font-size: 1.5em; font-weight: bold;">
                   {{$videoBanner->title}}
                </p>
                <p class="hero-description">
                    {{$videoBanner->description}}
                </p>
                <div class="hero-actions" style="margin-top: 20px;">
                   --}}{{-- <a href="#" class="btn-black">خرید</a>--}}{{--
                    <a href="{{$videoBanner->link}}" class="btn-black">{{$videoBanner->btn_text}}</a>
                </div>
            </div>
        </div>
    </section>--}}
<section class="hero-new">
    <div class="hero-slider-container">
        <!-- اسلایدر با حداکثر 4 عکس -->
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                <!-- اسلاید 1 -->
                <div class="swiper-slide">
                  <a href="{{$videoBanner->link}}"> <img src="{{asset('video/'.$videoBanner->photo)}}" alt="Banner 1"></a>
                </div>

                <!-- اسلاید 2 (در صورت وجود) -->
                @if(isset($banner2))
                    <div class="swiper-slide">
                       <a href="{{$banner2->link}}"><img src="{{asset('video/'.$banner2->photo)}}" alt="Banner 2"></a>
                    </div>
                @endif

                <!-- اسلاید 3 (در صورت وجود) -->
                @if(isset($banner3))
                    <div class="swiper-slide">
                        <a href="{{$banner3->link}}"><img src="{{asset('video/'.$banner3->photo)}}" alt="Banner 2"></a>
                    </div>
                @endif

                <!-- اسلاید 4 (در صورت وجود) -->
                @if(isset($banner4))
                    <div class="swiper-slide">
                        <a href="{{$banner4->link}}"><img src="{{asset('video/'.$banner4->photo)}}" alt="Banner 2"></a>
                    </div>
                @endif
            </div>

            <!-- دکمه‌های ناوبری -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- نقاط ناوبری -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
    <section class="categories-section" style="margin-top: 40px;">
        <h2 style="margin-bottom:30px;">خرید بر اساس دسته‌بندی</h2>
        <div class="categories-list">
            @foreach($categories as $category)
                <a href="{{route('front.category.show',$category->slug)}}" class="category-btn">
                    <div class="category-icon-box">
                        <img src="{{asset('category/'.$category->photo)}}" alt="{{$category->photo_alt}}">
                    </div>
                    {{$category->name}}
                </a>
            @endforeach


        </div>
    </section>
    <!-- بخش پیشنهاد ویژه با چندین محصول اسکرول‌شدنی -->
    <section class="special-offer">
        <h2>پیشنهاد ویژه امروز!</h2>
        <p>فقط تا پایان تایمر — عجله کنید!</p>
        <div class="timer" id="timer">08:00:00</div>


        <div class="product-scroll-container">
            <!-- همان محصولات جدیدترین، اما در پیشنهاد ویژه -->

                @foreach($specials as $special)
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="product-item h-100 shadow-sm bg-white p-2 d-flex flex-column">
                            <div class="image-wrapper mb-2">
                                <a href="{{ route('front.product.show', $special->slug) }}">
                                    <img src="{{ asset('product/' . ($special->photos->first()->photo ?? 'placeholder.jpg')) }}"
                                         class="main-product-img"
                                         id="img-{{ $special->id }}"
                                         alt="{{ $special->name }}">
                                </a>

                                @if($special->discount > 0)
                                    @php
                                        $percent = round(($special->discount / $special->price) * 100);
                                    @endphp
                                    <span class="promo-badge">{{ $percent }}% تخفیف</span>
                                @endif


                            </div>

                            <div class="color-swatches">
                                @foreach($special->photos as $photo)
                                    <div class="swatch {{ $loop->first ? 'active' : '' }}"
                                         data-image="{{ asset('product/' . $photo->photo) }}"
                                         onclick="changeProductImage(this, 'img-{{ $special->id }}')">
                                        <img src="{{ asset('product/' . $photo->photo) }}" alt="thumbnail">
                                    </div>
                                @endforeach
                            </div>

                            <div class="product-details mt-2 flex-grow-1">
                                <a href="{{ route('front.product.show', $special->slug) }}" class="text-decoration-none text-dark">
                                    <h3 class="product-name">{{ Str::limit($special->name, 40) }}</h3>
                                </a>

                                <div class="price-box mt-2">
                                    @if($special->discount > 0)
                                        @php
                                            $percent = round(($special->discount / $special->price) * 100);
                                        @endphp
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-danger text-white" style="font-size: 0.7rem;">{{ $percent }}% تخفیف</span>
                                            <span class="old-price text-muted small text-decoration-line-through">{{ number_format($special->price) }}</span>
                                        </div>
                                        <span class="current-price text-danger fw-bold fs-5">{{ number_format($special->price - $special->discount) }} <small style="font-size: 0.6em">تومان</small></span>
                                    @else
                                        <span class="current-price fw-bold fs-5">{{ number_format($special->price) }} <small style="font-size: 0.6em">تومان</small></span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('front.product.show', $special->slug) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                    مشاهده و خرید
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>



    </section>
    <section class="promo-banner">
        <div class="promo-bg">
            <img src="{{asset('banner/'.$bannerPrimary->photo)}}"  alt="{{$bannerPrimary->photo_alt}}">
        </div>
        <div class="promo-overlay"></div>
        <div class="promo-content">
            <h1 class="promo-title">{{$bannerPrimary->title}}</h1>
            <p style="font-size:1.2rem;">{{$bannerPrimary->description}}</p>
            <a href="{{$bannerPrimary->link}}" class="btn-black " >مشاهده محصولات   </a>
        </div>
    </section>

    <section class="interactive-grid">
        <div class="grid-container">

            <label class="blur-card">
                <input type="checkbox">
                <div class="card-media">
                    <img src="{{asset('banner/'.$bannerRight->photo)}}" alt="{{$bannerRight->photo_alt}}">
                </div>
                <div class="card-overlay">
                    <div class="overlay-text">
                        <h3>{{$bannerRight->title}}</h3>
                        <p>{{$bannerRight->description}}</p>

                        <a style="text-decoration: none; color: white;" href="{{$bannerRight->link}}" class="view-btn">مشاهده محصولات</a>
                    </div>
                </div>
            </label>

            <label class="blur-card">
                <input type="checkbox">
                <div class="card-media">
                    <img src="{{asset('banner/'.$bannerCenter->photo)}}" alt="{{$bannerCenter->photo_alt}}">
                </div>
                <div class="card-overlay">
                    <div class="overlay-text">
                        <h3>{{$bannerCenter->title}}</h3>
                        <p>{{$bannerCenter->description}}</p>

                        <a style="text-decoration: none; color: white;" href="{{$bannerCenter->link}}" class="view-btn">مشاهده محصولات</a>
                    </div>
                </div>
            </label>
            <label class="blur-card">
                <input type="checkbox">
                <div class="card-media">
                    <img src="{{asset('banner/'.$bannerLeft->photo)}}" alt="{{$bannerLeft->photo_alt}}">
                </div>
                <div class="card-overlay">
                    <div class="overlay-text">
                        <h3>{{$bannerLeft->title}}</h3>
                        <p>{{$bannerLeft->description}}</p>

                        <a style="text-decoration: none; color: white;" href="{{$bannerLeft->link}}" class="view-btn">مشاهده محصولات</a>
                    </div>
                </div>
            </label>

        </div>
    </section>


    <section class="latest-products">
        <div class="section-header">
            <h2>جدیدترین محصولات</h2>
            <a href="{{route('front.search')}}" class="view-all">مشاهده همه</a>
        </div>

        <div class="product-scroll-container">

            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="product-item h-100 shadow-sm bg-white p-2 d-flex flex-column">
                        <div class="image-wrapper mb-2">
                            <a href="{{ route('front.product.show', $product->slug) }}">
                                <img src="{{ asset('product/' . ($product->photos->first()->photo ?? 'placeholder.jpg')) }}"
                                     class="main-product-img"
                                     id="img-{{ $product->id }}"
                                     alt="{{ $product->name }}">
                            </a>

                            @if($product->discount > 0)
                                @php
                                    $percent = round(($product->discount / $product->price) * 100);
                                @endphp
                                <span class="promo-badge">{{ $percent }}% تخفیف</span>
                            @endif


                        </div>

                        <div class="color-swatches">
                            @foreach($product->photos as $photo)
                                <div class="swatch {{ $loop->first ? 'active' : '' }}"
                                     data-image="{{ asset('product/' . $photo->photo) }}"
                                     onclick="changeProductImage(this, 'img-{{ $product->id }}')">
                                    <img src="{{ asset('product/' . $photo->photo) }}" alt="thumbnail">
                                </div>
                            @endforeach
                        </div>

                        <div class="product-details mt-2 flex-grow-1">
                            <a href="{{ route('front.product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                <h3 class="product-name">{{ Str::limit($product->name, 40) }}</h3>
                            </a>

                            <div class="price-box mt-2">
                                @if($product->discount > 0)
                                    @php
                                        $percent = round(($product->discount / $product->price) * 100);
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-danger text-white" style="font-size: 0.7rem;">{{ $percent }}% تخفیف</span>
                                        <span class="old-price text-muted small text-decoration-line-through">{{ number_format($product->price) }}</span>
                                    </div>
                                    <span class="current-price text-danger fw-bold fs-5">{{ number_format($product->price - $product->discount) }} <small style="font-size: 0.6em">تومان</small></span>
                                @else
                                    <span class="current-price fw-bold fs-5">{{ number_format($product->price) }} <small style="font-size: 0.6em">تومان</small></span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('front.product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                مشاهده و خرید
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach



        </div>
    </section>
    <section class="promo-banner">
        <div class="promo-bg">
            <img src="{{asset('banner/'.$bannerPrimary2->photo)}}"  alt="{{$bannerPrimary2->photo_alt}}">
        </div>
        <div class="promo-overlay"></div>
        <div class="promo-content">
            <h1 class="promo-title">{{$bannerPrimary2->title}}</h1>
            <p style="font-size:1.2rem;">{{$bannerPrimary2->description}}</p>
            <a href="{{$bannerPrimary2->link}}" class="btn-black " >مشاهده محصولات  </a>
        </div>
    </section>


    <section class="latest-articles">
        <div class="section-header">
            <h2>آخرین مطالب مجله افشارکالا</h2>
            <a href="{{route('front.articles.show')}}" class="view-all">مشاهده وبلاگ</a>
        </div>

            <div class="articles-grid">
                @foreach($blogs as $blog)

                    <article class="article-card">
                        <div class="article-thumb">
                            <img src="{{asset('blog/'.$blog->photo)}}"
                                 alt="{{$blog->photo_alt}}">
                            <span class="date">{{jdate($blog->created_at)->format('Y/m/d')}}</span>
                        </div>
                        <div class="article-content">
                            <h3>{{$blog->title}}</h3>
                            {{-- <p>انتخاب تجهیزات مناسب و لایه‌بندی لباس‌ها کلید موفقیت در برنامه‌های کوهنوردی سرد است...</p>--}}
                            <a href="{{route('front.article.show',$blog->slug)}}" class="read-more">ادامه مطلب ←</a>
                        </div>
                    </article>

                @endforeach



        </div>
    </section>

@endsection
@push('scripts')

    <script>
        // اضافه کردن در فایل جاوااسکریپت اصلی یا قبل از تگ </body>
        document.addEventListener('DOMContentLoaded', function() {
            const heroSwiper = new Swiper('.heroSwiper', {
                // تنظیمات پایه
                loop: true,
                speed: 800,
                effect: 'fade', // می‌توانید به 'slide', 'fade', 'cube', 'coverflow' تغییر دهید
                fadeEffect: {
                    crossFade: true
                },

                // اتوپلی
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },

                // ناوبری
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // نقاط صفحه
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true
                },

                // لمسی و ماوس
                touchRatio: 1,
                grabCursor: true,

                // انیمیشن
                on: {
                    init: function() {
                        console.log('Hero slider initialized');
                    }
                }
            });
        });

    </script>
@endpush
