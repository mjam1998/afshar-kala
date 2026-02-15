<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use TCPDF;
use TCPDF_FONTS;

class OrderController extends Controller
{
    public function index()
    {


        return view('admin.order.index');
    }
    public function generateInvoicePdf(Order $order)
    {
        $shopInfo = config('shop');

        // ایجاد نمونه TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

        // تنظیمات اولیه
        $pdf->SetCreator('فروشگاه ' . $shopInfo['name']);
        $pdf->SetAuthor($shopInfo['name']);
        $pdf->SetTitle('فاکتور سفارش ' . $order->track_number);

        // حذف هدر و فوتر پیش‌فرض
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // تنظیم حاشیه‌ها
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        // اضافه کردن صفحه
        $pdf->AddPage();

        // تنظیم فونت فارسی
        $fontPath = storage_path('fonts/tcpdf/');
        $fontRegular = TCPDF_FONTS::addTTFfont($fontPath . 'Vazirmatn-Regular.ttf', 'TrueTypeUnicode', '', 96);
        $fontBold = TCPDF_FONTS::addTTFfont($fontPath . 'Vazirmatn-Bold.ttf', 'TrueTypeUnicode', '', 96);

        // فعال‌سازی RTL
        $pdf->setRTL(true);
        $pdf->SetFont($fontRegular, '', 10);

        // تولید HTML
        $html = view('admin.order.invoice-pdf-tcpdf', compact('order', 'shopInfo', 'fontBold'))->render();

        // نوشتن HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // خروجی PDF
        return response($pdf->Output("invoice-{$order->track_number}.pdf", 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="invoice-' . $order->track_number . '.pdf"');
    }
    public function updateStatus(Request $request, Order $order)
    {

        $request->validate([
            'status' => 'required|in:1,2,3',
            'send_at' => 'nullable|string',

        ]);
        $data = ['status' => $request->status];
        $data['postal_track']=$request->postal_track;

        if ($request->status == 1 && $request->filled('send_at')) {




            $normalizedDate =$this->normalizePersianDate($request['send_at']);
            $gregorianDate = Jalalian::fromFormat('Y/m/d', $normalizedDate)
                ->toCarbon()
                ->format('Y-m-d');
                $data['send_at'] =$gregorianDate;

        } else {
            $data['send_at'] = null;
        }

        $order->update($data);

        return back()->with('success', 'وضعیت سفارش با موفقیت بروزرسانی شد.');
    }
    public function normalizePersianDate($date)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $date = str_replace($persian, $english, $date);

        // حذف فاصله و کاراکترهای اضافی
        $date = preg_replace('/[^\d\/]/', '', $date);

        return $date;
    }
}
