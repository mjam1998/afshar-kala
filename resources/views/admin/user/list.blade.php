@extends('admin.layout.master')


@section('title')
    لیست ادمین ها
@endsection

@section('content')
<div>
    <div>
        <span class="me-3">افرودن ادمین</span>
        <a class="btn btn-success" href="{{route('admin.add.view')}}">افرودن</a>
        <p class="text text-danger mt-2">اخطار:در صورتی که خود را غیرفعال کنید از پنل ادمین خارج و دیگر نمیتوانید وارد پنل ادمین شوید.</p>
    </div>

    <div class="table overflow-auto"  style="margin-top: 30px;" tabindex="8">
        <div class="form-group row">

        </div>
        <table class="table table-striped table-hover">
            <thead class="thead-light">
            <tr>
                <th class="text-center align-middle text-primary">ایدی</th>
                <th class="text-center align-middle text-primary">نام</th>

                <th class="text-center align-middle text-primary"> موبایل</th>


                <th class="text-center align-middle text-primary">تاریخ ایجاد</th>
                <th class="text-center align-middle text-primary">وضعیت</th>
                <th class="text-center align-middle text-primary">ویرایش</th>
                <th class="text-center align-middle text-primary">عملیات</th>

            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-center align-middle">{{$user->id}}</td>


                    <td class="text-center align-middle">{{$user->name}}</td>
                    <td class="text-center align-middle">{{$user->mobile}}</td>

                    <td class="text-center align-middle">{{$user->created_at}}</td>
                    @if(($user->deleted_at)!=null)
                        <td class="text-center align-middle text-danger">
                            غیرفعال
                        </td>
                    @endif
                    @if(($user->deleted_at)==null)
                        <td class="text-center align-middle text-success">
                            فعال
                        </td>
                    @endif
                    <td class="text-center align-middle">
                        @if(($user->deleted_at)==null) <a class="btn btn-outline-info" href="{{route('admin.edit,view',[$user->id])}}">
                            ویرایش
                        </a> @endif

                    </td>
                    <td class="text-center align-middle">
                        @if(($user->deleted_at)==null)
                            <form method="post" action="{{route('admin.delete',[$user->id])}}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" >
                                    غیرفعال
                                </button>
                            </form>
                        @endif
                        @if(($user->deleted_at)!=null)
                            <form method="post" action="{{route('admin.restore',[$user->id])}}">

                                @csrf
                                <button type="submit" class="btn btn-outline-success" >
                                    بازگردانی
                                </button>
                            </form>
                        @endif


                    </td>

                </tr>
            @endforeach


        </table>
        <div style="margin: 40px !important;"
             class="pagination pagination-rounded pagination-sm d-flex justify-content-center">
        </div>
    </div>
<div>

    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form method="post" action="{{route('admin.change.sms')}}">
            @csrf
            <div class="form-group">
                <p>ارسال پیامک های خرید برای ادمین:{{$smsuser->name}}</p>
                <label>ویرایش:</label>
                <select class="form-select" name="smsuserid">
                    @foreach($useractive as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach

                </select>

            </div>
            <button type="submit" class="btn btn-warning mt-3">تغییر</button>
        </form>

    </div>
    <div class="col-md-4"></div>
</div>

</div>


@endsection
