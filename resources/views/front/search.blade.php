@extends('front.layout.master')



@section('content')

    <section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('front/assets/images/about-hero.jpg') }}') no-repeat center center/cover; color: white;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-end">
                    <h1 class="display-4 fw-bold mb-4">جستجو در فروشگاه</h1>

                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="{{asset('front/assets/columbia-brand.png')}}" alt="لوگو نورث فیس" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- بخش محصولات دسته‌بندی -->
    <section class="py-5 bg-light">
        <div class="container px-4">
            <div class="row mb-4">
                <div class="col-lg-6">

                    <p class="text-muted">{{ $products->total() }} محصول یافت شد</p>
                </div>


                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-lg-end">
                        <!-- جستجو -->
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-lg-end">

                                <form action="{{ route('front.search') }}" method="GET"
                                      class="d-flex align-items-center"
                                      style="width: 100%; max-width: 280px;">

                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control form-control-sm"
                                               placeholder="جستجو..."
                                               value="{{ request('search') }}"
                                               style="border-radius: 0 5px 5px 0;">

                                        <button type="submit"
                                                class="btn btn-primary btn-sm"
                                                style="border-radius: 5px 0 0 5px; margin: 0;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <!-- مرتب‌سازی -->
                        <form action="{{ route('front.search') }}" method="GET">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>جدیدترین</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>ارزان‌ترین</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>گران‌ترین</option>
                             </select>
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                        </form>
                    </div>
                </div>


            <!-- لیست محصولات -->
            @if($products->count() > 0)
                <div class="row g-4">
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

                <!-- صفحه‌بندی -->
                <div class="mt-5">
                    {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h4>محصولی در این دسته یافت نشد.</h4>
                    <a href="{{ route('home.index') }}" class="btn btn-primary mt-3">بازگشت به صفحه اصلی</a>
                </div>
            @endif
        </div>
    </section>

@endsection
