@extends('admin.layout.master')

@section('title')
    دسته بندی محصولات
@endsection

@section('content')
    <div class="profile-content ">
        <div class="profile-section active" >


            <h3 class="section-title"><i class="bi bi-info-circle-fill"></i> دسته بندی محصولات</h3>
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
            @if(session()->has('categoryAdded'))
                <p class="text text-success">{{session('categoryAdded')}}</p>
            @endif
            <div class="panel-body mt-4">
                <form method="post" action="{{route('admin.category.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >نام دسته بندی</label>
                                <input type="text" class="form-control mt-2" name="name"  required >

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >اسلاگ</label>
                                <input type="text" class="form-control mt-2" name="slug"  required  >

                            </div>
                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label">عکس دسته بندی</label>
                                <input type="file" class="form-control mt-2" name="photo"  accept="image/*">
                                <span class="caption text-info"> حداقل ابعاد عکس 280 * 280 پیکسل باشد</span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >alt photo </label>
                                <input type="text" class="form-control mt-2" name="photo_alt"   required >

                            </div>
                        </div>

                    </div>


                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >meta description </label>
                                <input type="text" class="form-control mt-2" name="meta_description"  required  >

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >page title </label>
                                <input type="text" class="form-control mt-2" name="page_title"   required >

                            </div>
                        </div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-md-3  text-center mt-2"> <button type="submit" class="btn btn-success waves-effect waves-light m-b-5 "
                                                                         style=" text-align: center;
                              display: flex;
                              align-items: center;
                              justify-content: center;
                              width: 100%;" >افزودن</button></div>

                        <div class="col-md-3 mt-2"></div>
                        <div class="col-md-3 mt-2"></div>
                    </div>



                </form>
            </div>
            <div class="table-container  " style="margin-top: 50px;">
                <table id="datatable" class=" table table-hover table-bordered mt-3  mb-3  ">
                    <thead >
                    <tr  >
                        <th style="text-align: center">اسلاگ</th>
                        <th style="text-align: center">نام</th>
                        <th style="text-align: center">عکس</th>
                        <th style="text-align: center">عملیات</th>
                    </tr>
                    </thead>
                    <tbody id="transactionsTable">
                    @foreach($categories as $category)
                        <tr >
                            <td style="text-align: center">{{$category->slug}}</td>
                            <td style="text-align: center">{{$category->name}}</td>
                            <td style="text-align: center"><img src="{{asset('category/'.$category->photo)}}" style="width: 80px; height: 80px;"></td>
                            <td style="text-align: center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick="fillEditModal({{ $category }})">
                                    <i class="bi bi-pencil-square"></i> ویرایش
                                </button>
                            </td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">ویرایش دسته‌بندی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>نام</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>اسلاگ</label>
                                <input type="text" name="slug" id="edit_slug" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>عکس فعلی</label><br>
                                <img id="current_photo" src="" class="img-thumbnail" style="width:100px; height:100px;">
                                <br><small class="text-muted">برای تغییر عکس، فایل جدید انتخاب کنید</small>
                            </div>
                            <div class="col-md-6">
                                <label>عکس جدید (اختیاری)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label>alt عکس</label>
                                <input type="text" name="photo_alt" id="edit_photo_alt" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>متا دیسکریپشن</label>
                                <input type="text" name="meta_description" id="edit_meta_description" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label>عنوان صفحه</label>
                                <input type="text" name="page_title" id="edit_page_title" class="form-control" required>
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



    <script>
        function fillEditModal(category) {
            document.getElementById('editForm').action = `/admin/category/edit/${category.id}`;
            document.getElementById('edit_id').value = category.id;
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_slug').value = category.slug;
            document.getElementById('edit_photo_alt').value = category.photo_alt;
            document.getElementById('edit_meta_description').value = category.meta_description;
            document.getElementById('edit_page_title').value = category.page_title;
            document.getElementById('current_photo').src = `/category/${category.photo}`;
        }
    </script>
@endsection
