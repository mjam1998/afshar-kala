

@extends('front.layout.master')

@section('page_title', $article->page_title)

@section('meta_description', $article->meta_description )

@section('content')
<div style="margin-top: 100px;">
    <!-- BreadCrumb -->
    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="text-decoration: none;color: black;">خانه</a></li>
                <li class="breadcrumb-item"><a href="{{ route('front.articles.show') }}" style="text-decoration: none;color: black;">بلاگ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
            </ol>
        </nav>
    </div>

    <!-- Article Content -->
    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <!-- عنوان مقاله -->
                <h1 class="mb-4 text-center fw-bold">{{ $article->title }}</h1>

                <!-- تصویر اصلی مقاله (اگر وجود داشت) -->
                @if($article->photo)
                    <div class="text-center mb-5">
                        <img src="{{ asset('blog/' . $article->photo) }}"
                             alt="{{ $article->photo_alt  }}"
                             class="img-fluid rounded shadow"
                             style="max-height: 500px; object-fit: cover;">
                        @if($article->photo_alt)
                            <p class="text-muted small mt-2">{{ $article->photo_alt }}</p>
                        @endif
                    </div>
                @endif


                <div class="article-content lh-lg text-justify">
                    {!! $article->description !!}
                </div>

                <!-- تاریخ انتشار یا سایر اطلاعات (اختیاری) -->
                <div class="mt-5 pt-4 border-top">
                    <small class="text-muted">
                        منتشر شده در: {{ jdate($article->created_at)->format('Y/m/d') }}
                    </small>
                </div>

                <hr class="my-5">


            </div>
        </div>
    </div>

</div>


@endsection
