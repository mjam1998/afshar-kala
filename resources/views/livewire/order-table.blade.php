<div>
    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- فیلترها و جستجو --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                {{-- جستجو --}}
                <div class="col-md-4">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <input type="text" wire:model.live="searchInput"
                               class="form-control"
                               placeholder="کد پیگیری، نام، موبایل...">
                        <button wire:click="applySearch" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                {{-- فیلتر وضعیت ارسال --}}
                <div class="col-md-3">
                    <label class="form-label">وضعیت ارسال</label>
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="">همه</option>
                        <option value="1">ارسال شده</option>
                        <option value="2">در انتظار</option>
                        <option value="3">کنسل شده</option>
                    </select>
                </div>

                {{-- فیلتر وضعیت پرداخت --}}
                <div class="col-md-3">
                    <label class="form-label">وضعیت پرداخت</label>
                    <select wire:model.live="filterPayment" class="form-select">
                        <option value="">همه</option>
                        <option value="1">پرداخت شده</option>
                        <option value="0">پرداخت نشده</option>
                    </select>
                </div>

                {{-- ریست فیلترها --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="$set('search', ''); $set('filterStatus', ''); $set('filterPayment', '')"
                            class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> ریست
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead >
            <tr style="text-align: center">
                <th style="cursor:pointer" wire:click="sortBy('track_number')">
                    کد پیگیری سفارش
                    @if($sortField === 'track_number')
                        <i class="bi bi-arrow-{{ $sortAsc ? 'up' : 'down' }}"></i>
                    @endif
                </th>
                <th>وضعیت پرداخت</th>
                <th>وضعیت ارسال</th>
                <th style="cursor:pointer" wire:click="sortBy('paid_at')">
                    تاریخ پرداخت
                    @if($sortField === 'paid_at')
                        <i class="bi bi-arrow-{{ $sortAsc ? 'up' : 'down' }}"></i>
                    @endif
                </th>
                <th style="cursor:pointer" wire:click="sortBy('total_amount')">
                    مبلغ کل
                    @if($sortField === 'total_amount')
                        <i class="bi bi-arrow-{{ $sortAsc ? 'up' : 'down' }}"></i>
                    @endif
                </th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="text-center"><strong>{{ $order->track_number }}</strong></td>
                    <td class="text-center">
                            <span class="badge {{ $order->is_paid ? 'bg-success' : 'bg-danger' }}">
                                {{ $order->is_paid ? 'پرداخت شده' : 'پرداخت نشده' }}
                            </span>
                    </td>
                    <td class="text-center">
                        @php
                            $statusText = [1 => 'ارسال شده', 2 => 'در انتظار', 3 => 'کنسل شده'][$order->status] ?? 'نامشخص';
                            $statusColor = [1 => 'bg-info', 2 => 'bg-warning', 3 => 'bg-danger'][$order->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusColor }}">{{ $statusText }}</span>
                    </td>
                    <td class="text-center" dir="ltr">
                        {{ $order->paid_at ? \Morilog\Jalali\Jalalian::forge($order->paid_at)->format('Y/m/d H:i') : '-' }}
                    </td>
                    <td class="text-center">{{ number_format($order->total_amount) }} تومان</td>
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                            <i class="bi bi-eye"></i> جزئیات
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        هیچ سفارشی یافت نشد
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>

    {{-- مودال‌ها (همان کد قبلی) --}}
    @foreach($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">جزئیات سفارش #{{ $order->id }} - کد پیگیری: {{ $order->track_number }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- اطلاعات مشتری -->
                        <div class="row mb-4">
                            <div class="col-md-6"><strong>نام:</strong> {{ $order->name }}</div>
                            <div class="col-md-6"><strong>موبایل:</strong> {{ $order->mobile }}</div>
                            <div class="col-md-6"><strong>شماره پیگیری درگاه بانکی:</strong> {{ $order->trans_id }}</div>
                            <div class="col-md-6"><strong>وضعیت پرداخت:</strong>
                                @if($order->is_paid == 1)
                                    پرداخت شده {{    \Morilog\Jalali\Jalalian::forge($order->paid_at)->format('Y/m/d h:i' )  }}
                                @endif
                                @if($order->is_paid == 0)
                                    پرداخت نشده
                                @endif

                            </div>
                            <div class="col-md-6"><strong>کد پستی:</strong> {{ $order->postal_code }}</div>
                            <div class="col-md-6"><strong>وضعیت ارسال:</strong>
                                @if($order->status ==1)
                                    ارسال شده {{    \Morilog\Jalali\Jalalian::forge($order->send_at)->format('Y/m/d')  }}

                                @else
                                    ارسال نشده
                                @endif


                            </div>

                            <div class="col-12 mt-2"><strong>آدرس:</strong> {{ $order->state }}، {{ $order->city }}، {{ $order->address }}</div>
                            <div class="col-12 mt-2"><strong>روش ارسال:</strong> {{ $order->send_method->name }}، {{$order->send_method->description }}</div>
                            <div class="col-md-12"><strong>کد رهگیری مرسوله(پست یا تیپاکس یا...):</strong> {{ $order->postal_track }}</div>
                            @if($order->offer_id)
                                <div class="col-md-6"><strong>کد تخفیف:</strong> {{ $order->offer?->code }}</div>
                                <div class="col-md-6"><strong>مبلغ کد تخفیف:</strong> {{ number_format($order->offer?->discount_amount)  }} تومان </div>
                            @endif

                        </div>

                        <!-- محصولات -->
                        <h6 class="fw-bold mb-3">محصولات سفارش</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr >
                                    <th >محصول</th>
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

                        <!-- مالی -->
                        <div class="row text-lg mb-4">
                            <div class="col-md-6"><strong>مبلغ کل کالا:</strong> {{ number_format($order->total_amount) }} تومان</div>
                            <div class="col-md-6"><strong>مبلغ پرداختی:</strong> <span class="text-success fw-bold">{{ number_format($order->pay_amount) }} تومان</span></div>
                            @if($order->total_amount > $order->pay_amount)
                                <div class="col-12 text-danger mt-2"><strong>تخفیف اعمال شده:</strong> {{ number_format($order->total_amount - $order->pay_amount) }} تومان</div>
                            @endif
                        </div>

                        <hr>

                        <!-- تغییر وضعیت ارسال -->
                        <form action="{{ route('admin.order.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">تعیین وضعیت ارسال</label>
                                    <select name="status" class="form-select" id="statusSelect{{ $order->id }}" onchange="toggleSendDate({{ $order->id }})">
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>در انتظار ارسال</option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>ارسال شده</option>
                                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>کنسل شده</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="sendDateContainer{{ $order->id }}" style="display: {{ $order->status == 1 ? 'block' : 'none' }};">
                                    <label class="form-label fw-bold">تعیین تاریخ ارسال  </label>
                                    <input type="text" name="send_at" class="form-control persian-datepicker" >
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">تعیین کد رهگیری مرسوله:</label>
                                    <input class="form-control" name="postal_track" type="text">
                                </div>
                            </div>
                            <div class=" text-end" style="margin-top: 180px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg"></i> بروزرسانی وضعیت
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

