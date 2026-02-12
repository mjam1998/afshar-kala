@extends('front.layout.master')

@section('content')

    <!-- Hero Section -->
    <section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('front/assets/images/about-hero.jpg') }}') no-repeat center center/cover; color: white;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-end">
                    <h1 class="display-4 fw-bold mb-4">درباره فروشگاه افشار کالا</h1>
                    <p class="lead">بهترین برندها با ضمانت اصالت، قیمت رقابتی و ارسال سریع به سراسر کشور</p>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="{{asset('front/assets/columbia-brand.png')}}" alt="لوگو کلمبیا" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- About Brand Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center flex-row-reverse flex-lg-row">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4 text-primary">تاریخچه افشار کالا</h2>
                    <p class="lead text-muted">افشار کالا بیش از چهار سال است که به‌عنوان یکی از پیشروترین فروشگاه‌های لوازم خانگی در شهر ساری فعالیت می‌کند. این فروشگاه فعالیت خود را در سال 1400 با هدف ارائه کالای اصل، خدمات پس‌ازفروش مطمئن و تجربه خریدی سریع و دلپذیر آغاز کرد. از همان روزهای نخست، افشار کالا توانست با انتخاب دقیق برندهای معتبر و ایجاد رابطه‌ای مبتنی بر اعتماد با مشتریان، نامی قابل احترام در بازار منطقه باشد.
                    </p>
                    <p class="lead text-muted">
                        در طول این سال‌ها، ما همواره تلاش کرده‌ایم تا نیازهای خانواده‌های ساری و استان مازندران را با جدیدترین و باکیفیت‌ترین محصولات برطرف کنیم — از لوازم آشپزخانه تا وسایل برقی بزرگ — و خدماتی ارائه دهیم که خرید را برای شما آسان و مطمئن کند. مشتری‌مداری، اصالت کالا، قیمت‌گذاری منصفانه و ارسال سریع، از اصولی بوده که افشار کالا به آن پایبند است. امروز، با بیش از یک دهه حضور فعال در قلب ساری، افشار کالا همچنان با انرژی و تعهدی دوچندان در کنار شماست تا کیفیت و آسایش را به خانه‌های شما بیاورد.
                    </p>

                </div>
                <div class="col-lg-6 text-center mb-4 mb-lg-0">
                    <img src="{{asset('front/assets/history.png')}}" alt="افشار کالا" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>



    <!-- Call to Action -->
    <section class="py-5 text-center" style="background-color: #000; color: white;">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4">افشار کالا؛ انتخاب مطمئن خانه شما</h2>
            <a href="{{ route('home.index') }}" class="btn btn-warning btn-lg px-5 py-3">مشاهده محصولات</a>
        </div>
    </section>

@endsection
