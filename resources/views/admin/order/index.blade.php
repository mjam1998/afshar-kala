@extends('admin.layout.master')

@section('title', 'مدیریت سفارش‌های پرداخت‌شده')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">مدیریت سفارشات</h3>

        <livewire:order-table />
    </div>

    <script>
        // اسکریپت‌های قبلی Persian Datepicker
        $(document).ready(function() {
            $('.persian-datepicker').persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                observer: true
            });
        });

        function toggleSendDate(orderId) {
            const select = document.getElementById('statusSelect' + orderId);
            const container = document.getElementById('sendDateContainer' + orderId);

            container.style.display = select.value == '1' ? 'block' : 'none';
        }
    </script>
@endsection
