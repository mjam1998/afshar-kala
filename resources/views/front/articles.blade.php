@extends('front.layout.master')



@section('content')

    <section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('front/assets/images/about-hero.jpg') }}') no-repeat center center/cover; color: white;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-end">
                    <h1 class="display-4 fw-bold mb-4">مقالات</h1>

                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="{{asset('front/assets/columbia-brand.png')}}" alt="columbia iran" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </section>


    <section class="py-5 bg-light">
        <div class="container px-4">
            <div class="row mb-4">
                <div class="col-lg-6">

                    <p class="text-muted">{{ $blogs->total() }} مقاله یافت شد</p>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex justify-content-lg-end">

                        <form action="{{ route('front.articles.show') }}" method="GET"
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
            </div>

            <!-- لیست محصولات -->
            @if($blogs->count() > 0)
                <div class="row g-4">
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
                </div>

                <!-- صفحه‌بندی -->
                <div class="mt-5">
                    {{ $blogs->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <h4>مقاله ای در این دسته یافت نشد.</h4>
                    <a href="{{ route('home.index') }}" class="btn btn-primary mt-3">بازگشت به صفحه اصلی</a>
                </div>
            @endif
        </div>
    </section>
@endsection

