<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs=Blog::paginate(10);
        return view('admin.blog.index',compact('blogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:blogs,slug',
            ],
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photo_alt'=>'required|string',
            'meta_description'=>'required|string',
            'page_title'=>'required|string',
            'description'=>'required|string',
        ], [
            'slug.regex'   => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z) باشد. عدد، خط تیره، فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique'  => 'این اسلاگ قبلاً استفاده شده است.',
            'slug.required'=> 'وارد کردن اسلاگ الزامی است.',
            'title.required'=>'وارد کردن عنوان مقاله الزامی است.',
            'title.max'               => 'نام دسته‌بندی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'photo.required'         => 'انتخاب عکس برای دسته‌بندی الزامی است.',
            'photo.image'            => 'فایل انتخاب‌شده باید تصویر باشد.',
            'photo.mimes'            => 'فرمت عکس فقط jpeg، jpg، png یا webp مجاز است.',
            'photo.max'              => 'حجم عکس نباید بیشتر از ۲ مگابایت باشد.',

            'photo_alt.required'     => 'وارد کردن  (Alt) الزامی است.',
            'description.required' =>'وارد کردن متن مقاله الزامی است.',

            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required'    => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',

        ]);

        $photoExtension = $request->photo->getClientOriginalExtension();
        $photoName = $request->slug .'.' . $photoExtension;
        $request->photo->storeAs('blog', $photoName, 'public');

        Blog::query()->create([
            'title'=>$request['title'],
            'slug'=>$request['slug'],
            'photo'=>$photoName,
            'photo_alt'=>$request['photo_alt'],
            'meta_description'=>$request['meta_description'],
            'page_title'=>$request['page_title'],
            'description'=>$request['description']
        ]);

        return redirect()->back()->with('blogMessage','مقاله با موفقیت افزوده شد.');
    }

    public function edit(Request $request, $id)
    {
        $blog = Blog::query()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'regex:/^[a-z0-9-]+$/',
                'unique:blogs,slug,' . $id
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photo_alt'=>'required|string',
            'meta_description'=>'required|string',
            'page_title'=>'required|string',
            'description'=>'required|string',
        ], [
            'slug.regex'   => 'اسلاگ فقط می‌تواند حروف انگلیسی کوچک (a-z) باشد. عدد، خط تیره، فاصله یا کاراکتر خاص مجاز نیست.',
            'slug.unique'  => 'این اسلاگ قبلاً استفاده شده است.',
            'slug.required'=> 'وارد کردن اسلاگ الزامی است.',
            'title.required'=>'وارد کردن نام الزامی است.',
            'title.max'               => 'نام دسته‌بندی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'photo.image'            => 'فایل انتخاب‌شده باید تصویر باشد.',
            'photo.mimes'            => 'فرمت عکس فقط jpeg، jpg، png یا webp مجاز است.',
            'photo.max'              => 'حجم عکس نباید بیشتر از ۲ مگابایت باشد.',

            'photo_alt.required'     => 'وارد کردن  (Alt) الزامی است.',
            'description.required' =>'وارد کردن متن مقاله الزامی است.',

            'meta_description.required' => 'وارد کردن توضیحات متا (Meta Description) الزامی است.',


            'page_title.required'    => 'وارد کردن عنوان صفحه (Page Title) الزامی است.',

        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // حذف عکس قبلی
            if ($blog->photo && file_exists(public_path('blog/' . $blog->photo))) {
                unlink(public_path('blog/' . $blog->photo));
            }
            $photoExtension = $request->photo->getClientOriginalExtension();
            $photoName = $request->slug .'.' . $photoExtension;
            $request->photo->storeAs('blog', $photoName, 'public');
            $data['photo'] = $photoName;
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')
            ->with('blogMessage', 'مقاله با موفقیت ویرایش شد.');
    }
}
