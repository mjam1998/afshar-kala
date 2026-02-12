@extends('admin.layout.master')

@section('title')
    مدیریت محصولات
@endsection

@section('content')
    <div class="profile-content ">
        <div class="profile-section active" >


            <h3 class="section-title"><i class="bi bi-info-circle-fill"></i> مدیریت محصولات</h3>
            @if(session()->has('productMessage'))
                <p class="text text-success">{{session('productMessage')}}</p>
            @endif
            <div class="mt-4">
                <a href="{{route('admin.product.add.view')}}" class="btn btn-success">افزودن محصول</a>
            </div>

            <div class="table-container  " style="margin-top: 50px;">
                <livewire:product-table />
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">ویرایش محصول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="row g-3">
                            <!-- نام و اسلاگ -->
                            <div class="col-md-4">
                                <label>نام محصول</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>اسلاگ</label>
                                <input type="text" name="slug" id="edit_slug" class="form-control" required>
                            </div>
                            <div class="col-md-4">

                                <label  class="control-label"> پیشنهاد ویژه</label>
                                <select name="is_special" class="form-control mt-2" id="edit_is_special" >
                                    <option value="0">خیر</option>
                                    <option value="1">بله</option>
                                </select>



                            </div>
                            <!-- قیمت و تخفیف -->
                            <div class="col-md-6">
                                <label>قیمت (تومان)</label>
                                <input type="text" class="form-control moneyDisplay" id="edit_price_display">
                                <input type="hidden" class="moneyValue" name="price" id="edit_price">
                            </div>
                            <div class="col-md-6">
                                <label>میزان تخفیف (تومان) - اختیاری</label>
                                <input type="text" class="form-control moneyDisplay" id="edit_discount_display">
                                <input type="hidden" class="moneyValue" name="discount" id="edit_discount">
                            </div>

                            <!-- دسته‌بندی و تعداد موجودی -->
                            <div class="col-md-4">
                                <label>دسته‌بندی</label>
                                <select class="form-control" name="category_id" id="edit_category_id">
                                    <option value="0">دسته بندی را انتخاب کنید...</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- جنس، وزن، ابعاد -->
                            <div class="col-md-4">
                                <label>جنس محصول</label>
                                <input type="text" name="material" id="edit_material" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>وزن</label>
                                <input type="text" name="weight" id="edit_weight" class="form-control" placeholder="مثلا 2.5 گرم" required>
                            </div>
                            <div class="col-md-4">
                                <label>ابعاد</label>
                                <input type="text" name="dimension" id="edit_dimension" class="form-control" placeholder="مثلا 20*12*14 سانتی متر" required>
                            </div>

                            <!-- متا و عنوان صفحه -->
                            <div class="col-md-4">
                                <label>Meta Description</label>
                                <input type="text" name="meta_description" id="edit_meta_description" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Page Title</label>
                                <input type="text" name="page_title" id="edit_page_title" class="form-control" required>
                            </div>
                            <div>
                                <label>توضیحات محصول</label>
                                <textarea name="description" id="edit_description" class="form-control" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal مدیریت رنگ‌های محصول -->
    <div class="modal fade" id="colorsModal" tabindex="-1" aria-labelledby="colorsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="colorsModalLabel">مدیریت رنگ‌های محصول: <span id="colorProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- فرم اضافه کردن رنگ جدید -->
                    <form id="addColorForm" class="mb-4">
                        <input type="hidden" id="color_product_id" name="product_id">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">نام رنگ</label>
                                <input type="text" class="form-control" id="color_name" required placeholder="مثلاً قرمز">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">کد رنگ (Hex)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="color_code" required placeholder="#ff0000">
                                    <input type="color" class="form-control form-control-color" id="color_picker" style="width:60px;">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">افزودن رنگ</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- جدول رنگ‌های موجود -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="colorsTable">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 80px; text-align:center;">رنگ</th>
                                <th>نام رنگ</th>
                                <th style="width: 100px; text-align:center;">کد Hex</th>
                                <th style="width: 80px; text-align:center;">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- رنگ‌ها به صورت داینامیک اینجا پر می‌شوند -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal مدیریت سایزهای محصول -->
    <div class="modal fade" id="sizesModal" tabindex="-1" aria-labelledby="sizesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="sizesModalLabel">مدیریت سایزهای محصول: <span id="sizeProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- فرم اضافه کردن سایز جدید -->
                    <form id="addSizeForm" class="mb-4">
                        <input type="hidden" id="size_product_id" name="product_id">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label">نام سایز</label>
                                <input type="text" class="form-control" id="size_name" required placeholder="مثلاً Large، ۴۰، آزاد، XL">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">افزودن سایز</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- جدول سایزهای موجود -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="sizesTable">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 80px; text-align:center;">#</th>
                                <th>نام سایز</th>
                                <th style="width: 100px; text-align:center;">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- سایزها به صورت داینامیک اینجا پر می‌شوند -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal مدیریت عکس‌های محصول -->
    <div class="modal fade" id="photosModal" tabindex="-1" aria-labelledby="photosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="photosModalLabel">مدیریت عکس‌های محصول: <span id="photoProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>نکته مهم:</strong> عکس اول در این لیست، به عنوان <strong>عکس اصلی (پیش‌نمایش)</strong> محصول در صفحه فروشگاه نمایش داده می‌شود.
                    </div>

                    <!-- فرم آپلود عکس جدید -->
                    <form id="addPhotoForm" class="mb-5">
                        <input type="hidden" id="photo_product_id" name="product_id">
                        <input type="hidden" id="photo_slug" name="slug">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label">انتخاب عکس</label>
                                <input type="file" class="form-control" name="photo" id="photo_input" accept="image/*" required>
                                <small class="text-muted">فرمت‌های مجاز: jpg, jpeg, png, gif, webp</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">متن جایگزین (Alt) - برای سئو</label>
                                <input type="text" class="form-control" name="photo_alt" placeholder="مثلاً تی‌شرت مشکی مردانه مدل 2025" required>
                            </div>
                            <div class="col-md-2 ">
                                <button type="submit" class="btn btn-success w-100">آپلود عکس</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- لیست عکس‌های موجود -->
                    <div class="row" id="photosList">
                        <!-- عکس‌ها به صورت داینامیک اینجا اضافه می‌شن -->
                    </div>

                    <div id="noPhotosMessage" class="text-center text-muted mt-4" style="display:none;">
                        عکسی برای این محصول آپلود نشده است.
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal مدیریت موجودی محصول (Variants) -->
    <div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="inventoryModalLabel">مدیریت موجودی محصول: <span id="inventoryProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- فرم افزودن/به‌روزرسانی موجودی -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <strong>افزودن یا افزایش موجودی</strong>
                        </div>
                        <div class="card-body">
                            <form id="addInventoryForm">
                                <input type="hidden" id="inventory_product_id" name="product_id">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">رنگ</label>
                                        <select class="form-control" id="inventory_color_id" name="color_id" required>
                                            <option value="">رنگ را انتخاب کنید...</option>
                                            <!-- با JS پر میشه -->
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">سایز</label>
                                        <select class="form-control" id="inventory_size_id" name="size_id" required>
                                            <option value="">سایز را انتخاب کنید...</option>
                                            <!-- با JS پر میشه -->
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">تعداد موجودی</label>
                                        <input type="number" class="form-control" name="count" min="0" value="1" required>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success w-100">افزودن</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <!-- جدول موجودی‌های فعلی -->
                    <h5>موجودی‌های فعلی</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="inventoryTable">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">رنگ</th>
                                <th style="width: 100px;">سایز</th>
                                <th style="width: 150px;">موجودی فعلی</th>
                                <th style="width: 100px;">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- با JS پر میشه -->
                            </tbody>
                        </table>
                    </div>

                    <div id="noInventoryMessage" class="text-center text-muted mt-4" style="display:none;">
                        هیچ ترکیبی از رنگ و سایز برای این محصول ثبت نشده است.
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"> </script>

    <script>
        let mainEditor = null;
        let modalEditor = null;

        function formatNumber(num) {
            if (!num) return '';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // فرمت قیمت و تخفیف + ادیتور فرم ایجاد
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.moneyDisplay').forEach(function(displayInput) {
                let hiddenInput = displayInput.parentElement.querySelector('.moneyValue');

                displayInput.addEventListener('input', function() {
                    let value = this.value.replace(/[^\d]/g, '');
                    if (hiddenInput) hiddenInput.value = value || '';
                    this.value = formatNumber(value);
                });

                if (displayInput.value) {
                    let clean = displayInput.value.replace(/[^\d]/g, '');
                    displayInput.value = formatNumber(clean);
                    if (hiddenInput) hiddenInput.value = clean;
                }
            });

            // ادیتور فرم ایجاد محصول
            const mainTextarea = document.querySelector('textarea[name="description"]');
            if (mainTextarea) {
                ClassicEditor
                    .create(mainTextarea, {
                        language: 'fa',
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                    })
                    .then(editor => mainEditor = editor)
                    .catch(error => console.error(error));
            }
        });

        // پر کردن مودال — فقط فیلدهای معمولی رو پر کن، توضیحات رو دست نزن
        function fillEditModal(product) {
            document.getElementById('editForm').action = `/admin/product/edit/${product.id}`;

            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_slug').value = product.slug;
            document.getElementById('edit_is_special').value = product.is_special;

            let price = product.price || 0;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_price_display').value = formatNumber(price);

            let discount = product.discount || 0;
            document.getElementById('edit_discount').value = discount;
            document.getElementById('edit_discount_display').value = formatNumber(discount);

            document.getElementById('edit_category_id').value = product.category_id;

            document.getElementById('edit_material').value = product.material;
            document.getElementById('edit_weight').value = product.weight;
            document.getElementById('edit_dimension').value = product.dimension;
            document.getElementById('edit_meta_description').value = product.meta_description;
            document.getElementById('edit_page_title').value = product.page_title;

            // فقط محتوای توضیحات رو در متغیر ذخیره کن — به textarea دست نزن!
            window.currentProductDescription = product.description || '';
        }

        const editModal = document.getElementById('editModal');

        editModal.addEventListener('shown.bs.modal', function () {
            const textarea = document.getElementById('edit_description');
            if (!textarea) return;

            // اگر ادیتور وجود نداره، بساز
            if (!modalEditor) {
                ClassicEditor
                    .create(textarea, {
                        language: 'fa',
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                    })
                    .then(editor => {
                        modalEditor = editor;
                        editor.setData(window.currentProductDescription || '');
                    })
                    .catch(error => console.error('CKEditor error:', error));
            } else {
                // اگر وجود داره، فقط محتوا رو عوض کن
                modalEditor.setData(window.currentProductDescription || '');
            }
        });
    </script>
    <script>
        // همگام‌سازی color picker با input متن
        document.getElementById('color_picker').addEventListener('input', function () {
            document.getElementById('color_code').value = this.value.toUpperCase();
        });

        document.getElementById('color_code').addEventListener('input', function () {
            let val = this.value;
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                document.getElementById('color_picker').value = val;
            }
        });

        // باز کردن مودال رنگ‌ها و لود رنگ‌های محصول
        function openColorsModal(productId, productName) {
            debugger;
            // تنظیم عنوان مودال
            document.getElementById('colorProductName').textContent = productName;
            document.getElementById('color_product_id').value = productId;

            // پاک کردن فرم
            document.getElementById('addColorForm').reset();
            document.getElementById('color_picker').value = '#000000';
            document.getElementById('color_code').value = '#000000';

            // لود رنگ‌های محصول از سرور
            fetch(`/admin/product/${productId}/colors`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#colorsTable tbody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">رنگی برای این محصول ثبت نشده است.</td></tr>';
                        return;
                    }

                    data.forEach(color => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td class="text-center">
                            <div style="width:30px;height:30px;border-radius:50%;background-color:${color.code};border:1px solid #ccc;margin:0 auto;"></div>
                        </td>
                        <td>${color.name}</td>
                        <td class="text-center" dir="ltr">${color.code}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteColor(${color.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    alert('خطا در دریافت رنگ‌ها');
                    console.error(err);
                });

            // نمایش مودال
            new bootstrap.Modal(document.getElementById('colorsModal')).show();
        }

        // افزودن رنگ جدید
        document.getElementById('addColorForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const productId = document.getElementById('color_product_id').value;
            const name = document.getElementById('color_name').value.trim();
            const code = document.getElementById('color_code').value.trim().toUpperCase();

            if (!name || !/^#[0-9A-F]{6}$/i.test(code)) {
                alert('لطفاً نام رنگ و کد Hex معتبر وارد کنید.');
                return;
            }

            fetch(`/admin/product/color/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    name: name,
                    code: code
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // اضافه کردن ردیف جدید به جدول بدون رفرش
                        const tbody = document.querySelector('#colorsTable tbody');
                        const existingEmpty = tbody.querySelector('td[colspan]');
                        if (existingEmpty) tbody.innerHTML = '';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                    <td class="text-center">
                        <div style="width:30px;height:30px;border-radius:50%;background-color:${code};border:1px solid #ccc;margin:0 auto;"></div>
                    </td>
                    <td>${name}</td>
                    <td class="text-center" dir="ltr">${code}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteColor(${data.color.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                        tbody.appendChild(tr);

                        // پاک کردن فرم
                        this.reset();
                        document.getElementById('color_picker').value = '#000000';
                        document.getElementById('color_code').value = '#000000';
                    } else {
                        alert(data.message || 'خطا در افزودن رنگ');
                    }
                })
                .catch(err => {
                    alert('خطا در ارتباط با سرور');
                    console.error(err);
                });
        });

        // حذف رنگ
        function deleteColor(colorId) {
            if (!confirm('آیا از حذف این رنگ مطمئن هستید؟')) return;

            fetch(`/admin/product/color/${colorId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // حذف ردیف از جدول
                        document.querySelector(`button[onclick="deleteColor(${colorId})"]`).closest('tr').remove();

                        // اگر جدول خالی شد، پیام نمایش بده
                        if (document.querySelector('#colorsTable tbody').children.length === 0) {
                            document.querySelector('#colorsTable tbody').innerHTML = '<tr><td colspan="4" class="text-center text-muted">رنگی برای این محصول ثبت نشده است.</td></tr>';
                        }
                    } else {
                        alert(data.message || 'خطا در حذف رنگ');
                    }
                });
        }
    </script>
    <script>
        // باز کردن مودال سایزها و لود سایزهای محصول
        function openSizesModal(productId, productName) {
            // تنظیم عنوان مودال
            document.getElementById('sizeProductName').textContent = productName;
            document.getElementById('size_product_id').value = productId;

            // پاک کردن فرم
            document.getElementById('addSizeForm').reset();

            // لود سایزهای محصول از سرور
            fetch(`/admin/product/${productId}/sizes`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#sizesTable tbody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">سایزی برای این محصول ثبت نشده است.</td></tr>';
                        return;
                    }

                    data.forEach((size, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td class="text-center">${index + 1}</td>
                        <td>${size.name}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteSize(${size.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    alert('خطا در دریافت سایزها');
                    console.error(err);
                });

            // نمایش مودال
            new bootstrap.Modal(document.getElementById('sizesModal')).show();
        }

        // افزودن سایز جدید
        document.getElementById('addSizeForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const productId = document.getElementById('size_product_id').value;
            const name = document.getElementById('size_name').value.trim();

            if (!name) {
                alert('لطفاً نام سایز را وارد کنید.');
                return;
            }

            fetch(`/admin/product/size/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    name: name
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // اضافه کردن ردیف جدید به جدول
                        const tbody = document.querySelector('#sizesTable tbody');
                        const existingEmpty = tbody.querySelector('td[colspan]');
                        if (existingEmpty) tbody.innerHTML = '';

                        const index = tbody.children.length + 1;
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                    <td class="text-center">${index}</td>
                    <td>${name}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteSize(${data.size.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                        tbody.appendChild(tr);

                        // پاک کردن فرم
                        this.reset();
                    } else {
                        alert(data.message || 'خطا در افزودن سایز');
                    }
                })
                .catch(err => {
                    alert('خطا در ارتباط با سرور');
                    console.error(err);
                });
        });

        // حذف سایز
        function deleteSize(sizeId) {
            if (!confirm('آیا از حذف این سایز مطمئن هستید؟')) return;

            fetch(`/admin/product/size/${sizeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // حذف ردیف از جدول
                        document.querySelector(`button[onclick="deleteSize(${sizeId})"]`).closest('tr').remove();

                        // به‌روزرسانی شماره ردیف‌ها
                        document.querySelectorAll('#sizesTable tbody tr').forEach((tr, index) => {
                            tr.cells[0].textContent = index + 1;
                        });

                        // اگر جدول خالی شد
                        if (document.querySelector('#sizesTable tbody').children.length === 0) {
                            document.querySelector('#sizesTable tbody').innerHTML = '<tr><td colspan="3" class="text-center text-muted">سایزی برای این محصول ثبت نشده است.</td></tr>';
                        }
                    } else {
                        alert(data.message || 'خطا در حذف سایز');
                    }
                });
        }
    </script>
    <script>
        // باز کردن مودال و لود عکس‌ها
        function openPhotosModal(productId, productName, productSlug) {
            document.getElementById('photoProductName').textContent = productName;
            document.getElementById('photo_product_id').value = productId;
            document.getElementById('photo_slug').value = productSlug;

            // پاک کردن لیست قبلی
            document.getElementById('photosList').innerHTML = '';
            document.getElementById('noPhotosMessage').style.display = 'none';

            // لود عکس‌ها از سرور
            fetch(`/admin/product/${productId}/photos`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('photosList');
                    const noMsg = document.getElementById('noPhotosMessage');

                    if (data.length === 0) {
                        noMsg.style.display = 'block';
                        return;
                    }

                    data.forEach((photo, index) => {
                        const isMain = index === 0 ? '<span class="badge bg-primary ms-2">عکس اصلی</span>' : '';

                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-4';
                        col.innerHTML = `
                        <div class="card border ${index === 0 ? 'border-primary' : ''}">
                            <img src="/product/${photo.photo}" class="card-img-top" alt="${photo.photo_alt}" style="height:200px; object-fit:cover;">
                            <div class="card-body text-center">
                                <p class="card-text small">${photo.photo_alt} ${isMain}</p>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deletePhoto(${photo.id}, '${photo.photo}')">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    `;
                        container.appendChild(col);
                    });
                })
                .catch(err => {
                    alert('خطا در دریافت عکس‌ها');
                    console.error(err);
                });

            new bootstrap.Modal(document.getElementById('photosModal')).show();
        }

        // آپلود عکس جدید
        document.getElementById('addPhotoForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const productId = document.getElementById('photo_product_id').value;
            const slug = document.getElementById('photo_slug').value;
            const photoFile = document.getElementById('photo_input').files[0];
            const photoAlt = this.querySelector('input[name="photo_alt"]').value.trim();

            if (!photoFile || !photoAlt) {
                alert('لطفاً عکس و متن Alt را وارد کنید.');
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('slug', slug);
            formData.append('photo', photoFile);
            formData.append('photo_alt', photoAlt);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/admin/product/photo/store`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // اضافه کردن عکس جدید به ابتدای لیست (عکس اول = اصلی)
                        const container = document.getElementById('photosList');
                        document.getElementById('noPhotosMessage').style.display = 'none';

                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-4';
                        col.innerHTML = `
                    <div class="card border border-primary">
                        <img src="/product/${data.photo.photo}" class="card-img-top" alt="${data.photo.photo_alt}" style="height:200px; object-fit:cover;">
                        <div class="card-body text-center">
                            <p class="card-text small">${data.photo.photo_alt} <span class="badge bg-primary ms-2">عکس اصلی</span></p>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deletePhoto(${data.photo.id}, '${data.photo.photo}')">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                `;
                        container.insertBefore(col, container.firstChild); // اضافه شدن به اول لیست

                        // پاک کردن فرم
                        this.reset();
                    } else {
                        alert(data.message || 'خطا در آپلود عکس');
                    }
                })
                .catch(err => {
                    alert('خطا در ارتباط با سرور');
                    console.error(err);
                });
        });

        // حذف عکس (از دیتابیس + فایل فیزیکی)
        function deletePhoto(photoId, photoFilename) {
            if (!confirm('آیا از حذف این عکس مطمئن هستید؟ این عمل قابل بازگشت نیست.')) return;

            fetch(`/admin/product/photo/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // حذف کارت عکس از صفحه
                        document.querySelector(`button[onclick="deletePhoto(${photoId}, '${photoFilename}')"]`).closest('.col-md-3').remove();

                        // اگر لیست خالی شد
                        if (document.getElementById('photosList').children.length === 0) {
                            document.getElementById('noPhotosMessage').style.display = 'block';
                        }

                        // به‌روزرسانی badge عکس اصلی برای عکس اول جدید
                        const firstCard = document.querySelector('#photosList .card');
                        if (firstCard) {
                            firstCard.classList.add('border-primary');
                            const badge = firstCard.querySelector('.badge.bg-primary');
                            if (badge) badge.style.display = 'inline';
                            else {
                                const p = firstCard.querySelector('.card-text');
                                p.innerHTML += ' <span class="badge bg-primary ms-2">عکس اصلی</span>';
                            }
                        }
                    } else {
                        alert(data.message || 'خطا در حذف عکس');
                    }
                });
        }
    </script>
    <script>
        let currentProductColors = [];
        let currentProductSizes = [];

        // باز کردن مودال و لود داده‌ها
        function openInventoryModal(productId, productName) {
            document.getElementById('inventoryProductName').textContent = productName;
            document.getElementById('inventory_product_id').value = productId;

            // پاک کردن فرم و جدول
            document.getElementById('addInventoryForm').reset();
            document.getElementById('inventoryTable').querySelector('tbody').innerHTML = '';
            document.getElementById('noInventoryMessage').style.display = 'none';

            // لود همزمان رنگ‌ها، سایزها و موجودی‌ها
            Promise.all([
                fetch(`/admin/product/${productId}/colors`).then(r => r.json()),
                fetch(`/admin/product/${productId}/sizes`).then(r => r.json()),
                fetch(`/admin/product/${productId}/variants`).then(r => r.json())
            ])
                .then(([colors, sizes, variants]) => {
                    currentProductColors = colors;
                    currentProductSizes = sizes;

                    // پر کردن سلکت رنگ
                    const colorSelect = document.getElementById('inventory_color_id');
                    colorSelect.innerHTML = '<option value="">رنگ را انتخاب کنید...</option>';
                    colors.forEach(color => {
                        colorSelect.innerHTML += `<option value="${color.id}">${color.name} (${color.code})</option>`;
                    });

                    // پر کردن سلکت سایز
                    const sizeSelect = document.getElementById('inventory_size_id');
                    sizeSelect.innerHTML = '<option value="">سایز را انتخاب کنید...</option>';
                    sizes.forEach(size => {
                        sizeSelect.innerHTML += `<option value="${size.id}">${size.name}</option>`;
                    });

                    // پر کردن جدول موجودی‌ها
                    const tbody = document.getElementById('inventoryTable').querySelector('tbody');
                    const noMsg = document.getElementById('noInventoryMessage');

                    if (variants.length === 0) {
                        noMsg.style.display = 'block';
                        return;
                    }

                    variants.forEach(variant => {
                        const colorName = colors.find(c => c.id == variant.color_id)?.name || 'نامشخص';
                        const colorCode = colors.find(c => c.id == variant.color_id)?.code || '';
                        const sizeName = sizes.find(s => s.id == variant.size_id)?.name || 'نامشخص';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                    <td>
                        <div style="width:20px;height:20px;border-radius:50%;background:${colorCode};display:inline-block;border:1px solid #ccc;"></div>
                        ${colorName}
                    </td>
                    <td class="text-center">${sizeName}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="${variant.count}" min="0" data-variant-id="${variant.id}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm" onclick="updateVariant(${variant.id})">
                            <i class="bi bi-check-lg"></i> بروزرسانی
                        </button>
                    </td>
                `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    alert('خطا در دریافت اطلاعات موجودی');
                    console.error(err);
                });

            new bootstrap.Modal(document.getElementById('inventoryModal')).show();
        }

        // افزودن یا افزایش موجودی
        document.getElementById('addInventoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const productId = document.getElementById('inventory_product_id').value;
            const colorId = document.getElementById('inventory_color_id').value;
            const sizeId = document.getElementById('inventory_size_id').value;
            const count = parseInt(this.querySelector('input[name="count"]').value);

            if (!colorId || !sizeId || count < 0) {
                alert('لطفاً رنگ، سایز و تعداد معتبر وارد کنید.');
                return;
            }

            fetch(`/admin/product/variant/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    color_id: colorId,
                    size_id: sizeId,
                    count: count
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('موجودی با موفقیت ثبت/به‌روزرسانی شد.');
                        // رفرش جدول (دوباره فراخوانی تابع باز کردن مودال)
                        openInventoryModal(productId, document.getElementById('inventoryProductName').textContent);
                    } else {
                        alert(data.message || 'خطا در ثبت موجودی');
                    }
                });
        });

        // به‌روزرسانی موجودی یک ردیف
        function updateVariant(variantId) {
            const input = document.querySelector(`input[data-variant-id="${variantId}"]`);
            const newCount = parseInt(input.value);

            if (isNaN(newCount) || newCount < 0) {
                alert('تعداد موجودی معتبر نیست.');
                return;
            }

            fetch(`/admin/product/variant/${variantId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ count: newCount })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('موجودی با موفقیت به‌روزرسانی شد.');
                        input.closest('tr').querySelector('button').classList.add('btn-success');
                        setTimeout(() => input.closest('tr').querySelector('button').classList.remove('btn-success'), 2000);
                    } else {
                        alert(data.message || 'خطا در به‌روزرسانی');
                    }
                });
        }
    </script>



@endsection

