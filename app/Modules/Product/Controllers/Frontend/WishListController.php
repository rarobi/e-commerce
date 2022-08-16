<?php

namespace App\Modules\Product\Controllers\Frontend;
use App\Libraries\CommonFunction;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Wishlist;
use App\Http\Controllers\Controller;

class WishListController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(!auth()->user())
            return redirect('/login');

        $data['wishlistItems'] = CommonFunction::getWishlistInfo();
        return view("Product::frontend.wishlist.index",$data);
    }

    /**
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToWishList($productId)
    {
        $userId = auth()->user()->id;
        $product = Product::find($productId);
        $existRecord = Wishlist::where(['user_id' => $userId, 'product_id'=>$productId])->exists();

        if($existRecord)
            return response()->json([ 'success' => false, 'status' => "{$product->name} already added to wishlist."]);

        $wishlist = new Wishlist();
        $wishlist->user_id = $userId;
        $wishlist->product_id = $productId;
        $wishlist->status = 1;
        $wishlist->save();

        $totalWishlistItem = CommonFunction::authCustomerWishlistItem();
        return response()->json([
            'success' => true,
            'status' => "{$product->name} added to wishlist successfully.",
            'totalWishlistItem' => $totalWishlistItem
            ]);
    }

    public function removeWishlistItem($wishlistId){
        Wishlist::where('id',$wishlistId)->delete();
        $data['wishlistItems'] = CommonFunction::getWishlistInfo();
        $totalWishlistItem = CommonFunction::authCustomerWishlistItem();
        $view = view("Product::frontend.wishlist.wishlist-item",$data)->render();
        return response()->json(['html' => $view,'totalWishlistItem' => $totalWishlistItem]);
    }
}
