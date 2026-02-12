@extends('admin.layout.master')

@section('title')
   روش ارسال محصولات
@endsection

@section('content')
    <div class="profile-content ">
        <div class="profile-section active" >


            <h3 class="section-title"><i class="bi bi-info-circle-fill"></i> روش ارسال محصولات</h3>
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
            @if(session()->has('sendAdded'))
                <p class="text text-success">{{session('sendAdded')}}</p>
            @endif
            <div class="panel-body mt-4">
                <form method="post" action="{{route('admin.send.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >نام روش ارسال</label>
                                <input type="text" class="form-control mt-2" name="name"  required >

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >توضیح روش ارسال</label>
                                <textarea type="text" class="form-control mt-2" name="description"  required >
                                </textarea>
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
                <table  class=" table table-hover table-bordered mt-3  mb-3  ">
                    <thead >
                    <tr  >

                        <th style="text-align: center">نام</th>
                        <th style="text-align: center">روش ارسال</th>
                        <th style="text-align: center">عملیات</th>
                    </tr>
                    </thead>
                    <tbody id="transactionsTable">
                    @foreach($sends as $send)
                        <tr >

                            <td style="text-align: center">{{$send->name}}</td>
                            <td style="text-align: center">{{$send->description}}</td>
                            <td style="text-align: center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick="fillEditModal({{ $send }})">
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
            {{ $sends->links() }}
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">ویرایش روش ارسال</h5>
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
                                <label>روش ارسال</label>
                                <textarea type="text" name="description" id="edit_description" class="form-control" required>
                                </textarea>
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
        function fillEditModal(send) {
            document.getElementById('editForm').action = `/admin/send/edit/${send.id}`;
            document.getElementById('edit_id').value = send.id;
            document.getElementById('edit_name').value = send.name;
            document.getElementById('edit_description').value = send.description;

        }
    </script>
@endsection

