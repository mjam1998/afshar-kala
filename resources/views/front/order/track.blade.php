@extends('front.layout.master')

@section('page_title', 'پیگیری سفارش')

@section('content')
    <div class="container py-5" style="margin-top: 100px; min-height: 70vh;">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-box-seam-fill text-primary display-1 mb-4"></i>
                        <h2 class="fw-bold mb-4">پیگیری سفارش</h2>
                        <p class="text-muted mb-5">برای مشاهده وضعیت سفارش خود، کد پیگیری را وارد کنید.</p>
                        <form action="{{ route('order.track.result') }}" method="POST" class="mx-auto" style="max-width: 450px;">
                            @csrf
                            <div class="d-flex align-items-stretch shadow-sm rounded-3 overflow-hidden" style="height: 58px;">

                                <input type="text" name="track_number"
                                       class="form-control border-0 fs-5 text-center"
                                       placeholder="کد پیگیری (مثل VEMQEI90M)"
                                       value="{{ old('track_number') }}"
                                       required
                                       dir="ltr"
                                       autocomplete="off"
                                       style="border-radius: 0; flex-grow: 1; outline: none !important; box-shadow: none !important;">

                                <button class="btn btn-primary px-4 d-flex align-items-center justify-content-center"
                                        type="submit"
                                        style="border-radius: 0; min-width: 70px;">
                                    <i class="bi bi-search fs-4"></i>
                                </button>

                            </div>

                            @error('track_number')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </form>

                        <div class="mt-5 text-muted small">
                            <p>کد پیگیری در پیامک تأیید سفارش برای شما ارسال شده است.</p>
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
