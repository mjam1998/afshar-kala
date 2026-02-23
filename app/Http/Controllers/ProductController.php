<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductComment;
use App\Models\ProductPhoto;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Morilog\Jalali\Jalalian;

class ProductController extends Controller
{
    public function index()
    {

         $categories=Category::all();
         return view('admin.product.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:products,slug',
            ],

            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id|not_in:0',

            'discount' => 'nullable|numeric|min:0|lte:price',
            'meta_description' => 'required|string',
            'page_title' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'weight' => 'required|string|max:100',
            'dimension' => 'required|string|max:100',
        ], [

            'name.required' => 'وارد کردن نام الزامی است.',
            'name.max' => 'نام محصول نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'slug.required' => 'وارد کردن اسلاگ الزامی است.',
            'slug.regex' => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z) و عدد باشد. فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',


            'price.required' => 'وارد کردن قیمت الزامی است.',
            'price.numeric' => 'قیمت باید عدد معتبر باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',

            'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
            'category_id.exists' => 'دسته‌بندی انتخاب‌شده معتبر نیست.',
            'category_id.not_in' => 'لطفاً یک دسته‌بندی معتبر انتخاب کنید.',



            'discount.numeric' => 'میزان تخفیف باید عدد معتبر باشد.',
            'discount.min' => 'میزان تخفیف نمی‌تواند منفی باشد.',
            'discount.lte' => 'میزان تخفیف نمی‌تواند بیشتر از قیمت محصول باشد.',

            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required' => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',
            'page_title.max' => 'عنوان صفحه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'material.required' => 'وارد کردن جنس محصول الزامی است.',
            'material.max' => 'جنس محصول نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'weight.required' => 'وارد کردن وزن الزامی است.',
            'weight.max' => 'وزن نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

            'dimension.required' => 'وارد کردن ابعاد الزامی است.',
            'dimension.max' => 'ابعاد نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

        ]);
        $data = $request->all();


        $data['discount'] = $request->filled('discount') ? $request->input('discount') : null;
        Product::query()->create($data);
        return redirect()->route('admin.product.index')->with('productMessage','محصول با موفقیت افزوده شد.');
    }

    public function edit(Request $request, $id)
    {
        $product=Product::query()->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:products,slug,' . $id,
            ],


            'category_id' => 'required|exists:categories,id|not_in:0',

            'meta_description' => 'required|string',
            'page_title' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'weight' => 'required|string|max:100',
            'dimension' => 'required|string|max:100',
            'description' => 'required',
        ], [

            'name.required' => 'وارد کردن نام الزامی است.',
            'name.max' => 'نام محصول نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'slug.required' => 'وارد کردن اسلاگ الزامی است.',
            'slug.regex' => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z)و عدد باشد.  خط under line، فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',




            'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
            'category_id.exists' => 'دسته‌بندی انتخاب‌شده معتبر نیست.',
            'category_id.not_in' => 'لطفاً یک دسته‌بندی معتبر انتخاب کنید.',




            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required' => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',
            'page_title.max' => 'عنوان صفحه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'material.required' => 'وارد کردن جنس محصول الزامی است.',
            'material.max' => 'جنس محصول نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'weight.required' => 'وارد کردن وزن الزامی است.',
            'weight.max' => 'وزن نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

            'dimension.required' => 'وارد کردن ابعاد الزامی است.',
            'dimension.max' => 'ابعاد نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

        ]);
        $data = $request->all();



        $product->update($data);
        return redirect()->back()->with('productMessage','محصول با موفقیت ویرایش شد.');
    }
    public function indexColor(Product $product): JsonResponse
    {
        $colors = $product->colors()->select('id', 'name', 'code')->get();

        return response()->json($colors);
    }

    /**
     * ذخیره رنگ جدید
     */
    public function storeColor(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:50',
            'code'       => 'required|regex:/^#[0-9A-F]{6}$/i',
        ]);

        // جلوگیری از تکراری بودن رنگ برای همان محصول
        $exists = ProductColor::query()->where('product_id', $request->product_id)
            ->where('code', strtoupper($request->code))
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'این رنگ قبلاً برای محصول اضافه شده است.'
            ], 422);
        }

        $color = ProductColor::query()->create([
            'product_id' => $request->product_id,
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'رنگ با موفقیت اضافه شد.',
            'color'   => $color
        ]);
    }

    /**
     * حذف رنگ
     */
    public function destroyColor(ProductColor $color): JsonResponse
    {
        // اطمینان از اینکه رنگ متعلق به محصول درست است (امنیت اضافی)
        $color->delete();

        return response()->json([
            'success' => true,
            'message' => 'رنگ با موفقیت حذف شد.'
        ]);
    }
    public function indexSize(Product $product): JsonResponse
    {
        $sizes = $product->sizes()->select('id', 'name')->orderBy('id')->get();
        return response()->json($sizes);
    }

// ذخیره سایز جدید
    public function storeSize(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:50',
        ]);

        // جلوگیری از تکراری بودن سایز برای همان محصول
        $exists = ProductSize::query()->where('product_id', $request->product_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'این سایز قبلاً برای محصول اضافه شده است.'
            ], 422);
        }

        $size = ProductSize::query()->create([
            'product_id' => $request->product_id,
            'name'       => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'سایز با موفقیت اضافه شد.',
            'size'    => $size
        ]);
    }

