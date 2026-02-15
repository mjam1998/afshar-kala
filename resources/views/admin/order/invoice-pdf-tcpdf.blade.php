<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'vazirmatn', sans-serif; /* فونت وزیر یا هر فونت فارسی که دارید */
            direction: rtl;
            text-align: right;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* استایل‌های کلی جداول */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* هدر اصلی */
        .header-tbl {
            margin-bottom: 10px;
            border-bottom: 2px solid #444;
        }
        .header-tbl td {
            vertical-align: middle;
            padding-bottom: 5px;
        }

        /* باکس‌های اطلاعات (فروشنده و خریدار) */
        .box-container {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            height: 110px; /* ارتفاع ثابت برای هم‌اندازه شدن باکس‌ها */
        }

        .box-title {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            margin-bottom: 6px;
            color: #000;
        }

        .row-item {
            display: block;
            margin-bottom: 3px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        /* جدول محصولات */
        .items-table {
            margin-top: 10px;
        }

        .items-table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 6px;
            font-size: 11px;
            border: 1px solid #2c3e50;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            font-size: 11px;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* بخش جمع کل */
        .total-box {
            width: 40%;
            float: left; /* شناور به چپ */
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
        }

        .total-row {
            margin-bottom: 5px;
            font-size: 11px;
            width: 100%;
            overflow: hidden; /* برای جلوگیری از به هم ریختگی فلوت */
        }

        .total-label {
            float: right;
            width: 60%;
        }

        .total-value {
            float: left;
            width: 40%;
            text-align: left;
            font-weight: bold;
        }

        .final-price {
            border-top: 1px solid #999;
            padding-top: 5px;
            margin-top: 5px;
            font-size: 13px;
            color: #28a745;
        }

        .footer {
            clear: both; /* پاک کردن اثر فلوت‌ها */
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

    </style>
</head>
<body>

<!-- هدر: استفاده از جدول برای چیدمان چپ و راست هدر -->
<table class="header-tbl">
    <tr>
        <td style="text-align: right; width: 60%;">
            <h1 style="font-size: 18px; margin: 0;">فاکتور فروش</h1>
        </td>
        <td style="text-align: left; width: 40%;">
            <div style="font-size: 11px;">کد پیگیری: <strong>{{ $order->track_number }}</strong></div>
            <div style="font-size: 10px; margin-top: 2px;">تاریخ خرید: {{ \Morilog\Jalali\Jalalian::forge($order->paid_at)->format('Y/m/d h:i' ) }}</div>
        </td>
    </tr>
</table>

<!-- بدنه اصلی: استفاده از جدول برای دو ستونه کردن اطلاعات فروشنده و خریدار -->
<!-- این روش در PDF سازها مطمئن‌ترین روش است -->
<table class="box-container" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <!-- ستون اول: فروشنده -->
        <td style="width: 49%; vertical-align: top;">
            <div class="info-box">
                <div class="box-title">اطلاعات فروشگاه</div>
                <div class="row-item"><span class="label">نام:</span> {{ $shopInfo['name'] }}</div>
                <div class="row-item"><span class="label">تلفن:</span> {{ $shopInfo['phone'] }}</div>
                <div class="row-item"><span class="label">ایمیل:</span> {{ $shopInfo['email'] }}</div>
                <div class="row-item"><span class="label">آدرس:</span> {{ $shopInfo['address'] }}</div>
            </div>
        </td>

        <!-- فاصله بین دو ستون -->
        <td style="width: 2%;"></td>

        <!-- ستون دوم: خریدار -->
        <td style="width: 49%; vertical-align: top;">
            <div class="info-box">
                <div class="box-title">اطلاعات خریدار</div>
                <div class="row-item"><span class="label">نام:</span> {{ $order->name }}</div>
                <div class="row-item"><span class="label">موبایل:</span> {{ $order->mobile }}</div>
                <div class="row-item">
                    <span class="label">مکان:</span> {{ $order->state }} - {{ $order->city }}
                </div>
                <div class="row-item">
                    <span class="label">آدرس:</span> {{ $order->address }}
                    @if($order->postal_code)
                        - <span class="label">پستی:</span> {{ $order->postal_code }}
                    @endif
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- جدول محصولات -->
<table class="items-table">
    <thead>
    <tr>
        <th >#</th>
        <th >نام محصول</th>
        <th >رنگ</th>
        <th >سایز</th>
        <th >تعداد</th>
        <th >قیمت واحد</th>
        <th >جمع کل</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td style="text-align: right;">{{ $item->product->name }}</td>
            <td>{{ $item->product_color->name ?? '-' }}</td>
            <td>{{ $item->product_size->name ?? '-' }}</td>
            <td>{{ number_format($item->quantity) }}</td>
            <td>{{ number_format($item->price - $item->discount) }}</td>
            <td style="font-weight: bold;">{{ number_format(($item->price - $item->discount) * $item->quantity) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- باکس جمع کل (شناور به سمت چپ) -->
<div>
    <div class="total-box">
        <div class="total-row">
            <span class="total-label">جمع کل کالاها:</span>
            <span class="total-value">{{ number_format($order->total_amount) }}</span>
        </div>

        @if($order->offer_id)
            <div class="total-row" style="color: #dc3545;">
                <span class="total-label">تخفیف ({{ $order->offer->code }}):</span>
                <span class="total-value">{{ number_format($order->offer->discount_amount) }}-</span>
            </div>
        @endif

        @if($order->total_amount > $order->pay_amount)
            <div class="total-row" style="color: #dc3545;">
                <span class="total-label">جمع تخفیف‌ها:</span>
                <span class="total-value">{{ number_format($order->total_amount - $order->pay_amount) }}-</span>
            </div>
        @endif

        <div class="total-row final-price">
            <span class="total-label">مبلغ قابل پرداخت (تومان):</span>
            <span class="total-value">{{ number_format($order->pay_amount) }}  </span>
        </div>
    </div>
    <!-- یک div خالی با clear:both برای اینکه اگر متنی بعد از باکس آمد، درست نمایش داده شود -->
    <div style="clear: both;"></div>
</div>

<!-- فوتر -->
<div class="footer">
    از خرید شما سپاسگزاریم.
</div>

</body>
</html>
