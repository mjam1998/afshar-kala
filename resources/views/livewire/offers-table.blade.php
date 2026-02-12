<div>


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
                               placeholder="کد تخفیف...">
                        <button wire:click="applySearch" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
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
                <th style="cursor:pointer" wire:click="sortBy('code')">
                    کد تخفیف

                </th>
                <th>میزان تخفیف</th>
                <th>تاریخ انقضا</th>

                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($offers as $offer)
                <tr>
                    <td class="text-center"><strong>{{ $offer->code }}</strong></td>
                    <td class="text-center">{{ number_format($offer->discount_amount) }} تومان</td>

                    <td class="text-center" dir="ltr">
                        {{ $offer->expires_at ? \Morilog\Jalali\Jalalian::forge($offer->expires_at)->format('Y/m/d H:i') : '-' }}
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                onclick="fillEditModal({{ $offer }})">
                            <i class="bi bi-pencil-square"></i> ویرایش
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        هیچ کد تخفیفی یافت نشد
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $offers->links() }}
    </div>

</div>

