<div>
    {{-- جستجو --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                   wire:model.defer="searchInput"
                   wire:keydown.enter="applySearch"
                   class="form-control"
                   placeholder="جستجو بر اساس نام، اسلاگ یا دسته‌بندی">
        </div>
        <div class="col-md-2">
            <button wire:click="applySearch" class="btn btn-primary">
                جستجو
            </button>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th class="text-center">اسلاگ</th>
                <th class="text-center">نام</th>
                <th class="text-center">دسته‌بندی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>

            <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="text-center">{{ $product->slug }}</td>
                    <td class="text-center">{{ $product->name }}</td>
                    <td class="text-center">
                        {{ $product->category?->name ?? '-' }}
                    </td>
                    <td style="text-align: center">
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                onclick="fillEditModal({{ $product }})">
                            <i class="bi bi-pencil-square"></i> ویرایش
                        </button>
                        <a class="btn btn-danger btn-sm" href="javascript:void(0)"
                           onclick="openInventoryModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                            <i class="bi bi-box-seam"></i> موجودی
                        </a>
                        <a class="btn btn-info btn-sm" href="javascript:void(0)"
                           onclick="openColorsModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                            <i class="bi bi-palette"></i> رنگها
                        </a>
                        <a class="btn btn-success btn-sm" href="javascript:void(0)"
                           onclick="openPhotosModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->slug }}')">
                            <i class="bi bi-images"></i> عکسها
                        </a>
                        <a class="btn btn-secondary btn-sm" href="javascript:void(0)"
                           onclick="openSizesModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                            <i class="bi bi-rulers"></i> سایزها
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.product.comments', $product->id) }}">
                            <i class="bi bi-chat-dots"></i> کامنتها
                        </a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        محصولی یافت نشد
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
