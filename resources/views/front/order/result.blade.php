@extends('front.layout.master')

@section('page_title', 'نتیجه پیگیری سفارش')

@section('content')
    <div class="container py-5" style="margin-top: 100px; min-height: 70vh;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- هدر رنگی بر اساس وضعیت پرداخت -->
                    <div class="text-center py-4 text-white {{ $order->is_paid ? 'bg-success' : 'bg-warning' }}">
                        <i class="bi {{ $order->is_paid ? 'bi-check-circle-fill' : 'bi-clock-history' }} display-1 mb-3"></i>
                        <h3 class="fw-bold mb-0">
                            {{ $order->is_paid ? 'سفارش پرداخت شده' : 'در انتظار پرداخت' }}
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3 text-primary">اطلاعات سفارش</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>کد پیگیری:</strong>
                                        <span dir="ltr">{{ $order->track_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>نام گیرنده:</strong>
                                        <span>{{ $order->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>موبایل:</strong>
                                        <span dir="ltr">{{ $order->mobile }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>تاریخ سفارش:</strong>
                                        <span dir="ltr">
                                        {{ \Morilog\Jalali\Jalalian::forge($order->created_at)->format('Y/m/d H:i') }}
                                    </span>
                                    </li>
                                    @if($order->paid_at)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>تاریخ پرداخت:</strong>
                                            <span dir="ltr">
                                        {{ \Morilog\Jalali\Jalalian::forge($order->paid_at)->format('Y/m/d H:i') }}
                                    </span>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>شماره تراکنش بانک:</strong>
                                        <span dir="ltr">{{ $order->trans_id ?? '—' }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3 text-primary">وضعیت ارسال</h5>
                                <div class="text-center p-4 rounded-3 {{ $order->status == 1 ? 'bg-info' : ($order->status == 3 ? 'bg-danger' : 'bg-warning') }} text-white">
                                    @php
                                        $statusText = [1 => 'ارسال شده', 2 => 'در انتظار ارسال', 3 => 'کنسل شده'][$order->status] ?? 'نامشخص';
                                    @endphp
                                    <h4 class="fw-bold mb-2">{{ $statusText }}</h4>
                                    @if($order->status == 1 && $order->send_at)
                                        <p class="mb-0">
                                            تاریخ ارسال:
                                            <span dir="ltr">
                                            {{ \Morilog\Jalali\Jalalian::forge($order->send_at)->format('Y/m/d') }}
                                        </span>
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <strong>روش ارسال:</strong> {{ $order->send_method->name }}
                                    <small class="text-muted d-block">{{ $order->send_method->description }}</small>
                                </div>
                                <div class="mt-4">
                                    <strong>کد رهگیری مرسوله:</strong> {{ $order->postal_track }}

                                </div>
                                <div class="mt-4">
                                    <strong>آدرس:</strong>
                                    <small class="text-muted d-block">{{ $order->state }},{{ $order->city }}, {{ $order->address }},{{ $order->postal_code }}</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- محصولات سفارش -->
                        <h5 class="fw-bold mb-3 text-primary">محصولات سفارش شده</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                <tr>
                                    <th>محصول</th>
                                    <th>رنگ</th>
                                    <th>سایز</th>
                                    <th>تعداد</th>
                                    <th>قیمت واحد</th>
                                    <th>جمع</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product_color->name ?? '-' }}</td>
                                        <td>{{ $item->product_size->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price - $item->discount) }} تومان</td>
                                        <td>{{ number_format(($item->price - $item->discount) * $item->quantity) }} تومان</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- خلاصه مالی -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="bg-light rounded p-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>مبلغ کل کالا:</strong>
                                        <span>{{ number_format($order->total_amount) }} تومان</span>
                                    </div>
                                    @if($order->total_amount > $order->pay_amount)
                                        <div class="d-flex justify-content-between text-danger mb-2">
                                            <strong>تخفیف اعمال شده:</strong>
                                            <span>{{ number_format($order->total_amount - $order->pay_amount) }} تومان</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between fs-5 fw-bold text-success">
                                        <strong>مبلغ پرداختی:</strong>
                                        <span>{{ number_format($order->pay_amount) }} تومان</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('order.track.form') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-arrow-left"></i> پیگیری سفارش دیگر
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
