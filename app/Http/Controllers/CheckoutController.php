<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SendMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('front.cart.list')->with('error', 'سبد خرید شما خالی است.');
        }

        // محاسبه جمع کل (با قیمت واقعی از دیتابیس)
        $totalAmount = 0;
        foreach ($cart as $key => $item) {
            [$productId, $colorId, $sizeId] = explode('-', $key);

            $product = Product::query()->find($productId);
            if ($product) {
                $price = $product->discount > 0
                    ? $product->price - $product->discount
                    : $product->price;

                $totalAmount += $price * $item['quantity'];
            }
        }

        // دریافت اطلاعات کد تخفیف از session (اگر اعمال شده باشه)
        $appliedOffer = session('applied_offer');
        $offerDiscount = 0;

        if ($appliedOffer) {
            $offerDiscount = $appliedOffer['discount_amount'];
        }

        // محاسبه مبلغ نهایی (بعد از کسر تخفیف کد)
        $finalAmount = $totalAmount - $offerDiscount;

        $sends = SendMethod::all();

        return view('front.checkout', compact(
            'cart',
            'totalAmount',
            'sends',
            'appliedOffer',    // اطلاعات کد تخفیف
            'offerDiscount',   // مبلغ تخفیف
            'finalAmount'      // مبلغ نهایی بعد از تخفیف
        ));
    }


    public function paySubmit(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('front.cart.list')->with('error', 'سبد خرید خالی است.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|size:11|regex:/^09[0-9]{9}$/',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string|size:10',
            'send_method_id' => 'required|exists:send_methods,id',
        ], [
            'name.required' => 'نام و نام خانوادگی الزامی است.',
            'name.string' => 'نام باید به صورت متن باشد.',
            'name.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.string' => 'شماره موبایل باید به صورت متن باشد.',
            'mobile.size' => 'شماره موبایل باید ۱۱ رقم باشد.',
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست (باید با 09 شروع شود).',

            'state.required' => 'انتخاب استان الزامی است.',
            'state.string' => 'استان باید به صورت متن باشد.',

            'city.required' => 'انتخاب شهر الزامی است.',
            'city.string' => 'شهر باید به صورت متن باشد.',

            'address.required' => 'آدرس دقیق الزامی است.',
            'address.string' => 'آدرس باید به صورت متن باشد.',

            'postal_code.required' => 'کد پستی الزامی است.',
            'postal_code.string' => 'کد پستی باید به صورت متن باشد.',
            'postal_code.size' => 'کد پستی باید ۱۰ رقم باشد.',

            'send_method_id.required' => 'انتخاب روش ارسال الزامی است.',
            'send_method_id.exists' => 'روش ارسال انتخاب شده معتبر نیست.',
        ]);
        $totalAmount = 0;
        $totalDiscount = 0;
        $outOfStockItems = [];

        // چک موجودی + محاسبه قیمت واقعی از دیتابیس
        foreach ($cart as $key => $item) {
            [$productId, $colorId, $sizeId] = explode('-', $key);

            // پیدا کردن variant
            $variant = ProductVariant::query()->where([
                'product_id' => $productId,
                'color_id' => $colorId,
                'size_id' => $sizeId,
            ])->first();

            if (!$variant) {
                $outOfStockItems[] = $item['product_name'] . " (رنگ: {$item['color_name']}, سایز: {$item['size_name']}) - این ترکیب دیگر موجود نیست.";
                continue;
            }

            if ($variant->count < $item['quantity']) {
                $outOfStockItems[] = $item['product_name'] . " (رنگ: {$item['color_name']}, سایز: {$item['size_name']}) - موجودی کافی نیست (فقط {$variant->count} عدد موجود است).";
                continue;
            }

            // محاسبه قیمت واقعی از دیتابیس
            $product = Product::findOrFail($productId);
            $finalPrice = $product->discount > 0
                ? $product->price - $product->discount
                : $product->price;
            $discount = $product->discount;
            $totalAmount += $finalPrice * $item['quantity'];
            $totalDiscount += $discount * $item['quantity'];

        }
        $backUrl = route('checkout.pay.callback');
        $offerDiscount = 0;
        $appliedOffer = session('applied_offer');

        if ($appliedOffer) {
            $offerDiscount = $appliedOffer['discount_amount'];
            $totalAmount -= $offerDiscount; // کم کردن تخفیف از مبلغ کل
            $totalDiscount +=$offerDiscount;
        }

        $response= Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',

        ])->post('https://gateway.zibal.ir/v1/request', [
            "merchant" => env('ZIBAL_API_KEY'),
            "amount" => $totalAmount * 10,
            "description"=>"پرداخت در افشار کالا",

            "callbackUrl"=>$backUrl
        ]);






        if ($response['result']==100) {
            $offerCode = session('applied_offer.code');
            $offer = Offer::where('code', $offerCode)->first();
            if($offer){
                $offerId = $offer->id;
            }

            $trackId=$response['trackId'];
            $order = Order::query()->create([
                'send_method_id' => $request['send_method_id'],
                'name' => $request['name'],
                'mobile' => $request['mobile'],
                'total_amount' => $totalAmount + $totalDiscount,
                'pay_amount' => $totalAmount,

                'state' => $request['state'],
                'city' => $request['city'],
                'address' => $request['address'],
                'postal_code' => $request['postal_code'],

                'id_get' => $trackId,
                'offer_id'=>$offerId ?? null,

            ]);

            foreach ($cart as $key => $item) {
                [$productId, $colorId, $sizeId] = explode('-', $key);

                $product = Product::findOrFail($productId);


                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_color_id' => $colorId,
                    'product_size_id' => $sizeId,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'quantity' => $item['quantity'],
                ]);

            }

            return redirect()->away("https://gateway.zibal.ir/start/$trackId");

        }
        return redirect()->back()->with('errorPortal', 'خطا در ایجاد درگاه پرداخت.');
    }

    public function payCallback(Request $request)
    {


        $trackId = $request->input('trackId');
        $success = $request->input('success');

        $order=Order::query()->where('id_get',$trackId)->first();
        if ($order==null){
            return redirect()->route('home.index');
        }
        if ($success == 1) {
            // تایید پرداخت
            return $this->verifyPayment($trackId);
        }
        // لغو پرداخت



        $order_item=OrderItem::query()->where('order_id',$order->id)->get();
        foreach ($order_item as $item) {
            $item->delete();
        }
        $order->delete();
        return redirect()->route('checkout.index')
            ->with('errorPortal', 'پرداخت توسط کاربر لغو شد.');

    }
    private function verifyPayment($id_get){
        $order=Order::query()->where('id_get',$id_get)->first();
        $trackingCode = strtoupper(Str::random(9));

        session()->forget('cart');
        session()->forget('applied_offer');

        $response= Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',

        ])->post('https://gateway.zibal.ir/v1/verify', [
            "merchant" => env('ZIBAL_API_KEY'),
            "trackId" => $id_get,


        ]);


        if($response['result']==100 && $response['status']==1 && $response['amount']==$order->pay_amount * 10 ){

            $order->update([
                'trans_id'=>$response['refNumber'],
                'is_paid'=>1,
                'status'=>2,
                'paid_at'=>now(),
                'track_number'=>$trackingCode
            ]);
            $admin=User::query()->where('type',1)->first();


            //پیامک به ادمین
            Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'x-api-key' => env('SMS_IR_API_KEY')
            ])->post('https://api.sms.ir/v1/send/verify', [
                "mobile" => $admin->mobile,
                "templateId" => 622998,
                "parameters" => [
                    [
                        "name" => "TRACNUM",
                        "value" => $trackingCode
                    ]
                ]
            ]);

            //پیامک به مشتری
            Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'x-api-key' => env('SMS_IR_API_KEY')
            ])->post('https://api.sms.ir/v1/send/verify', [
                "mobile" => $order->mobile,
                "templateId" => 833510,
                "parameters" => [
                    [
                        "name" => "NAME",
                        "value" => $order->name
                    ],[
                        "name" => "TRACKNUM",
                        "value" => $trackingCode
                    ],[
                        "name" => "AMOUNT",
                        "value" => number_format($order->pay_amount)
                    ]
                ]
            ]);
            foreach ($order->items as $item) {
                ProductVariant::query()->where([
                    'product_id' => $item->product_id,
                    'color_id'   => $item->product_color_id,
                    'size_id'    => $item->product_size_id,
                ])->decrement('count', $item['quantity']);
            }

            return redirect()->route('checkout.payment.result',['track'=>$trackingCode]);
        }

        $order->update([
            'is_paid'=>0,
            'paid_at'=>now(),
            'track_number'=>$trackingCode
        ]);
        return redirect()->route('checkout.payment.result',['track'=>$trackingCode]);
    }

    public function paymentResult($track)
    {
        $order=Order::query()->where('track_number',$track)->first();
        if ($order==null){
            return redirect()->route('home.index');
        }
        return view('front.payresult',compact('order'));
    }
}
