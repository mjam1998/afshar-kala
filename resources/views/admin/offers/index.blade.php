@extends('admin.layout.master')

@section('title')
  مدیریت کدهای تخفیف
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
            @if(session()->has('offer-added'))
                <p class="text text-success">{{session('offer-added')}}</p>
            @endif
            <div class="panel-body mt-4">
                <form method="post" action="{{route('admin.offers.store')}}"  >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >کد تخفیف</label>
                                <input type="text" class="form-control mt-2" name="code"  required >

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="control-label" >میزان تخفیف</label>
                                <input type="text" class="form-control mt-2" name="discount_amount"  required  >

                            </div>
                        </div>
                        <div class=" col-md-5 ">
                            <label class="form-label"> تاریخ انقضا</label>
                            <input class="form-control text-dark" name="expires_at" id="persianDate" type="text" required>
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
                <livewire:offers-table />
            </div>
        </div>

    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">ویرایش کد تخفیف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" >
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label" >کد تخفیف</label>
                                    <input type="text" id="edit_code" class="form-control mt-2" name="code"  required >

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label" >میزان تخفیف</label>
                                    <input type="text" id="edit_discount_amount" class="form-control mt-2" name="discount_amount"  required  >

                                </div>
                            </div>
                            <div class=" col-md-5 ">
                                <label class="form-label"> تاریخ انقضا</label>
                                <input class="form-control persianDate text-dark"  name="expires_at" id="edit_expires_at" type="text" required>
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
        function fillEditModal(offer) {
            document.getElementById('editForm').action = `/admin/offers/edit/${offer.id}`;
            document.getElementById('edit_code').value = offer.code;
            document.getElementById('edit_discount_amount').value = offer.discount_amount;

        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.persianDate').persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                observer: true,

                position: 'auto',
                toolbox: {
                    calendarSwitch: { enabled: false }
                },
                altField: '#hiddenDate', // اختیاری
                navigator: {
                    enabled: true
                },
                // مهم‌ترین بخش:
                initialValue: false,
                calendar: {
                    persian: {
                        leapYearMode: 'astronomical'
                    }
                },
                // موقعیت تقویم
                placement: 'bottom' // یا 'top' یا 'auto'
            });
        });
    </script>
@endsection
