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
                        @php
                            // اولین variant که تخفیف داره، وگرنه اولین variant
                            $defaultVariant = $product->variants->first(fn($v) => $v->discount > 0)
                                            ?? $product->variants->first();
                            $displayPrice    = $defaultVariant?->price ?? 0;
                            $displayDiscount = $defaultVariant?->discount ?? 0;
                        @endphp

                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="product-item h-100 shadow-sm bg-white p-2 d-flex flex-column">

                                <div class="image-wrapper mb-2">
                                    <a href="{{ route('front.product.show', $product->slug) }}">
                                        <img src="{{ asset('product/' . ($product->photos->first()->photo ?? 'placeholder.jpg')) }}"
                                             class="main-product-img"
                                             id="img-{{ $product->id }}"
                                             alt="{{ $product->name }}">
                                    </a>
                                    @if($displayDiscount > 0)
                                        @php $percent = round(($displayDiscount / $displayPrice) * 100); @endphp
                                        <span class="promo-badge">{{ $percent }}% تخفیف</span>
                                    @endif
                                </div>
                                {{-- thumbnailهای عکس --}}
                                @if($product->photos->count() > 1)
                                    <div class="color-swatches">
                                        @foreach($product->photos as $photo)
                                            <div class="swatch {{ $loop->first ? 'active' : '' }}"
                                                 data-image="{{ asset('product/' . $photo->photo) }}"
                                                 onclick="changeProductImage(this, {{ $product->id }})">
                                                <img src="{{ asset('product/' . $photo->photo) }}" alt="thumbnail">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                {{-- رنگ‌ها --}}
                                <div class="color-circles d-flex gap-1 flex-wrap mb-1"
                                     id="colors-p{{ $product->id }}"
                                     data-product-id="p{{ $product->id }}">
                                    @foreach($product->variants->pluck('color')->unique('id')->filter() as $color)
                                        <span class="color-circle {{ $loop->first ? 'active' : '' }}"
                                              style="background-color: {{ $color->code }};"
                                              title="{{ $color->name }}"
                                              data-color-id="{{ $color->id }}"
                                              data-product-id="p{{ $product->id }}"
                                              onclick="selectColor(this)">
        </span>
                                    @endforeach
                                </div>

                                {{-- سایزها --}}
                                <div class="size-badges d-flex gap-1 flex-wrap mb-1"
                                     id="sizes-p{{ $product->id }}">
                                    @foreach($product->variants->pluck('size')->unique('id')->filter() as $size)
                                        <span class="size-badge {{ $loop->first ? 'active' : '' }}"
                                              data-size-id="{{ $size->id }}"
                                              data-product-id="p{{ $product->id }}"
                                              onclick="selectSize(this)">
            {{ $size->name }}
        </span>
                                    @endforeach
                                </div>

                                <div class="product-details mt-2 flex-grow-1">
                                    <a href="{{ route('front.product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        <h3 class="product-name">{{ Str::limit($product->name, 40) }}</h3>
                                    </a>

                                    {{-- قیمت --}}
                                    <div class="price-box mt-2" id="price-box-p{{ $product->id }}">
                                        @if($displayDiscount > 0)
                                            @php $percent = round(($displayDiscount / $displayPrice) * 100); @endphp
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge bg-danger text-white" style="font-size:0.7rem;">{{ $percent }}% تخفیف</span>
                                                <span class="old-price text-muted small text-decoration-line-through">{{ number_format($displayPrice) }}</span>
                                            </div>
                                            <span class="current-price text-danger fw-bold fs-5">
                            {{ number_format($displayPrice - $displayDiscount) }}
                            <small style="font-size:0.6em">تومان</small>
                        </span>
                                        @elseif($displayPrice > 0)
                                            <span class="current-price fw-bold fs-5">
                            {{ number_format($displayPrice) }}
                            <small style="font-size:0.6em">تومان</small>
                        </span>
                                        @else
                                            <span class="text-muted small">موجودی ندارد</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- داده‌های variants برای JS --}}
                                @php
                                    $variantsJson = $product->variants->map(function($v) {
                                        return [
                                            'color_id'   => $v->color_id,
                                            'size_id'    => $v->size_id,
                                            'price'      => $v->price,
                                            'discount'   => $v->discount,
                                            'count'      => $v->count,
                                            'color_code' => optional($v->color)->code,
                                            'color_name' => optional($v->color)->name,
                                            'size_name'  => optional($v->size)->name,
                                        ];
                                    })->toJson();
                                @endphp
                                <script type="application/json" id="variants-data-p{{ $product->id }}">
                                    {!! $variantsJson !!}
                                </script>

                                <div class="mt-3">
                                    <a href="{{ route('front.product.show', $product->slug) }}"
                                       class="btn btn-outline-primary btn-sm w-100 rounded-pill">
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
