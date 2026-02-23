<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryList()
    {
        $categories = Category::paginate(10);
        return view('admin.category.list',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:categories,slug',
            ],
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photo_alt'=>'required|string',
            'meta_description'=>'required|string',
            'page_title'=>'required|string',
        ], [
            'slug.regex'   => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z) و عدد باشد.  خط تیره، فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique'  => 'این اسلاگ قبلاً استفاده شده است.',
            'slug.required'=> 'وارد کردن اسلاگ الزامی است.',
            'name.required'=>'وارد کردن نام الزامی است.',
            'name.max'               => 'نام دسته‌بندی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'photo.required'         => 'انتخاب عکس برای دسته‌بندی الزامی است.',
            'photo.image'            => 'فایل انتخاب‌شده باید تصویر باشد.',
            'photo.mimes'            => 'فرمت عکس فقط jpeg، jpg، png یا webp مجاز است.',
            'photo.max'              => 'حجم عکس نباید بیشتر از ۲ مگابایت باشد.',

            'photo_alt.required'     => 'وارد کردن  (Alt) الزامی است.',


            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required'    => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',

        ]);

        $photoExtension = $request->photo->getClientOriginalExtension();
        $photoName = $request->slug . '-' . time() . '.' . $photoExtension;

        $request->photo->storeAs('category', $photoName, 'public');

        Category::create([
           'name'=>$request['name'],
           'slug'=>$request['slug'],
           'photo'=>$photoName,
           'photo_alt'=>$request['photo_alt'],
           'meta_description'=>$request['meta_description'],
           'page_title'=>$request['page_title'],
        ]);

        return redirect()->back()->with('categoryAdded','دسته بندی با موفقیت افزوده شد.');
    }

    public function edit(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:categories,slug,' . $id
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photo_alt'=>'required|string',
            'meta_description'=>'required|string',
            'page_title'=>'required|string',
        ], [
            'slug.regex'   => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z) و عدد باشد.  خط تیره، فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique'  => 'این اسلاگ قبلاً استفاده شده است.',
            'slug.required'=> 'وارد کردن اسلاگ الزامی است.',
            'name.required'=>'وارد کردن نام الزامی است.',
            'name.max'               => 'نام دسته‌بندی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'photo.image'            => 'فایل انتخاب‌شده باید تصویر باشد.',
            'photo.mimes'            => 'فرمت عکس فقط jpeg، jpg، png یا webp مجاز است.',
            'photo.max'              => 'حجم عکس نباید بیشتر از ۲ مگابایت باشد.',

            'photo_alt.required'     => 'وارد کردن  (Alt) الزامی است.',


            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required'    => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',

        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // حذف عکس قبلی
            if ($category->photo && file_exists(public_path('category/' . $category->photo))) {
                unlink(public_path('category/' . $category->photo));
            }
            $photoExtension = $request->photo->getClientOriginalExtension();
            $photoName = $request->slug . '-' . time() . '.' . $photoExtension;

            $request->photo->storeAs('category', $photoName, 'public');
            $data['photo'] = $photoName;
        }

        $category->update($data);

        return redirect()->route('admin.category.list')
            ->with('categoryAdded', 'دسته‌بندی با موفقیت ویرایش شد.');
    }
}
