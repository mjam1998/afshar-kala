<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class TorobController extends Controller
{
    const PER_PAGE = 50;

    /**
     * لیست محصولات - GET /torob/products?page=0
     */
    public function products(Request $request)
    {
        $page = (int) $request->get('page', 0);

        $products = Product::with([
            'category',
            'photos',
            'variants.size',
            'variants.color',
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(self::PER_PAGE, ['*'], 'page', $page + 1);

        $result = [];

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                if ($variant->price <= 0) continue;

                $finalPrice = $variant->price;
                $oldPrice   = null;

                if ($variant->discount && $variant->discount > 0) {
                    $oldPrice   = $variant->price;
                    $finalPrice = $variant->price - $variant->discount;
                }

                // مسیر درست عکس
                $firstPhoto = $product->photos->first();
                $imageUrl = $firstPhoto
                    ? env('APP_URL') . '/product/' . $firstPhoto->photo
                    : null;

                $result[] = [
                    'torob_id'     => $product->id . '-' . $variant->id,
                    'title'        => $product->name . ' - ' . $variant->color->name . ' - ' . $variant->size->name,
                    'price'     => (int) $finalPrice * 10,
                    'old_price' => $oldPrice ? (int) $oldPrice * 10 : null,
                    'availability' => $variant->count > 0,
                    'image_link'   => $imageUrl,
                    'product_link' => route('front.product.show', $product->slug),
                    'category'     => $product->category->name ?? '',
                    'brand'        => '',
                    'sku'          => $product->slug . '-' . $variant->id,
                    'description' => $this->cleanDescription($product->description),

                ];
            }
        }

        return response()->json([
            'count'     => $products->total(),
            'num_pages' => $products->lastPage(),
            'products'  => $result,
        ]);
    }

    /**
     * جزئیات یک محصول - GET /torob/product/{id}
     */
    public function product(string $id)
    {
        [$productId, $variantId] = explode('-', $id);

        $product = Product::with([
            'category',
            'photos',
            'variants.size',
            'variants.color',
        ])->findOrFail($productId);

        $variant = $product->variants->where('id', $variantId)->first();

        if (!$variant) {
            return response()->json(['error' => 'not found'], 404);
        }

        $finalPrice = $variant->price;
        $oldPrice   = null;

        if ($variant->discount && $variant->discount > 0) {
            $oldPrice   = $variant->price;
            $finalPrice = $variant->price - $variant->discount;
        }

        // مسیر درست عکس
        $firstPhoto = $product->photos->first();
        $imageUrl = $firstPhoto
            ? env('APP_URL') . '/product/' . $firstPhoto->photo
            : null;

        return response()->json([
            'torob_id'     => $product->id . '-' . $variant->id,
            'title'        => $product->name . ' - ' . $variant->color->name . ' - ' . $variant->size->name,
            'price'     => (int) $finalPrice * 10,
            'old_price' => $oldPrice ? (int) $oldPrice * 10 : null,
            'availability' => $variant->count > 0,
            'image_link'   => $imageUrl,
            'product_link' => route('front.product.show', $product->slug),
            'category'     => $product->category->name ?? '',
            'brand'        => '',
            'sku'          => $product->slug . '-' . $variant->id,
            'description' => $this->cleanDescription($product->description),

        ]);
    }
    private function cleanDescription(?string $text): string
    {
        if (!$text) return '';

        // 1. حذف &nbsp; های literal
        $text = str_replace('&nbsp;', ' ', $text);

        // 2. تبدیل <br> و <p> به فاصله (قبل از strip_tags)
        $text = preg_replace('/<br\s*\/?>/i', ' ', $text);
        $text = preg_replace('/<\/p>/i', ' ', $text);
        $text = preg_replace('/<\/li>/i', ' ', $text);

        // 3. حذف تمام تگ‌های HTML
        $text = strip_tags($text);

        // 4. decode کردن HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 5. حذف U+00A0 یونیکد
        $text = str_replace("\u{00A0}", ' ', $text);

        // 6. تبدیل \n و \r و \t به فاصله
        $text = str_replace(["\n", "\r", "\t"], ' ', $text);

        // 7. حذف فاصله‌های اضافی
        $text = preg_replace('/\s+/', ' ', $text);

        // 8. trim کامل
        return trim($text, " \t\n\r\0\x0B\u{00A0}");
    }



}
