@extends('front.layout.master')

@section('page_title')
    {{ $product->page_title  }}
@endsection

@section('meta_description')
    {{ $product->meta_description }}
@endsection

@section('content')

    <section class="py-5" style="margin-top: 100px;">
        <div class="container px-4">
            <div class="row g-5">
                <!-- گالری عکس -->
                <div class="col-lg-6">
                    <div class="swiper product-gallery mb-3">
                        <div class="swiper-wrapper">
                            @foreach($product->photos as $photo)
                                <div class="swiper-slide">
                                    <div class="ratio ratio-1x1"> <!-- یا ratio-4x3 اگر می‌خوای مستطیل باشه -->
                                        <img src="{{ asset('product/' . $photo->photo) }}"
                                             alt="{{ $photo->photo_alt  }}"
                                             class="w-100 h-100 rounded-3 object-fit-cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- thumbnails کوچک (اختیاری) -->
                    <div class="swiper product-thumbnails">
                        <div class="swiper-wrapper">
                            @foreach($product->photos as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ asset('product/' . $photo->photo) }}"
                                         alt="{{$photo->photo_alt}}"  class="w-100 h-100 rounded object-fit-cover"
                                         style="height: 80px;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- جزئیات محصول -->
                <div class="col-lg-6">
                    <h1 class="fw-bold mb-3">{{ $product->name }}</h1>

                    <!-- قیمت -->
                    <div class="mb-4" id="product-price-box">
                        @php
                            $firstVariant = $product->variants->first();
                            $price    = $firstVariant?->price ?? 0;
                            $discount = $firstVariant?->discount ?? 0;
                        @endphp

                        @if($discount > 0)
                            <span class="text-muted text-decoration-line-through fs-4">
            {{ number_format($price) }} تومان
        </span>
                            <span class="text-danger fw-bold fs-2 me-3">
            {{ number_format($price - $discount) }} تومان
        </span>
                        @else
                            <span class="fw-bold fs-2">{{ number_format($price) }} تومان</span>
                        @endif
                    </div>

                    <!-- انتخاب رنگ -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">رنگ:</label>
                        @if($availableColors->count() > 0)
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach($availableColors as $color)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="color" id="color-{{ $color->id }}"
                                               value="{{ $color->id }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center gap-2" for="color-{{ $color->id }}">
                                            <div class="color-box" style="background: {{ $color->code }}; width: 30px; height: 30px; border: 2px solid #ddd; border-radius: 50%;"></div>
                                            <span>{{ $color->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-danger">رنگ موجودی در حال حاضر موجود نیست.</p>
                        @endif
                    </div>

                    <!-- انتخاب سایز -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">سایز:</label>
                        @if($availableSizes->count() > 0)
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach($availableSizes as $size)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="size" id="size-{{ $size->id }}"
                                               value="{{ $size->id }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size-{{ $size->id }}">
                                            {{ $size->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-danger">سایز موجودی در حال حاضر موجود نیست.</p>
                        @endif
                    </div>

                    <!-- تعداد -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">تعداد:</label>
                        <input type="number" id="quantity" class="form-control w-25" value="1" min="1">
                        <div class="text text-danger" style="margin-top:10px;" id="low-stock-warning"></div>
                    </div>

                    <!-- دکمه افزودن به سبد -->
                    <button id="add-to-cart-btn" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-bag-plus"></i> افزودن به سبد خرید
                    </button>
                    <div id="cart-result-message" class="mt-3"></div>
                </div>
            </div>

            <!-- جدول مشخصات فنی -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">مشخصات فنی</h3>
                    <table class="table table-bordered">
                        <tbody>
                        <tr><th width="30%">جنس</th><td>{{ $product->material ?? 'نامشخص' }}</td></tr>
                        <tr><th>وزن</th><td>{{ $product->weight ?? 'نامشخص' }}</td></tr>
                        <tr><th>ابعاد</th><td>{{ $product->dimension ?? 'نامشخص' }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- توضیحات کامل -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">توضیحات محصول</h3>
                    <div class="ck-content">
                        {!! $product->description !!} <!-- چون با CKEditor ذخیره شده -->
                    </div>
                </div>
            </div>

            <!-- نظرات -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">نظرات کاربران ({{ $product->product_comments->count() }})</h3>
                    @foreach($coments as $comment)
                        <div class="border rounded p-4 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $comment->name }}</strong>
                                <small class="text-muted">  {{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->format('Y/m/d ') }}</small>
                            </div>
                            <p class="mt-2">{{ $comment->comment }}</p>
                            @if($comment->admin_response)
                                <div class="bg-light p-3 rounded mt-3 border-start border-primary border-4">
                                    <strong>پاسخ ادمین:</strong>
                                    <p class="mb-0">{{ $comment->admin_response }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if($product->product_comments->count() == 0)
                        <p class="text-muted">هنوز نظری ثبت نشده است. اولین نفر باشید!</p>
                    @endif
                    <div class="mt-3">
                        {{ $coments->links() }}
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="fw-bold mb-4">نظر خود را بنویسید</h4>
                    <form id="comment-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label class="form-label">نام شما</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نظر شما</label>
                            <textarea name="comment" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">ارسال نظر</button>
                        <div id="comment-message" class="mt-3"></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- فرم ثبت نظر -->

    </section>

    {{-- اسکریپت AJAX چک موجودی و افزودن به سبد --}}
    <script id="variants-data-{{ $product->id }}" type="application/json">
        {!! $product->variants->map(fn($v) => [
            'color_id' => $v->color_id,
            'size_id'  => $v->size_id,
            'price'    => $v->price,
            'discount' => $v->discount ?? 0,
        ])->toJson() !!}
    </script>
    <script>


        // تعریف المان‌های نمایش پیام به صورت جداگانه
        const lowStockEl = document.getElementById('low-stock-warning');
        const cartResultEl = document.getElementById('cart-result-message');

        // ۱. تابع چک کردن موجودی لحظه‌ای (برای هشدار ۱ عدد)
        function checkInstantStock() {
            const productId = {{ $product->id }};
            const colorId = document.querySelector('input[name="color"]:checked')?.value;
            const sizeId = document.querySelector('input[name="size"]:checked')?.value;

            if (colorId && sizeId) {
                fetch("/product/check-stock", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        color_id: colorId,
                        size_id: sizeId,
                        quantity: 1
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        // فقط اگر موجودی دقیقا ۱ بود، متن قرمز کوچک نمایش داده شود
                        if (data.available && data.stock === 1) {
                            lowStockEl.innerHTML = 'فقط ۱ عدد در انبار باقی مانده است!';
                        } else {
                            lowStockEl.innerHTML = '';
                        }
                    });
            }
        }
        function updateProductPagePrice() {
            const productId = {{ $product->id }};
            const colorId = parseInt(document.querySelector('input[name="color"]:checked')?.value);
            const sizeId  = parseInt(document.querySelector('input[name="size"]:checked')?.value);

            const el = document.getElementById('variants-data-' + productId);
            if (!el) return;
            const variants = JSON.parse(el.textContent);

            let variant = null;
            if (colorId && sizeId) {
                variant = variants.find(v => v.color_id === colorId && v.size_id === sizeId);
            }
            if (!variant && colorId) {
                variant = variants.find(v => v.color_id === colorId);
            }
            if (!variant) variant = variants[0];

            if (!variant) return;

            const fmt = n => Number(n).toLocaleString('en-US');
            const box = document.getElementById('product-price-box');
            const price = variant.price;
            const discount = variant.discount || 0;

            if (discount > 0) {
                const pct = Math.round((discount / price) * 100);
                box.innerHTML = `
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger">${pct}% تخفیف</span>
                <span class="text-muted text-decoration-line-through fs-4">${fmt(price)} تومان</span>
            </div>
            <span class="text-danger fw-bold fs-2">${fmt(price - discount)} تومان</span>`;
            } else {
                box.innerHTML = `<span class="fw-bold fs-2">${fmt(price)} تومان</span>`;
            }
        }

        document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
            input.addEventListener('change', () => {
                checkInstantStock();
                updateProductPagePrice();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            checkInstantStock();
            updateProductPagePrice();
        });

        // ۲. گوش دادن به تغییرات انتخاب رنگ و سایز
        document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
            input.addEventListener('change', checkInstantStock);
        });

        // ۳. مدیریت کلیک روی دکمه افزودن به سبد خرید
        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
            const productId = {{ $product->id }};
            const colorId = document.querySelector('input[name="color"]:checked')?.value;
            const sizeId = document.querySelector('input[name="size"]:checked')?.value;
            const quantity = parseInt(document.getElementById('quantity').value) || 1;

            // پاک کردن پیام قبلی دکمه
            cartResultEl.innerHTML = '';

            if (!colorId || !sizeId) {
                cartResultEl.innerHTML = '<div class="alert alert-warning">لطفاً رنگ و سایز را انتخاب کنید.</div>';
                return;
            }

            // مرحله اول: چک نهایی موجودی قبل از افزودن
            fetch("/product/check-stock", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ product_id: productId, color_id: colorId, size_id: sizeId, quantity: quantity })
            })
                .then(res => res.json())
                .then(stockData => {
                    if (!stockData.available) {
                        // اگر موجود نبود، پیام خطا در باکس پایین دکمه نمایش داده شود
                        cartResultEl.innerHTML = `<div class="alert alert-danger">${stockData.message}</div>`;
                        return;
                    }

                    // مرحله دوم: اگر موجود بود، ارسال به سبد خرید
                    return fetch("/cart/add", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ product_id: productId, color_id: colorId, size_id: sizeId, quantity: quantity })
                    });
                })
                .then(res => res?.json())
                .then(cartData => {
                    if (cartData && cartData.success) {
                        cartResultEl.innerHTML = `<div class="alert alert-success">${cartData.message}</div>`;
                        if(typeof updateCartBadge === 'function') updateCartBadge(cartData.total_items);
                    }
                })
                .catch(err => {
                    console.error(err);
                    cartResultEl.innerHTML = '<div class="alert alert-danger">خطایی در سیستم رخ داد.</div>';
                });
        });

        // اجرای چک اولیه در هنگام لود صفحه
        document.addEventListener('DOMContentLoaded', checkInstantStock);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Swiper برای گالری -->
    <script>
        const galleryThumbs = new Swiper('.product-thumbnails', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        const galleryMain = new Swiper('.product-gallery', {
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: { el: '.swiper-pagination' },
            thumbs: { swiper: galleryThumbs }
        });
    </script>
    <script>
        document.getElementById('comment-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageEl = document.getElementById('comment-message');

            fetch("{{ route('front.product.comment.store', $product) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        messageEl.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        this.reset();
                    } else {
                        messageEl.innerHTML = '<div class="alert alert-danger">خطایی رخ داد.</div>';
                    }
                })
                .catch(() => {
                    messageEl.innerHTML = '<div class="alert alert-danger">خطایی در ارسال رخ داد.</div>';
                });
        });
    </script>

@endsection