// حذف سایز
    public function destroySize(ProductSize $size): JsonResponse
    {
        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'سایز با موفقیت حذف شد.'
        ]);
    }
    public function indexPhoto(Product $product): JsonResponse
    {
        $photos = $product->photos()->select('id', 'photo', 'photo_alt')->orderBy('id')->get();
        return response()->json($photos);
    }

// ذخیره عکس جدید در public/product
    public function storePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'slug'       => 'required|string',
            'photo'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'photo_alt'  => 'required|string',
        ]);

        $productId = $request->product_id;
        $slug = $request->slug;

        // ساخت نام فایل: slug-1.jpg, slug-2.jpg و ...
        $existingPhotos = ProductPhoto::query()->where('product_id', $productId)->count();
        $newIndex = $existingPhotos + 1;

        $extension = $request->photo->getClientOriginalExtension();
        $photoName = $slug . '-' . $newIndex . '.' . $extension;

        // ذخیره مستقیم در public/product
        $request->photo->storeAs('product', $photoName, 'public');

        $photo = ProductPhoto::query()->create([
            'product_id' => $productId,
            'photo'      => $photoName,
            'photo_alt'  => $request->photo_alt,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'عکس با موفقیت آپلود شد.',
            'photo'   => $photo
        ]);
    }

// حذف عکس (از دیتابیس + فایل فیزیکی)
    public function destroyPhoto(ProductPhoto $photo): JsonResponse
    {
        // حذف فایل از public/product
        $filePath = public_path('product/' . $photo->photo);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // حذف رکورد از دیتابیس
        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'عکس با موفقیت حذف شد.'
        ]);
    }
    // لیست تمام variants محصول
    public function indexVariant(Product $product): JsonResponse
    {

        $variants = $product->variants()
            ->with(['color:id,name,code', 'size:id,name'])
            ->get();

        return response()->json($variants);
    }
// ذخیره یا افزایش موجودی (اگر ترکیب وجود داشته باشه، count رو جمع می‌کنه)
    public function storeVariant(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id'   => 'required|exists:product_colors,id',
            'size_id'    => 'required|exists:product_sizes,id',
            'count'      => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lte:price',
        ]);

        $variant = ProductVariant::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'color_id'   => $request->color_id,
                'size_id'    => $request->size_id,
            ],
            [
                'count'    => $request->count,
                'price'    => $request->price,
                'discount' => $request->discount,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'موجودی با موفقیت افزوده شد.',
            'variant' => $variant->load(['color', 'size'])
        ]);
    }

// به‌روزرسانی مستقیم موجودی
    public function updateVariant(Request $request, ProductVariant $variant): JsonResponse
    {
        $request->validate([
            'count' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lte:price',
        ]);

        $variant->update([
            'count' => $request->count,
            'price'=>$request->price,
            'discount'=>$request->discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'موجودی به‌روزرسانی شد.'
        ]);
    }
    public function indexComment(Product $product): JsonResponse
    {
        $comments = $product->product_comments()->orderBy('created_at', 'desc')->paginate(1);
        return response()->json($comments);
    }

// افزودن کامنت
    public function storeComment(Request $request)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:100',
            'comment' => 'required|string',
            'admin_response' => 'nullable|string',
            'status' => 'required|in:1,2,3',
            'created_at'=> 'required'
        ]);
        $data=$request->all();
        $normalizedDate =$this->normalizePersianDate($request['created_at']); // 1377/07/22
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $normalizedDate)
            ->toCarbon()
            ->format('Y-m-d');
        $data['created_at']=$gregorianDate;

        ProductComment::create($data);

        return redirect()->back()->with('message', 'عملیات با موفقیت انجام شد.');
    }

// ویرایش پاسخ ادمین
    public function updateResponse(Request $request, Product $product, ProductComment $comment)
    {
        if ($comment->product_id !== $product->id) abort(403);

        $request->validate(['admin_response' => 'nullable|string']);
        $comment->update(['admin_response' => $request->admin_response]);

        return redirect()->back()->with('message', 'پاسخ ادمین ذخیره شد.');
    }

    public function destroyComment(Product $product, ProductComment $comment)
    {
        if ($comment->product_id !== $product->id) abort(403);

        $comment->delete();

        return redirect()->back()->with('message', 'کامنت حذف شد.');
    }

// تغییر وضعیت
    public function updateStatus(Request $request, Product $product, ProductComment $comment)
    {
        // چون کامنت متعلق به محصول هست، می‌تونی چک کنی (امنیت)
        if ($comment->product_id !== $product->id) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:1,2']);

        $comment->update(['status' => $request->status]);

        return redirect()->back()->with('message', 'وضعیت کامنت با موفقیت تغییر کرد.');
    }



    public function comments(Product $product)
    {
        $comments = $product->product_comments()->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.product.comments', compact('product', 'comments'));
    }

    public function productAddView()
    {
        $categories=Category::all();
        return view('admin.product.add',compact('categories'));
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
