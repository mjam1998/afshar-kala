<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class OrderController extends Controller
{
    public function index()
    {


        return view('admin.order.index');
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
