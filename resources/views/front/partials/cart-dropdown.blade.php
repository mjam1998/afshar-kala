@php
    $cart = $cart ?? [];
    $totalItems = array_sum(array_column($cart, 'quantity'));
    $totalPrice = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
@endphp

@if(count($cart) > 0)
    @foreach($cart as $item)
        <li class="dropdown-item p-0 mb-3">
            <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                    @if($item['image'] ?? false)
                        <img src="{{ asset('product/' . $item['image']) }}"
                             class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px;">
                            <i class="bi bi-image text-muted fs-3"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 small">{{ $item['product_name'] ?? 'محصول' }}</h6>
                    <div class="text-muted small">
                        رنگ:
                        <span style="display:inline-block; width:12px; height:12px; background:{{ $item['color_code'] ?? '#000' }}; border-radius:50%; vertical-align:middle;"></span>
                        {{ $item['color_name'] ?? 'نامشخص' }}
                        <br>
                        سایز: {{ $item['size_name'] ?? 'نامشخص' }}
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-2">
                        <span class="text-muted small">تعداد: {{ $item['quantity'] }}</span>
                        <strong class="text-danger">{{ number_format($item['price'] * $item['quantity']) }} تومان</strong>
                    </div>
                </div>
            </div>
        </li>
    @endforeach

    <li><hr class="dropdown-divider"></li>
    <li class="dropdown-item p-0">
        <div class="d-flex justify-content-between mb-3">
            <strong>جمع کل:</strong>
            <strong class="text-danger">{{ number_format($totalPrice) }} تومان</strong>
        </div>
        <a href="{{ route('cart.show') }}" class="btn btn-success w-100">
            مشاهده سبد خرید
        </a>
    </li>
@else
    <li class="dropdown-item text-center text-muted py-4">
        <i class="bi bi-bag fs-1 d-block mb-3"></i>
        سبد خرید شما خالی است
    </li>
@endif
