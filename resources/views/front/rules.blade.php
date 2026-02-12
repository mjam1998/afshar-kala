
@extends('front.layout.master')


@section('content')

    <section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('front/assets/images/about-hero.jpg') }}') no-repeat center center/cover; color: white;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-end">
                    <h1 class="display-4 fw-bold mb-4">قوانین و مقررات</h1>
                    <p class="lead">شرایط و ضوابط استفاده از فروشگاه افشار کالا</p>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="{{asset('front/assets/columbia-brand.png')}}" alt="" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <p class="lead text-muted mb-5">
                                استفاده از فروشگاه اینترنتی افشار کالا به معنی پذیرش کامل شرایط و قوانین زیر است. این قوانین بر اساس قانون تجارت الکترونیکی، قانون حمایت از حقوق مصرف‌کنندگان و سایر قوانین جمهوری اسلامی ایران تدوین شده است.
                            </p>

                            <h3 class="fw-bold mb-4">۱. تعاریف</h3>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="bi bi-check-circle text-primary me-2"></i><strong>فروشگاه:</strong> وب‌سایت رسمی لوازم خانگی افشار کالا</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-primary me-2"></i><strong>کاربر/مشتری:</strong> هر شخص حقیقی یا حقوقی که از خدمات سایت استفاده می‌کند.</li>
                                <li class="mb-3"><i class="bi bi-check-circle text-primary me-2"></i><strong>سفارش:</strong> درخواست خرید کالا از فروشگاه.</li>
                            </ul>

                            <h3 class="fw-bold mb-4 mt-5">۲. ثبت سفارش و خرید</h3>
                            <p class="text-muted">
                                - تمامی قیمت‌ها به تومان ایران است و شامل مالیات بر ارزش افزوده می‌باشد.<br>
                                - فروشگاه حق تغییر قیمت محصولات بدون اطلاع قبلی را دارد، اما پس از ثبت سفارش، قیمت ثابت می‌ماند.<br>
                                - ثبت سفارش به معنی پذیرش قوانین سایت است.<br>
                                - فروشگاه متعهد به ارسال محصولات اورجینال با ضمانت اصالت کالا است.
                            </p>

                            <h3 class="fw-bold mb-4 mt-5">۳. ارسال مرسولات</h3>
                            <p class="text-muted">
                                - ارسال سفارشات به سراسر ایران از طریق پست پیشتاز، تیپاکس یا پیک موتوری (برای ساری) انجام می‌شود.<br>
                                - هزینه ارسال بر اساس وزن، ابعاد و مقصد محاسبه شده و در مرحله پرداخت نمایش داده می‌شود.<br>
                                - زمان تحویل تقریبی: ساری ۱-۲ روز کاری، سایر شهرها ۲-۵ روز کاری (به غیر از تعطیلات).<br>
                                - ارسال رایگان برای سفارشات بالای مبلغ مشخص (در صورت اعمال تخفیف).<br>
                                - مسئولیت آسیب‌های ناشی از حمل و نقل بر عهده شرکت پستی است و فروشگاه در صورت بیمه شدن مرسوله، پیگیری خسارت را انجام می‌دهد.<br>
                                - مشتریان می‌توانند وضعیت سفارش خود را از طریق صفحه <a href="{{ route('order.track.form') }}" class="text-primary">پیگیری سفارش</a> با وارد کردن کد پیگیری یا شماره سفارش مشاهده کنند.
                            </p>

                            <h3 class="fw-bold mb-4 mt-5">۴. بازگشت کالا و استرداد وجه</h3>
                            <p class="text-muted">
                                - طبق قانون حمایت از مصرف‌کنندگان، مشتری تا ۷ روز فرصت دارد کالا را مرجوع کند (به شرط باز نشدن پلمپ و عدم استفاده).<br>
                                - هزینه بازگشت کالا بر عهده مشتری است مگر در موارد اشتباه فروشگاه.<br>
                                - پس از تایید بازگشت، وجه در ۴۸-۷۲ ساعت کاری استرداد می‌شود.<br>
                                - کالاهای بهداشتی، لباس زیر و محصولات سفارشی قابل بازگشت نیستند.
                            </p>

                            <h3 class="fw-bold mb-4 mt-5">۵. حریم خصوصی و امنیت</h3>
                            <p class="text-muted">
                                - اطلاعات شخصی مشتریان محرمانه است و تنها برای پردازش سفارش استفاده می‌شود.<br>
                                - فروشگاه از پروتکل‌های امن پرداخت استفاده می‌کند.<br>
                                - هیچ اطلاعاتی به شخص ثالث ارائه نمی‌شود مگر با حکم قانونی.
                            </p>

                            <h3 class="fw-bold mb-4 mt-5">۶. پیگیری سفارش</h3>
                            <p class="text-muted">
                                - پس از ثبت سفارش، کد پیگیری از طریق ایمیل و پیامک ارسال می‌شود.<br>
                                - مشتریان می‌توانند در هر زمان از صفحه <a href="{{ route('order.track.form') }}" class="text-primary">پیگیری سفارش</a> وضعیت مرسوله خود را چک کنند.<br>
                                - در صورت تاخیر یا مشکل، با پشتیبانی تماس بگیرید.
                            </p>

                            <h3 class="fw-bold mb-4 mt-5">۷. سایر موارد</h3>
                            <p class="text-muted">
                                - فروشگاه تابع قوانین جمهوری اسلامی ایران است و هیچ محتوای خلاف قوانین منتشر نمی‌کند.<br>
                                - نظرات کاربران باید منطبق با قوانین باشد و فروشگاه حق ویرایش یا حذف را دارد.<br>
                                - تغییرات قوانین در همین صفحه اعلام می‌شود.
                            </p>

                            <p class="text-center mt-5 text-muted">
                                آخرین بروزرسانی:  ۱۴۰۴<br>
                                با تشکر از اعتماد شما به افشار کالا
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
