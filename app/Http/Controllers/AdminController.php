<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class AdminController extends Controller
{
    public function index()
    {
        // کارت 1: پرداخت شده و در انتظار ارسال (status = 2)
        $pendingPaidOrders = Order::where('is_paid', 1)
            ->where('status', 2)
            ->count();

        // کارت 2: پرداخت شده و ارسال شده (status = 1)
        $sentPaidOrders = Order::where('is_paid', 1)
            ->where('status', 1)
            ->count();

        // کارت 3: کل سفارشات پرداخت شده
        $totalPaidOrders = Order::where('is_paid', 1)
            ->count();

        // کارت 4: تعداد کل محصولات
        $totalProducts = Product::count();

        // داده‌های نمودار
        $salesData = $this->getSalesDataByCategory();
        $countData = $this->getCountDataByCategory();

        return view('admin.index', compact(
            'pendingPaidOrders',
            'sentPaidOrders',
            'totalPaidOrders',
            'totalProducts',
            'salesData',
            'countData'
        ));
    }

    /**
     * نمودار اول: میزان فروش (تومان) هر دسته‌بندی به تفکیک ماه
     */
    private function getSalesDataByCategory()
    {
        // دریافت تمام سفارشات پرداخت شده و ارسال شده
        $orders = Order::where('is_paid', 1)
            ->where('status', 1)
            ->with('items.product.category')
            ->orderBy('created_at')
            ->get();

        // دسته‌بندی‌ها
        $categories = Category::pluck('name', 'id')->toArray();

        // ساختار داده: [category_id => [year-month => total_amount]]
        $salesByCategory = [];

        foreach ($orders as $order) {
            // تبدیل تاریخ سفارش به شمسی
            $jalaliDate = Jalalian::fromCarbon(Carbon::parse($order->created_at));
            $yearMonth = $jalaliDate->format('Y-m'); // مثلاً: 1403-05

            foreach ($order->items as $item) {
                if ($item->product && $item->product->category_id) {
                    $categoryId = $item->product->category_id;
                    $amount = $item->quantity * $item->price;

                    if (!isset($salesByCategory[$categoryId])) {
                        $salesByCategory[$categoryId] = [];
                    }

                    if (!isset($salesByCategory[$categoryId][$yearMonth])) {
                        $salesByCategory[$categoryId][$yearMonth] = 0;
                    }

                    $salesByCategory[$categoryId][$yearMonth] += $amount;
                }
            }
        }

        // دریافت تمام ماه‌هایی که فروش داشته‌ایم (مرتب شده)
        $allMonths = [];
        foreach ($salesByCategory as $months) {
            $allMonths = array_merge($allMonths, array_keys($months));
        }
        $allMonths = array_unique($allMonths);
        sort($allMonths);

        // آماده‌سازی داده برای نمودار
        $chartData = [];
        foreach ($categories as $categoryId => $categoryName) {
            $data = [];
            foreach ($allMonths as $month) {
                $data[] = $salesByCategory[$categoryId][$month] ?? 0;
            }

            $chartData[] = [
                'name' => $categoryName,
                'data' => $data
            ];
        }

        // تبدیل ماه‌ها به فرمت قابل خواندن (مثلاً: فروردین 1403)
        $monthLabels = [];
        foreach ($allMonths as $ym) {
            list($year, $month) = explode('-', $ym);
            $monthNames = [
                '01' => 'فروردین', '02' => 'اردیبهشت', '03' => 'خرداد',
                '04' => 'تیر', '05' => 'مرداد', '06' => 'شهریور',
                '07' => 'مهر', '08' => 'آبان', '09' => 'آذر',
                '10' => 'دی', '11' => 'بهمن', '12' => 'اسفند'
            ];
            $monthLabels[] = $monthNames[$month] . ' ' . $year;
        }

        return [
            'series' => $chartData,
            'categories' => $monthLabels
        ];
    }

    /**
     * نمودار دوم: تعداد فروش هر دسته‌بندی به تفکیک ماه
     */
    private function getCountDataByCategory()
    {
        // دریافت تمام سفارشات پرداخت شده و ارسال شده
        $orders = Order::where('is_paid', 1)
            ->where('status', 1)
            ->with('items.product.category')
            ->orderBy('created_at')
            ->get();

        // دسته‌بندی‌ها
        $categories = Category::pluck('name', 'id')->toArray();

        // ساختار داده: [category_id => [year-month => count]]
        $countByCategory = [];

        foreach ($orders as $order) {
            $jalaliDate = Jalalian::fromCarbon(Carbon::parse($order->created_at));
            $yearMonth = $jalaliDate->format('Y-m');

            foreach ($order->items as $item) {
                if ($item->product && $item->product->category_id) {
                    $categoryId = $item->product->category_id;
                    $quantity = $item->quantity;

                    if (!isset($countByCategory[$categoryId])) {
                        $countByCategory[$categoryId] = [];
                    }

                    if (!isset($countByCategory[$categoryId][$yearMonth])) {
                        $countByCategory[$categoryId][$yearMonth] = 0;
                    }

                    $countByCategory[$categoryId][$yearMonth] += $quantity;
                }
            }
        }

        // دریافت تمام ماه‌ها
        $allMonths = [];
        foreach ($countByCategory as $months) {
            $allMonths = array_merge($allMonths, array_keys($months));
        }
        $allMonths = array_unique($allMonths);
        sort($allMonths);

        // آماده‌سازی داده برای نمودار
        $chartData = [];
        foreach ($categories as $categoryId => $categoryName) {
            $data = [];
            foreach ($allMonths as $month) {
                $data[] = $countByCategory[$categoryId][$month] ?? 0;
            }

            $chartData[] = [
                'name' => $categoryName,
                'data' => $data
            ];
        }

        // تبدیل ماه‌ها به فرمت قابل خواندن
        $monthLabels = [];
        foreach ($allMonths as $ym) {
            list($year, $month) = explode('-', $ym);
            $monthNames = [
                '01' => 'فروردین', '02' => 'اردیبهشت', '03' => 'خرداد',
                '04' => 'تیر', '05' => 'مرداد', '06' => 'شهریور',
                '07' => 'مهر', '08' => 'آبان', '09' => 'آذر',
                '10' => 'دی', '11' => 'بهمن', '12' => 'اسفند'
            ];
            $monthLabels[] = $monthNames[$month] . ' ' . $year;
        }

        return [
            'series' => $chartData,
            'categories' => $monthLabels
        ];
    }
    public function adminList(){
        $users=User:: query()->withTrashed()->get();
        $useractive=User::query()->get();
        $smsuser=$users->where('type',1)->first();
        return view('admin.user.list',compact('users','smsuser','useractive'));
    }
    public function adminAddView(){
        return view('admin.user.add');
    }
    public  function  adminAddPost(Request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($request['password']);
        User::query()->create([
            'name'=>$data['name'],
            'mobile'=>$data['mobile'],
            'password'=>$data['password'],
            'type'=>2
        ]);
        return redirect(route('admin.list'));

    }

    public function adminEdit($id)
    {
        $user=User::query()->find($id);
        return view('admin.user.edit',compact('user'));
    }

    public function adminEditPost(request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        $user=User::query()->find($data['id']);
        $user->update($data);
        return redirect(route('admin.list'));
    }

    public function adminDelete($id)
    {
        $user=User::query()->find($id);
        $user->delete();
        return redirect(route('admin.list'));
    }
    public function adminRestore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect(route('admin.list'));
    }

    public function adminChangeSms(Request $request)
    {

         $users=User::query()->withTrashed()->where('type',1)->get();
         foreach($users as $user){
             $user->update(['type'=>2]);
         }
        $user=User::query()->find($request['smsuserid']);
         $user->update(['type'=>1]);
        return redirect(route('admin.list'));
    }





    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('home.index'));
    }
}
