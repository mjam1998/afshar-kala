@extends('front.layout.master')


@section('content')
    <div style="margin-top: 100px;">
        <div class="container py-5 my-5" >
            <h2 class="text-center mb-5 fw-bold">سبد خرید شما</h2>

            @if(empty($cart))
                <div class="text-center py-5">
                    <i class="bi bi-bag display-1 text-muted mb-4"></i>
                    <h4 class="text-muted">سبد خرید شما خالی است</h4>
                    <p class="text-muted mb-4">محصولات مورد علاقه خود را به سبد خرید اضافه کنید.</p>
                    <a href="{{ route('home.index') }}" class="btn btn-dark btn-lg">برگشت به فروشگاه</a>
                </div>
            @else
                <div class="row">
                    <!-- لیست آیتم‌های سبد -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="text-center py-3">تصویر</th>
                                            <th class="py-3">محصول</th>
                                            <th class="text-center py-3">رنگ / سایز</th>
                                            <th class="text-center py-3">قیمت واحد</th>
                                            <th class="text-center py-3">تعداد</th>
                                            <th class="text-center py-3">جمع</th>
                                            <th class="text-center py-3">حذف</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cart as $key => $item)
                                            <tr class="align-middle border-bottom">
                                                <td class="text-center">
                                                    @if($item['image'])
                                                        <img src="{{ asset('product/' . $item['image']) }}"
                                                             alt="{{ $item['product_name'] }}"
                                                             class="rounded"
                                                             style="width: 80px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                             style="width: 80px; height: 80px;">
                                                            <i class="bi bi-image text-muted" style="font-size: 30px;"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <h6 class="mb-1">{{ $item['product_name'] }}</h6>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small">
                                                    <span class="d-block">
                                                        رنگ: <span class="fw-bold">{{ $item['color_name'] }}</span>
                                                        <span class="d-inline-block rounded"
                                                              style="width: 16px; height: 16px; background-color: {{ $item['color_code'] }}; border: 1px solid #ddd;"></span>
                                                    </span>
                                                        <span class="d-block">سایز: <span class="fw-bold">{{ $item['size_name'] }}</span></span>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    {{ number_format($item['price']) }} تومان
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <button class="btn btn-outline-secondary btn-sm quantity-decrease"
                                                                data-key="{{ $key }}" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number"
                                                               class="form-control form-control-sm text-center quantity-input"
                                                               value="{{ $item['quantity'] }}"
                                                               data-key="{{ $key }}"
                                                               min="1"
                                                               style="width: 60px; margin-top: 20px;">
                                                        <button class="btn btn-outline-secondary btn-sm quantity-increase"
                                                                data-key="{{ $key }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-bold">
                                                <span class="item-total-price">
                                                    {{ number_format($item['price'] * $item['quantity']) }}
                                                </span> تومان
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger btn-sm remove-item" data-key="{{ $key }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- خلاصه سبد خرید -->
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                            <div class="card-body">
                                <h5 class="card-title mb-4">خلاصه سبد خرید</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>تعداد کل کالا:</span>
                                    <span class="total-items-count fw-bold">
                                {{ array_sum(array_column($cart, 'quantity')) }}
                            </span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fs-5">جمع کل:</span>
                                    <span class="fs-5 fw-bold text-danger total-price">
                                {{ number_format($totalPrice) }} تومان
                            </span>
                                </div>
                                <a href="{{route('checkout.index')}}" class="btn btn-danger btn-lg w-100">
                                   ثبت نهایی سفارش
                                    <i class="bi bi-arrow-left me-2"></i>
                                </a>
                                <div class="text-center mt-3">
                                    <a href="{{ route('home.index') }}" class="text-muted small">
                                        <i class="bi bi-arrow-right"></i> ادامه خرید
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- بخش کد تخفیف -->
                    <div class="col-lg-4 mt-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-3">کد تخفیف</h5>

                                @if(session('applied_offer'))
                                    <!-- نمایش کد اعمال شده -->
                                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <strong>{{ session('applied_offer.code') }}</strong>
                                            <div class="small mt-1">
                                                تخفیف: {{ number_format(session('applied_offer.discount_amount')) }} تومان
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeOfferBtn">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @else
                                    <!-- فرم ورود کد -->
                                    <form id="offerForm">
                                        <div class="input-group">
                                            <input type="text"
                                                   class="form-control"
                                                   id="offerCode"
                                                   placeholder="کد تخفیف را وارد کنید"
                                                   maxlength="20">
                                            <button class="btn btn-danger" type="submit" id="applyOfferBtn">
                                                <i class="bi bi-check2"></i> اعمال
                                            </button>
                                        </div>
                                        <div id="offerMessage" class="mt-2"></div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>

        <!-- Toast برای پیام‌ها -->
        <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3">
            <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <script>
            // اعمال کد تخفیف
            document.getElementById('offerForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const code = document.getElementById('offerCode').value.trim();
                const btn = document.getElementById('applyOfferBtn');
                const messageDiv = document.getElementById('offerMessage');

                if (!code) {
                    messageDiv.innerHTML = '<small class="text-danger">لطفاً کد تخفیف را وارد کنید.</small>';
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال بررسی...';

                fetch('/cart/apply-offer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = `<small class="text-success">${data.message}</small>`;
                            showToast(data.message);

                            // رفرش صفحه برای نمایش تخفیف
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            messageDiv.innerHTML = `<small class="text-danger">${data.message}</small>`;
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-check2"></i> اعمال';
                        }
                    })
                    .catch(error => {
                        messageDiv.innerHTML = '<small class="text-danger">خطا در ارتباط با سرور</small>';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check2"></i> اعمال';
                    });
            });

            // حذف کد تخفیف
            document.getElementById('removeOfferBtn')?.addEventListener('click', function() {
                if (!confirm('آیا از حذف کد تخفیف مطمئن هستید؟')) return;

                fetch('/cart/remove-offer', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            location.reload();
                        }
                    });
            });

            function showToast(message) {
                const toastEl = document.getElementById('cartToast');
                toastEl.querySelector('.toast-body').textContent = message;
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }

            // افزایش تعداد
            document.querySelectorAll('.quantity-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.closest('td').querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                    updateCart(input.dataset.key, input.value);
                });
            });

            // کاهش تعداد
            document.querySelectorAll('.quantity-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.closest('td').querySelector('.quantity-input');
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateCart(input.dataset.key, input.value);
                    }
                });
            });

            // تغییر دستی تعداد
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 1) this.value = 1;
                    updateCart(this.dataset.key, this.value);
                });
            });

            // حذف آیتم
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('آیا مطمئن هستید که می‌خواهید این محصول را از سبد حذف کنید؟')) {
                        removeFromCart(this.dataset.key);
                    }
                });
            });

            function updateCart(key, quantity) {
                fetch("/cart/update", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ key, quantity })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // آپدیت قیمت این آیتم (از سرور می‌گیریم)
                            const row = document.querySelector(`input[data-key="${key}"]`).closest('tr');
                            row.querySelector('.item-total-price').textContent =
                                new Intl.NumberFormat('fa-IR').format(data.itemPrice) + ' تومان';

                            // آپدیت جمع کل
                            document.querySelector('.total-price').textContent =
                                new Intl.NumberFormat('fa-IR').format(data.totalPrice) + ' تومان';

                            // آپدیت تعداد کل کالا
                            document.querySelector('.total-items-count').textContent = data.totalItems;

                            // آپدیت بج سبد خرید در navbar
                            document.querySelectorAll('.badge.bg-danger').forEach(badge => {
                                badge.textContent = data.totalItems;
                                badge.style.display = data.totalItems > 0 ? 'inline' : 'none';
                            });

                            const decreaseBtn = row.querySelector('.quantity-decrease');
                            const currentQuantity = parseInt(quantity);

                            if (currentQuantity <= 1) {
                                decreaseBtn.disabled = true;
                            } else {
                                decreaseBtn.disabled = false;
                            }
                            showToast('سبد خرید بروزرسانی شد');
                        } else {
                            showToast(data.message || 'خطا در بروزرسانی');
                            location.reload(); // اگر موجودی نبود، صفحه رو رفرش کن تا وضعیت درست بشه
                        }
                    });
            }

            function removeFromCart(key) {
                fetch("/cart/remove/" + key, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            showToast(data.message || 'خطا در حذف');
                        }
                    });
            }
        </script>
    </div>

@endsection




