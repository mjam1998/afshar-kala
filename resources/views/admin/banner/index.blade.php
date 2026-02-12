@extends('admin.layout.master')

@section('title')
    مدیریت صفحه اصلی سایت
@endsection

@section('content')
    <div class="container mt-5">

        <h2 class="mb-4">مدیریت بنرهای صفحه اصلی</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>خطا در اطلاعات وارد شده:</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ==================== بخش اسلایدر هیرو ==================== --}}
        <div class="card mb-5 border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i>
                    مدیریت اسلایدر بنر اصلی (Hero Slider)
                </h5>
                <span class="badge bg-white text-primary">حداکثر ۴ اسلاید</span>
            </div>
            <div class="card-body">

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    اسلایدها بر اساس شماره <strong>sort</strong> مرتب می‌شوند. تصاویر با نسبت <strong>16:9</strong> یا <strong>عرض کامل</strong> مناسب‌تر هستند.
                </div>

                <div class="row g-4">
                    @foreach($heroBanners as $heroBanner)
                        <div class="col-md-6">
                            <div class="card h-100 {{ $heroBanner->photo ? 'border-success' : 'border-warning' }}">
                                <div class="card-header d-flex justify-content-between align-items-center
                            {{ $heroBanner->photo ? 'bg-success text-white' : 'bg-warning' }}">
                            <span>
                                <i class="fas fa-image me-1"></i>
                                اسلاید شماره {{ $heroBanner->sort }}
                            </span>
                                    @if($heroBanner->photo)
                                        <span class="badge bg-white text-success">فعال</span>
                                    @else
                                        <span class="badge bg-dark">بدون تصویر</span>
                                    @endif
                                </div>

                                <div class="card-body">
                                    {{-- پیش‌نمایش تصویر فعلی --}}
                                    <div class="mb-3 text-center" style="height: 180px; background:#f8f9fa; border-radius:8px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                        @if($heroBanner->photo)
                                            <img src="{{ asset('video/' . $heroBanner->photo) }}"
                                                 class="img-fluid"
                                                 style="max-height:180px; width:100%; object-fit:cover;"
                                                 alt="اسلاید {{ $heroBanner->sort }}">
                                        @else
                                            <div class="text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p class="mb-0 small">تصویری آپلود نشده</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- فرم ویرایش --}}
                                    <form action="{{ route('admin.banner.hero.update', $heroBanner) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-upload me-1"></i>
                                                تصویر جدید
                                                <small class="text-muted fw-normal">(jpeg, png, jpg, gif, webp - max 5MB)</small>
                                            </label>
                                            <input type="file"
                                                   name="photo"
                                                   class="form-control"
                                                   accept="image/*"
                                                   onchange="previewImage(this, 'preview-{{ $heroBanner->sort }}')">

                                            {{-- پیش‌نمایش قبل از آپلود --}}
                                            <div id="preview-{{ $heroBanner->sort }}" class="mt-2" style="display:none;">
                                                <img src="" class="img-fluid rounded" style="max-height:120px;">
                                                <small class="text-success d-block mt-1">
                                                    <i class="fas fa-check"></i> پیش‌نمایش تصویر انتخابی
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                متن Alt تصویر
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                   name="photo_alt"
                                                   class="form-control"
                                                   value="{{ old('photo_alt', $heroBanner->photo_alt) }}"
                                                   placeholder="توضیح تصویر برای سئو"
                                                   required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                لینک اسلاید
                                                <small class="text-muted fw-normal">(اختیاری)</small>
                                            </label>
                                            <input type="url"
                                                   name="link"
                                                   class="form-control"
                                                   value="{{ old('link', $heroBanner->link) }}"
                                                   placeholder="https://example.com/page">
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save me-1"></i>
                                            ذخیره اسلاید {{ $heroBanner->sort }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- ==================== بنر اصلی (متا، عنوان و ...) ==================== --}}
        <div class="card mb-5">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    تنظیمات صفحه اصلی (عنوان، متا و دکمه)
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banner.video.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">










                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">متا دسکریپشن صفحه اصلی</label>
                                <textarea name="meta_description" class="form-control" required>{{ old('meta_description', $videoBanner?->meta_description) }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">تایتل صفحه اصلی</label>
                                <input type="text" name="page_title" class="form-control"
                                       value="{{ old('page_title', $videoBanner?->page_title) }}" required>
                            </div>
                        </div>


                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i>
                        بروزرسانی تنظیمات
                    </button>
                </form>
            </div>
        </div>

        {{-- ==================== بنرهای عکس (photo banners) ==================== --}}
        <div class="card mb-5">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-th-large me-2"></i>
                    ویرایش بنرهای بخش‌های مختلف سایت
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        <li>بنر ۱: بنر اولی و بزرگتر (بین جدیدترین‌ها و پیشنهاد ویژه)</li>
                        <li>بنر ۲: زیر بنر اصلی - سمت راست</li>
                        <li>بنر ۳: زیر بنر اصلی - وسط</li>
                        <li>بنر ۴: زیر بنر اصلی - سمت چپ</li>
                        <li>بنر ۵: آخرین بنر بزرگ</li>
                    </ul>
                </div>

                <div class="row">
                    @foreach($photoBanners as $banner)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light fw-bold">
                                    <i class="fas fa-image me-1"></i>
                                    بنر شماره {{ $banner->id }}
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3" style="height:150px; background:#f8f9fa; border-radius:8px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                        @if($banner->photo)
                                            <img src="{{ asset('banner/' . $banner->photo) }}"
                                                 class="img-fluid"
                                                 style="max-height:150px; width:100%; object-fit:cover;">
                                        @else
                                            <span class="text-muted small">عکسی آپلود نشده</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.banner.photo.update', $banner) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group mb-3">
                                            <label class="form-label">عنوان بنر</label>
                                            <input type="text" name="title" class="form-control"
                                                   value="{{ old('title', $banner->title) }}" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">متن بنر</label>
                                            <textarea name="description" class="form-control" rows="2" required>{{ old('description', $banner->description) }}</textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">alt عکس</label>
                                            <input type="text" name="photo_alt" class="form-control"
                                                   value="{{ old('photo_alt', $banner->photo_alt) }}" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">لینک بنر <small class="text-muted">(اختیاری)</small></label>
                                            <input type="url" name="link" class="form-control"
                                                   value="{{ old('link', $banner->link) }}"
                                                   placeholder="https://example.com">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">عکس جدید <small class="text-muted">(اختیاری)</small></label>
                                            <input type="file" name="photo" class="form-control"
                                                   accept="image/*"
                                                   onchange="previewImage(this, 'preview-photo-{{ $banner->id }}')">
                                            <div id="preview-photo-{{ $banner->id }}" class="mt-2" style="display:none;">
                                                <img src="" class="img-fluid rounded" style="max-height:100px;">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-save me-1"></i>
                                            بروزرسانی
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- JavaScript پیش‌نمایش تصویر --}}
    <script>
        function previewImage(input, previewId) {
            const previewDiv = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.style.display = 'block';
                    previewDiv.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewDiv.style.display = 'none';
            }
        }
    </script>
@endsection
