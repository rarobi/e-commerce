<?php

namespace App\Modules\Product\Controllers\Frontend;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productDetail($productId)
    {
        $decodedProductId = Encryption::decodeId($productId);
        $data['product'] = CommonFunction::getSingleProductInfo($decodedProductId);
        $data['productPhotos'] = CommonFunction::getSingleProductPhotos($decodedProductId);

        $data['relatedProducts'] = CommonFunction::productInfo()
            ->where('products.is_archive', 0)
            ->whereNotIn('products.id', [$decodedProductId])
            ->take(8)
            ->orderBy('products.id', 'desc')
            ->groupBy('products.id')
            ->get([
                'products.*',
                'product_categories.id as product_category_id',
                'product_categories.name as product_category_name',
                'photos.path as photo_path'
            ]);

        CommonFunction::countProductView($decodedProductId);
        return view("Product::frontend.product.product-detail", $data);
    }

    /**
     * @param $productId
     */
    public function quickView($productId)
    {
        $data['product'] = CommonFunction::getSingleProductInfo($productId);
        $data['productPhotos'] = CommonFunction::getSingleProductPhotos($productId);
        CommonFunction::countProductView($productId);

        $view = view("Product::frontend.quick-view.index",$data)->render();
        return response()->json(['html'=>$view]);

    }

    /**
     * @param Request $request
     */
    public function searchProducts(Request $request)
    {
        $searchKey = $request->input('search_key');
        $categoryId = $request->input('category_id');

        $query = CommonFunction::productInfo();

        if(!empty($categoryId))
            $query->where('products.category_id',$categoryId);

        if(!empty($searchKey))
            $query->where('products.name', $searchKey)->orWhere('products.name', 'like', '%' . $searchKey . '%');

        $data['searchProducts'] = $query->select('products.*', 'product_categories.id as product_category_id', 'product_categories.name as product_category_name', 'photos.path as photo_path')
            ->orderBy('products.id', 'desc')
            ->groupBy('products.id')
            ->paginate(20);

        if ($request->ajax()) {
            $view = view("Product::frontend.product.load-more",$data)->render();
            return response()->json(['html'=>$view]);
        }

        return view("Product::frontend.product.index",$data);
    }
}
