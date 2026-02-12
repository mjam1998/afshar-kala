@extends('front.layout.master')

@section('page_title', 'نتیجه پرداخت')

@section('content')
    <div class="container py-5" style="margin-top: 100px; min-height: 70vh;">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- هدر رنگی -->
                    <div class="text-center py-5 px-4 {{ $order->is_paid ? 'bg-success' : 'bg-danger' }} text-white">
                        <i class="bi {{ $order->is_paid ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} display-1 mb-3"></i>
                        <h2 class="fw-bold mb-0">
                            {{ $order->is_paid ? 'پرداخت با موفقیت انجام شد!' : 'پرداخت ناموفق بود' }}
                        </h2>
                    </div>

                    <!-- بدنه کارت -->
                    <div class="card-body p-5">
                        <!-- پیام اصلی -->
                        <div class="alert {{ $order->is_paid ? 'alert-success' : 'alert-danger' }} text-center fs-5 mb-4">
                            {{ $order->is_paid
                                ? 'سفارش شما با موفقیت ثبت شد و پرداخت انجام گردید،لطفا کد پیگیری سفارش را برای پیگیری سفارش در نزد خود نگه دارید.'
                                : 'متأسفانه پرداخت انجام نشد. در صورت کسر وجه از حساب شما، مبلغ تا ۴۸ ساعت آینده بازگردانده خواهد شد.'
                            }}
                        </div>

                        <!-- جزئیات پرداخت -->
                        <div class="bg-light rounded-4 p-4">
                            <h5 class="fw-bold mb-4 text-center" style="color: #00a036;">
                                <i class="bi bi-receipt me-2"></i>
                                جزئیات تراکنش
                            </h5>

                            <div class="row g-3 text-lg">
                                <div class="col-6 fw-bold text-muted">کد پیگیری سفارش:</div>
                                <div class="col-6 text-start fw-bold dir-ltr">{{ $order->track_number ?? '—' }}</div>

                                <div class="col-6 fw-bold text-muted">شماره تراکنش بانک:</div>
                                <div class="col-6 text-start fw-bold dir-ltr">{{ $order->trans_id ?? '—' }}</div>

                                <div class="col-6 fw-bold text-muted">مبلغ پرداختی:</div>
                                <div class="col-6 text-start fw-bold text-success">
                                    {{ number_format($order->pay_amount) }} تومان
                                </div>

                                <div class="col-6 fw-bold text-muted">تاریخ و ساعت پرداخت:</div>
                                <div class="col-6 text-start fw-bold dir-ltr">
                                    {{ $order->paid_at
                                        ? \Morilog\Jalali\Jalalian::forge($order->paid_at)->format('Y/m/d ساعت H:i')
                                        : '—'
                                    }}
                                </div>
                            </div>
                        </div>

                        <!-- نکته برای پرداخت ناموفق -->
                        @if(!$order->is_paid)
                            <div class="alert alert-warning mt-4 text-center">
                                <i class="bi bi-info-circle me-2"></i>
                                در صورت کسر وجه از حساب شما و عدم بازگشت خودکار، لطفاً با <strong>پشتیبانی</strong> تماس بگیرید.
                            </div>
                        @endif

                        <!-- دکمه‌های اقدام -->
                        <div class="d-grid gap-3 mt-5">
                            <a href="{{ route('home.index') }}" class="btn btn-outline-dark btn-lg rounded-pill">
                                <i class="bi bi-house-door me-2"></i>
                                بازگشت به صفحه اصلی
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.navbar').classList.add('force-white');
    </script>
@endsection
