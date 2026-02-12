@extends('admin.layout.master')

@section('title')
    کامنت‌های محصول: {{ $product->name }}
@endsection

@section('content')
    <div class="profile-content">
        <div class="profile-section active">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="section-title">
                    <i class="bi bi-chat-dots"></i> کامنت‌های محصول: {{ $product->name }}
                </h3>
                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-right"></i> بازگشت به لیست محصولات
                </a>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- فرم افزودن کامنت جدید -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong>افزودن کامنت جدید</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product.comment.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">نام نظر دهنده</label>
                                <input type="text" name="name" class="form-control" required placeholder="مثلاً علی احمدی">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">متن نظر</label>
                                <textarea name="comment" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">وضعیت</label>
                                <select name="status" class="form-control">
                                    <option value="1">فعال</option>
                                    <option value="2" selected>در انتظار تایید</option>
                                </select>
                            </div>
                            <div class=" col-md-5 ">
                                <label class="form-label"> تاریخ نظر</label>
                                <input class="form-control text-dark" name="created_at" id="persianDate" type="text" required>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="form-label">پاسخ ادمین (اختیاری)</label>
                                <textarea name="admin_response" class="form-control" rows="3" placeholder="پاسخ شما..."></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success">افزودن کامنت</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول کامنت‌ها -->
            <h5>لیست کامنت‌ها ({{ $comments->total() }} نظر)</h5>
            <div class="table-responsive">
                <table id="commentsTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                    <tr >
                        <th style="text-align: center">نام</th>
                        <th style="text-align: center">نظر</th>
                        <th style="text-align: center">پاسخ ادمین</th>
                        <th style="text-align: center">وضعیت</th>
                        <th style="text-align: center">تاریخ</th>
                        <th style="text-align: center">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td>{{ $comment->name }}</td>
                            <td>{{ $comment->comment }}</td>
                            <td>
                                <form action="{{ route('admin.product.comment.response', ['product' => $product->id, 'comment' => $comment->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="admin_response" class="form-control" value="{{ $comment->admin_response }}" placeholder="پاسخ...">
                                        <button type="submit" class="btn btn-outline-primary">ذخیره</button>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">
                                @if($comment->status == 1)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-warning">در انتظار</span>
                                @endif
                            </td>
                            <td class="text-center" dir="ltr">
                                {{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->format('Y/m/d ') }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.product.comment.status', ['product' => $product->id, 'comment' => $comment->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $comment->status == 1 ? 2 : 1 }}">
                                    <button type="submit" class="btn btn-sm {{ $comment->status == 1 ? 'btn-warning' : 'btn-success' }}">
                                        {{ $comment->status == 1 ? 'به انتظار ببر' : 'فعال کن' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.product.comment.destroy', ['product' => $product->id, 'comment' => $comment->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1" onclick="return confirm('آیا از حذف مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- صفحه‌بندی -->
            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#commentsTable').DataTable({
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fa.json" },
                order: [[4, "desc"]],
                pageLength: 15,
                columnDefs: [
                    { targets: [2, 5], orderable: false }
                ]
            });
        });
    </script>
@endsection
