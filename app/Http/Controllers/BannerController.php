<?php

namespace App\Http\Controllers;

use App\Models\PhotoBanner;
use App\Models\VideoBanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $videoBanner = VideoBanner::first();
        $heroBanners = VideoBanner::orderBy('sort')->limit(4)->get();
        $photoBanners = PhotoBanner::orderBy('id')->limit(5)->get();

        return view('admin.banner.index', compact('videoBanner', 'photoBanners', 'heroBanners'));
    }

    public function updateVideo(Request $request)
    {
       $data= $request->validate([

            'meta_description' => 'required|string',
            'page_title' => 'required|string|max:255',
        ],[

            'meta_description.required' => 'وارد کردن متا دیسکریپشن الزامی است.',
            'meta_description.string' => 'متا دیسکریپشن باید از نوع متن باشد.',

            'page_title.required' => 'وارد کردن عنوان صفحه الزامی است.',
            'page_title.string' => 'عنوان صفحه باید از نوع متن باشد.',
            'page_title.max' => 'عنوان صفحه نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
        ]);

        $videoBanner = VideoBanner::firstOrCreate([]);





        $videoBanner->update($data);

        return redirect()->back()->with('success', 'تنظیمات صفحه اصلی با موفقیت بروزرسانی شد.');
    }
    /**
     * آپدیت بنرهای اسلایدر هیرو (sort 1 تا 4)
     */
    public function updateHeroBanner(Request $request, VideoBanner $heroBanner)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5000',
            'photo_alt' => 'required|string|max:255',
            'link' => 'nullable|url',
        ], [
            'photo.image' => 'فایل باید یک تصویر باشد.',
            'photo.mimes' => 'فرمت تصویر باید jpeg، png، jpg، gif یا webp باشد.',
            'photo.max' => 'حجم تصویر نمی‌تواند بیشتر از ۵ مگابایت باشد.',
            'photo_alt.required' => 'وارد کردن متن جایگزین تصویر الزامی است.',
            'link.url' => 'لینک وارد شده معتبر نیست.',
        ]);

        $data = $request->only(['photo_alt', 'link']);

        if ($request->hasFile('photo')) {
            if ($heroBanner->photo && file_exists(public_path('video/' . $heroBanner->photo))) {
                unlink(public_path('video/' . $heroBanner->photo));
            }
            $photoExtension = $request->photo->getClientOriginalExtension();
            $photoName = 'hero_banner_' . $heroBanner->sort . '_' . time() . '.' . $photoExtension;
            $request->photo->move(public_path('video'), $photoName);
            $data['photo'] = $photoName;
        }

        $heroBanner->update($data);

        return redirect()->back()->with('success', 'اسلاید شماره ' . $heroBanner->sort . ' با موفقیت بروزرسانی شد.');
    }

    public function updatePhoto(Request $request, PhotoBanner $photoBanner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'photo_alt' => 'required|string',
            'link' => 'nullable|url',
        ],[
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.string' => 'عنوان باید از نوع متن باشد.',
            'title.max' => 'عنوان نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'description.required' => 'وارد کردن توضیحات الزامی است.',
            'description.string' => 'توضیحات باید از نوع متن باشد.',

            'photo.image' => 'فایل عکس باید یک تصویر باشد.',
            'photo.mimes' => 'فرمت عکس باید یکی از jpeg، png، jpg یا gif باشد.',
            'photo.max' => 'حجم عکس نمی‌تواند بیشتر از ۴ مگابایت باشد.',

            'photo_alt.required' => 'وارد کردن متن جایگزین عکس الزامی است.',
            'photo_alt.string' => 'متن جایگزین عکس باید از نوع متن باشد.',

            'link.url' => 'لینک وارد شده معتبر نیست.',
        ]);

        $data = $request->only(['title', 'description', 'photo_alt', 'link']);

        if ($request->hasFile('photo')) {
            if ($photoBanner->photo && file_exists(public_path('banner/' . $photoBanner->photo))) {
                unlink(public_path('banner/' . $photoBanner->photo));
            }
            $photoExtension = $request->photo->getClientOriginalExtension();
            $photoName = 'banner'.time() .'.' . $photoExtension;
            $request->photo->storeAs('banner', $photoName, 'public');
            $data['photo'] = $photoName;
        }

        $photoBanner->update($data);

        return redirect()->back()->with('success', 'بنر عکس با موفقیت بروزرسانی شد.');
    }
}
