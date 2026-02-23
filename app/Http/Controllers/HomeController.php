<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Order;
use App\Models\PhotoBanner;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\VideoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){


      $videoBanner=VideoBanner::where('sort', 1)->first();
       $banner2 = VideoBanner::where('sort', 2)->first();
      $banner3 = VideoBanner::where('sort', 3)->first();
        $banner4 = VideoBanner::where('sort', 4)->first();
// محدود کردن عکس‌های محصولات به 4 تا
        $products = Product::query()
            ->with([
                'photos' => fn($q) => $q->orderBy('id')->limit(4),
                'variants' => fn($q) => $q->where('count', '>', 0)->with('color', 'size'),
            ])
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();

        // محدود کردن عکس‌های محصولات ویژه به 4 تا
        $specials = Product::query()
            ->where('is_special', 1)
            ->with([
                'photos' => fn($q) => $q->orderBy('id')->limit(4),
                'variants' => fn($q) => $q->where('count', '>', 0)->with('color', 'size'),
            ])
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();
      $bannerPrimary=PhotoBanner::query()->where('id','1')->first();
      $bannerRight=PhotoBanner::query()->where('id','2')->first();
      $bannerCenter=PhotoBanner::query()->where('id','3')->first();
        $bannerLeft=PhotoBanner::query()->where('id','4')->first();
        $bannerPrimary2=PhotoBanner::query()->where('id','5')->first();
      $categories=Category::all();
      $blogs=Blog::query()->orderBy('id','desc')->take(3)->get();
        return view('front.index',compact(
            'videoBanner','products','bannerPrimary',
            'bannerRight','bannerCenter','categories','blogs','specials','bannerLeft','bannerPrimary2',
            'banner2','banner3','banner4'
        ));
    }

    public function login()
    {
        return view('front.login');
    }
    public function loginPost(Request $request)
    {
        $user=User::query()->where('mobile',$request['mobile'])->first();
        if($user==null){
            return back()->with('loginError','اطلاعات نادرست لطفا دوباره تلاش کنید.');
        }
        if(Hash::check($request->password,$user->password)){
            Auth::login($user);
            return redirect()->route('admin.index');
        }
        return back()->with('loginError',' اطلاعات نادرست لطفا دوباره تلاش کنید.');
    }

    public function showCategory(Request $request,$slug)
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $query = $category->products();


        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'latest':
                    $query->latest();
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        $products =$query
            ->with([
                'photos' => fn($q) => $q->orderBy('id')->limit(4),
                'variants' => fn($q) => $q->where('count', '>', 0)->with('color', 'size'),
            ])
            ->orderBy('id', 'desc')
            ->paginate(8);
       /* // تغییر این خط - محدود کردن عکس‌ها به 4 تا
        $products = $query->with(['photos' => function($photoQuery) {
            $photoQuery->orderBy('id')->limit(4);
        }])->paginate(8);*/

        return view('front.category', compact('category', 'products'));
    }
    public function showProduct($slug)
    {
        $product = Product::with([
            'photos',
            'product_comments' => fn($q) => $q->where('status', 1)->latest(),


            'variants' => fn($q) => $q->where('count', '>', 0)
                ->with('color', 'size'),



        ])->where('slug', $slug)->firstOrFail();


        $availableColors = $product->variants->pluck('color')->unique('id')->filter();


        $availableSizes = $product->variants->pluck('size')->unique('id')->filter();

        $coments=ProductComment::query()->where('product_id',$product->id)->paginate(10);

        return view('front.product', compact('product', 'availableColors', 'availableSizes', 'coments'));
    }

    // چک موجودی با AJAX
    public function checkStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id'   => 'required|exists:product_colors,id',
            'size_id'    => 'required|exists:product_sizes,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::query()->where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (!$variant || $variant->count < $request->quantity) {
            return response()->json([
                'available' => false,
                'message'  => $variant
                    ? "موجودی این ترکیب فقط {$variant->count} عدد است."
                    : "این ترکیب رنگ و سایز موجود نیست یا تمام شده است."
            ]);
        }

        return response()->json([
            'available' => true,
            'stock'     => $variant->count,
            'message'   => 'موجود است'
        ]);
    }
    public function storeComment(Request $request, Product $product)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        ProductComment::query()->create([
            'product_id'     => $product->id,
            'name'           => strip_tags($request->name),
            'comment'        => strip_tags($request->comment),
            'status'         => 2, // در انتظار تأیید
            'admin_response' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'نظر شما با موفقیت ثبت شد و پس از بررسی نمایش داده خواهد شد.'
        ]);
    }
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id'   => 'required|exists:product_colors,id',
            'size_id'    => 'required|exists:product_sizes,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $colorId   = $request->color_id;
        $sizeId    = $request->size_id;
        $quantity  = $request->quantity;

        // ابتدا چک موجودی (دوباره، برای امنیت)
        $variant = ProductVariant::where('product_id', $productId)
            ->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->first();

        if (!$variant || $variant->count < $quantity) {
            return response()->json([
                'success' => false,
                'message' => $variant
                    ? "موجودی کافی نیست. فقط {$variant->count} عدد موجود است."
                    : "این ترکیب موجود نیست."
            ]);
        }

        // دریافت سبد خرید فعلی از session
        $cart = session()->get('cart', []);

        // کلید یکتا برای هر ترکیب محصول + رنگ + سایز
        $key = "{$productId}-{$colorId}-{$sizeId}";

        if (isset($cart[$key])) {
            // اگر قبلاً اضافه شده، تعداد رو افزایش بده (اگر موجودی اجازه بده)
            $newQuantity = $cart[$key]['quantity'] + $quantity;
            if ($newQuantity > $variant->count) {
                return response()->json([
                    'success' => false,
                    'message' => "موجودی کافی نیست. حداکثر {$variant->count} عدد می‌توانید اضافه کنید."
                ]);
            }
            $cart[$key]['quantity'] = $newQuantity;
        } else {
            // اطلاعات محصول رو برای نمایش بعدی ذخیره کن
            $product = Product::find($productId);

            $cart[$key] = [
                'product_id'   => $productId,
                'product_name' => $product->name,
                'price' => $variant->discount > 0
                    ? $variant->price - $variant->discount
                    : $variant->price,
                'color_id'     => $colorId,
                'color_name'   => $variant->color->name ?? 'نامشخص',
                'color_code'   => $variant->color->code ?? '#000',
                'size_id'      => $sizeId,
                'size_name'    => $variant->size->name ?? 'نامشخص',
                'quantity'     => $quantity,
                'image'        => $product->photos->first()?->photo ?? null, // اولین عکس
            ];
        }

        // ذخیره سبد در session
        session()->put('cart', $cart);

        // تعداد کل آیتم‌ها در سبد
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'     => true,
            'message'     => "{$quantity} عدد به سبد خرید اضافه شد.",
            'total_items' => $totalItems
        ]);
    }
    public function cartDropdown()
    {
        // همیشه آرایه باشه، حتی اگر null باشه
        $cart = session('cart', []);

        $totalItems = array_sum(array_column($cart, 'quantity'));
        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('front.partials.cart-dropdown', compact('cart', 'totalItems', 'totalPrice'))->render();
    }
    public function showArticles(Request $request)
    {
        $query = Blog::query();


        $query->orderByDesc('id');


        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");

        }

        // pagination
        $blogs = $query->paginate(6);

        return view('front.articles', compact('blogs'));
    }
    public function showBlog($slug)
    {
        $article = Blog::query()->where('slug', $slug)->firstOrFail();



        return view('front.blog', [
            'article' => $article

        ]);
    }
    public function search(Request $request)
    {


        $query = Product::query();


        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");

        }

        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'latest':
                    $query->latest();
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products =$query
            ->with([
                'photos' => fn($q) => $q->orderBy('id')->limit(4),
                'variants' => fn($q) => $q->where('count', '>', 0)->with('color', 'size'),
            ])
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('front.search', compact( 'products'));
    }
    public function cartList()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        $totalItems = 0;
        if (empty($cart)){
            return view('front.cart', compact('cart', 'totalPrice', 'totalItems'));
        }
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        return view('front.cart', compact('cart', 'totalPrice', 'totalItems'));
    }


    public function cartUpdate(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $key = $request->key;
        $newQuantity = $request->quantity;

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return response()->json([
                'success' => false,
                'message' => 'آیتم مورد نظر در سبد خرید یافت نشد.'
            ]);
        }

        // استخراج product_id, color_id, size_id از کلید
        [$productId, $colorId, $sizeId] = explode('-', $key);

        $variant = ProductVariant::where('product_id', $productId)
            ->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->first();

        if (!$variant || $variant->count < $newQuantity) {
            $available = $variant ? $variant->count : 0;
            return response()->json([
                'success' => false,
                'message' => "موجودی کافی نیست. حداکثر {$available} عدد موجود است."
            ]);
        }

        // بروزرسانی تعداد
        $cart[$key]['quantity'] = $newQuantity;
        session()->put('cart', $cart);

        // محاسبه مجدد جمع کل و تعداد آیتم‌ها
        $totalPrice = 0;
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        return response()->json([
            'success'     => true,
            'message'     => 'سبد خرید با موفقیت بروزرسانی شد.',
            'totalPrice'  => $totalPrice,           // بدون format (برای جاوااسکریپت)
            'totalItems'  => $totalItems,
            'itemPrice'   => $cart[$key]['price'] * $newQuantity
        ]);
    }


    public function cartRemove(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return response()->json([
                'success' => false,
                'message' => 'آیتم مورد نظر یافت نشد.'
            ]);
        }

        // حذف آیتم
        unset($cart[$key]);
        session()->put('cart', $cart);

        // محاسبه مجدد تعداد کل آیتم‌ها
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'    => true,
            'message'    => 'محصول از سبد خرید حذف شد.',
            'totalItems' => $totalItems
        ]);
    }
    public function showForm()
    {
        return view('front.order.track');
    }

    // جستجو و نمایش جزئیات سفارش
    public function track(Request $request)
    {
        $request->validate([
            'track_number' => 'required|string|exists:orders,track_number',
        ], [
            'track_number.required' => 'لطفاً کد پیگیری را وارد کنید.',
            'track_number.exists'   => 'کد پیگیری وارد شده یافت نشد.',
        ]);

        $order = Order::with(['items.product', 'items.product_color', 'items.product_size', 'send_method'])
            ->where('track_number', $request->track_number)
            ->firstOrFail();

        return view('front.order.result', compact('order'));
    }

    public function aboutUs()
    {
        return view('front.aboutus');
    }

    public function contactUs()
    {
        return view('front.contactus');
    }

    public function rules()
    {
        return view('front.rules');
    }

}
