<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class OffersController extends Controller
{
    public function index(){

        return view('admin.offers.index');

    }
    public function store(Request $request)
    {
        $normalizedDate =$this->normalizePersianDate($request['expires_at']); // 1377/07/22
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $normalizedDate)
            ->toCarbon()
            ->format('Y-m-d');
        Offer::query()->create([
           'code'=>$request['code'],
           'discount_amount'=>$request['discount_amount'],
           'expires_at'=>$gregorianDate,
           'created_at'=>now()
        ]);
        return redirect()->route('admin.offers.index')->with('offer-added','کد تخفیف با موفقیت ایجاد شد.');
    }
    public function edit($id, Request $request)
    {
        $offer = Offer::query()->find($id);
        $normalizedDate =$this->normalizePersianDate($request['expires_at']); // 1377/07/22
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $normalizedDate)
            ->toCarbon()
            ->format('Y-m-d');
        $offer->update([
            'code'=>$request['code'],
            'discount_amount'=>$request['discount_amount'],
            'expires_at'=>$gregorianDate,
            'updated_at'=>now()
        ]);
        return redirect()->route('admin.offers.index')->with('offer-added','کد تخفیف با موفقیت ایجاد شد.');
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
    public function applyOffer(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ], [
            'code.required' => 'لطفاً کد تخفیف را وارد کنید.',
        ]);

        $code = trim($request->code);

        // پیدا کردن کد تخفیف
        $offer = Offer::where('code', $code)->first();

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'کد تخفیف معتبر نیست.'
            ]);
        }

        // چک تاریخ اعتبار
        if (!$offer->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'کد تخفیف منقضی شده است.'
            ]);
        }

        if (session('applied_offer.code') === $offer->code) {
            return back()->with('error', 'این کد قبلاً اعمال شده است.');
        }



        // محاسبه تخفیف
        $cart = session('cart', []);
        $totalAmount = 0;

        foreach ($cart as $key => $item) {
            [$productId] = explode('-', $key);
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $price = $product->discount > 0
                    ? $product->price - $product->discount
                    : $product->price;
                $totalAmount += $price * $item['quantity'];
            }
        }




            $discountAmount = $offer->discount_amount;


        // محدود کردن تخفیف به مبلغ کل
        $discountAmount = min($discountAmount, $totalAmount);

        $finalAmount = $totalAmount - $discountAmount;

        // ذخیره در session
        session([
            'applied_offer' => [
                'id' => $offer->id,
                'code' => $offer->code,
                'discount_amount' => $discountAmount,
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف با موفقیت اعمال شد.',
            'discount_amount' => number_format($discountAmount),
            'final_amount' => number_format($finalAmount),
        ]);
    }

    public function removeOffer()
    {
        session()->forget('applied_offer');

        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف حذف شد.'
        ]);
    }
}
