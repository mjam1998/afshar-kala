<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SendController;
use Illuminate\Support\Facades\Route;


Route::get('/',[HomeController::class,'index'])->name('home.index');
Route::get('/login',[HomeController::class,'login'])->name('login');
Route::post('login/post',[HomeController::class,'loginPost'])->name('login.post');
Route::get('/category/{slug}', [HomeController::class, 'showCategory'])->name('front.category.show');
Route::get('/product/{slug}', [HomeController::class, 'showProduct'])->name('front.product.show');
Route::post('/product/check-stock', [HomeController::class, 'checkStock'])->name('product.check-stock');
Route::post('/product/{product}/comment', [HomeController::class, 'storeComment'])
    ->name('front.product.comment.store')
    ->middleware('throttle:6,480');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/dropdown', [HomeController::class, 'cartDropdown'])->name('cart.dropdown');
Route::get('/blogs', [HomeController::class, 'showArticles'])->name('front.articles.show');
Route::get('/blog/{slug}', [HomeController::class, 'showBlog'])->name('front.article.show');
Route::get('/search', [HomeController::class, 'search'])->name('front.search');
Route::get('/cart/list', [HomeController::class, 'cartList'])->name('front.cart.list');
Route::post('/cart/update', [HomeController::class, 'cartUpdate'])->name('front.cart.update');
Route::post('/cart/remove/{key}', [HomeController::class, 'cartRemove'])->name('front.cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/pay/submit', [CheckoutController::class, 'paySubmit'])->name('checkout.pay.submit');
Route::get('/checkout/pay/callback', [CheckoutController::class, 'payCallback'])->name('checkout.pay.callback');
Route::get('/payment/result/{track}', [CheckoutController::class, 'paymentResult'])->name('checkout.payment.result');
Route::get('/order/track', [HomeController::class, 'showForm'])->name('order.track.form');
Route::post('/order/track', [HomeController::class, 'track'])->name('order.track.result');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('front.about.us');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('front.contact.us');
Route::get('/rules', [HomeController::class, 'rules'])->name('front.rules');
Route::post('/cart/apply-offer', [OffersController::class, 'applyOffer'])->name('cart.apply.offer');
Route::post('/cart/remove-offer', [OffersController::class, 'removeOffer'])->name('cart.remove.offer');

Route::prefix('/admin')->middleware('auth')->group(function(){
    Route::get('/index',[AdminController::class,'index'])->name('admin.index');
    Route::post('banner/hero/{heroBanner}', [BannerController::class, 'updateHeroBanner'])
        ->name('admin.banner.hero.update');
    Route::get('/list',[AdminController::class,'adminList'])->name('admin.list');
    Route::get('/add/view',[AdminController::class,'adminAddView'])->name('admin.add.view');
    Route::post('/add/post',[AdminController::class,'adminAddPost'])->name('admin.add.post');
    Route::get('/edit/view/{id}',[AdminController::class,'adminEdit'])->name('admin.edit,view');
    Route::put('/edit/post',[AdminController::class,'adminEditPost'])->name('admin.edit.post');
    Route::delete('/delete/{id}',[AdminController::class,'adminDelete'])->name('admin.delete');
    Route::post('/restore/{id}',[AdminController::class,'adminRestore'])->name('admin.restore');
    Route::post('/change/sms',[AdminController::class,'adminChangeSms'])->name('admin.change.sms');

    Route::get('/category/list',[CategoryController::class,'categoryList'])->name('admin.category.list');
    Route::post('/category/store',[CategoryController::class,'store'])->name('admin.category.store');
    Route::put('/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');

    Route::get('/product',[ProductController::class,'index'])->name('admin.product.index');
    Route::get('/product/add/view',[ProductController::class,'productAddView'])->name('admin.product.add.view');
    Route::post('/product/store',[ProductController::class,'store'])->name('admin.product.store');
    Route::put('/product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::get('/product/{product}/colors', [ProductController::class, 'indexColor'])->name('admin.product.colors.index');
    Route::post('/product/color/store', [ProductController::class, 'storeColor'])->name('admin.product.color.store');
    Route::delete('/product/color/{color}', [ProductController::class, 'destroyColor'])->name('admin.product.color.destroy');
    Route::get('/product/{product}/sizes', [ProductController::class, 'indexSize'])->name('admin.product.sizes.index');
    Route::post('/product/size/store', [ProductController::class, 'storeSize'])->name('admin.product.size.store');
    Route::delete('/product/size/{size}', [ProductController::class, 'destroySize'])->name('admin.product.size.destroy');
    Route::get('/product/{product}/photos', [ProductController::class, 'indexPhoto'])->name('admin.product.photos.index');
    Route::post('/product/photo/store', [ProductController::class, 'storePhoto'])->name('admin.product.photo.store');
    Route::delete('/product/photo/{photo}', [ProductController::class, 'destroyPhoto'])->name('admin.product.photo.destroy');
    Route::get('/product/{product}/variants', [ProductController::class, 'indexVariant'])->name('admin.product.variants.index');
    Route::post('/product/variant/store', [ProductController::class, 'storeVariant'])->name('admin.product.variant.store');
    Route::put('/product/variant/{variant}', [ProductController::class, 'updateVariant'])->name('admin.product.variant.update');
    Route::prefix('product/{product}')->group(function () {
        Route::get('/comments', [ProductController::class, 'comments'])->name('admin.product.comments');
        Route::post('/comment/store', [ProductController::class, 'storeComment'])->name('admin.product.comment.store');
        Route::put('/comment/{comment}/response', [ProductController::class, 'updateResponse'])->name('admin.product.comment.response');
        Route::put('/comment/{comment}/status', [ProductController::class, 'updateStatus'])->name('admin.product.comment.status');
        Route::delete('/comment/{comment}', [ProductController::class, 'destroyComment'])->name('admin.product.comment.destroy');
    });

    Route::get('/blog/index',[BlogController::class,'index'])->name('admin.blog.index');
    Route::post('/blog/store',[BlogController::class,'store'])->name('admin.blog.store');
    Route::put('/blog/edit/{id}', [BlogController::class, 'edit'])->name('admin.blog.edit');

    Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner.index');
    Route::post('/banner/video', [BannerController::class, 'updateVideo'])->name('admin.banner.video.update');
    Route::post('/banner/photo/{photoBanner}', [BannerController::class, 'updatePhoto'])->name('admin.banner.photo.update');

    Route::get('/send/list',[SendController::class,'index'])->name('admin.send.list');
    Route::post('/send/store',[SendController::class,'store'])->name('admin.send.store');
    Route::put('/send/edit/{id}', [SendController::class, 'edit'])->name('admin.send.edit');

    Route::get('/orders/list', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.order.update-status');

    Route::get('/offers',[OffersController::class,'index'])->name('admin.offers.index');
    Route::post('/offers/store',[OffersController::class,'store'])->name('admin.offers.store');
    Route::put('/offers/edit/{id}', [OffersController::class, 'edit'])->name('admin.offers.edit');

    Route::post('logout',[AdminController::class,'logout'])->name('logout');
});
