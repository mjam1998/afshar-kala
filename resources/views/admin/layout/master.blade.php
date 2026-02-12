<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('front/assets/logo-smal.png')}}">
    <link rel="icon" type="image/png" href="{{asset('front/assets/logo-smal.png')}}">

    <!-- برای دستگاه‌های اپل -->
    <link rel="apple-touch-icon" href="{{asset('front/assets/logo-smal.png')}}">
    @livewireStyles


    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin/css/persian-datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}"/>
    <script src="{{asset('bootstrap/jquery-3.6.0.min.js')}}"></script>


    <style>
        #exitBtn:hover {
            background-color: darkred;
            border-radius: 10px;
        }
    </style>

</head>
<body>
<button id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">

        </div>
        <div class="admin-info">

            <div class="admin-details">
                <span class="admin-name">{{auth()->user()->name}}</span>
                <span class="admin-role"> ادمین</span>
            </div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="{{route('admin.index')}}"  >
                <i class="bi bi-house-fill"></i>
                <span>خانه</span>
            </a>
        </li>


            <li class="menu-item">
                <a href="{{route('admin.list')}}"  >
                    <i class="bi bi-person-gear"></i>
                    <span> مدیریت ادمین ها</span>
                </a>
            </li>
        <li class="menu-item">
            <a href="{{route('admin.category.list')}}"  >
                <i class="bi bi-tags"></i>
                <span>   دسته بندی محصولات</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.product.index')}}"  >
                <i class="bi bi-basket"></i>
                <span>    محصولات</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.orders.index')}}"  >
                <i class="bi bi-cart3"></i>
                <span>    سفارشات</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.offers.index')}}"  >
                <i class="bi bi-percent"></i>
                <span> کد تخفیف</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.blog.index')}}"  >
                <i class="bi bi-file-earmark-font"></i>
                <span>    مقالات</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.banner.index')}}"  >
                <i class="bi bi-card-image"></i>
                <span>     بنر صفحه اصلی</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{route('admin.send.list')}}"  >
                <i class="bi bi-box-seam"></i>
                <span>     روش ارسال محصولات</span>
            </a>
        </li>




        <form method="post" action="{{route('logout')}}">
          @csrf
            <li class="menu-item" id="exitBtn">
                <button  type="submit" class="btn  " style="color: lightgrey;" >
                    <i class="bi bi-box-arrow-right"></i>
                    <span>خروج </span>


                </button>
            </li>
        </form>

    </ul>
</div>

<div class="main-content">
    <div  class="dynamic-content">


        <!-- محتوای پیش‌فرض (داشبورد) -->
        <div class="page-header">
            <h1 class="page-title">
                @yield('title')

            </h1>

        </div>

        <div class="stats-container">
            @yield('content')
        </div>

    </div>
</div>
@livewireScripts

<script src="{{asset('admin/js/persian-date.min.js')}}"></script>
<script src="{{asset('admin/js/persian-datepicker.min.js')}}"></script>
<script src="{{asset('bootstrap/bootstrap.min.js')}}" ></script>
<script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}" ></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fa.json"
            },
            "paging": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "autoWidth": false,
            "order": [[1, "asc"]] // مثلاً بر اساس نام دسته‌بندی مرتب کنه
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#persianDate').persianDatepicker({
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
<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');

        // اختیاری: وقتی سایدبار باز میشه، یه اورلی تیره بذاریم که با کلیک خارج از منو بسته بشه
        document.body.classList.toggle('sidebar-open');
    });

    // بستن منو با کلیک بیرون از آن (در موبایل)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // وقتی اندازه صفحه تغییر کرد و بزرگ شد، مطمئن بشیم سایدبار باز باشه
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    });
</script>

</body>
</html>

