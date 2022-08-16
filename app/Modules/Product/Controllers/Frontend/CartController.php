<?php

namespace App\Modules\Product\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\Product\Models\Wishlist;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Throwable;

class CartController extends Controller
{

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view("Product::frontend.cart.index");
    }

    /**updateCartItem
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function addToCart(Request $request)
    {
        session(['cart' => $this->addCart($request)]);
        $data = [];
        $data['cart'] = session()->has('cart') ? session()->get('cart') : [];

        //Calculation
        $cartItem = $this->calculation($data);
        session(['totalItem' => $cartItem['total_item']]);
        session(['totalAmount' => $cartItem['total_amount']]);

        $view = view("Product::frontend.cart.add-cart", $data)->render();
        return response()->json(['status' => 'true', 'html' => $view, 'totalItem' => $cartItem['total_item'], 'totalAmount' => $cartItem['total_amount']]);
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function addCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = CommonFunction::productInfo()
            ->where('products.id', $productId)
            ->groupBy('products.id')
            ->first([
                'products.*',
                'photos.path as photo_path'
            ]);

        $cart = session()->has('cart') ? session()->get('cart') : [];
        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id]['quantity'] += $quantity;
            $cart[$product->id]['tax'] += $product->tax * $quantity;
        } else {
            $cart[$product->id] = [
                'title' => $product->title,
                'name' => $product->name,
                'quantity' => $quantity,
                'photo' => $product->photo_path,
                'price' => $product->price,
                'discount' => $product->discount,
                'tax' => $product->tax
            ];
        }

        return $cart;
    }

    /**
     * @param $data
     */
    public function calculation($data)
    {
        $totalItem = 0;
        $tax = 0;
        $totalAmount = 0;
        foreach ($data['cart'] as $cartItemId => $cartItem) {
            $totalItem += $cartItem['quantity'];
            $tax += ($cartItem['tax']) ? $cartItem['tax'] : 0;
            $totalAmount += ($cartItem['discount']) ? ($cartItem['quantity'] * ($cartItem['price'] - ($cartItem['price'] * ($cartItem['discount'] / 100)))) : ($cartItem['price'] * $cartItem['quantity']);
        }

        return [
            'total_amount' => $totalAmount + $tax,
            'tax' => $tax,
            'total_item' => $totalItem
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function addToCartFromWishlist(Request $request)
    {
        Wishlist::where('id', $request->input('wishlist_id'))->delete();

        session(['cart' => $this->addCart($request)]);
        $data = [];
        $data['cart'] = session()->has('cart') ? session()->get('cart') : [];

        //Calculation
        $cartItem = $this->calculation($data);
        session(['totalItem' => $cartItem['total_item']]);
        session(['totalAmount' => $cartItem['total_amount']]);

        $totalWishlistItem = CommonFunction::authCustomerWishlistItem();
        $data['wishlistItems'] = CommonFunction::getWishlistInfo();
        $cartView = view("Product::frontend.cart.add-cart", $data)->render();
        $wishlistView = view("Product::frontend.wishlist.wishlist-item", $data)->render();

        return response()->json([
            'status' => true,
            'cart_html' => $cartView,
            'wishlist_html' => $wishlistView,
            'totalItem' => $cartItem['total_item'],
            'totalAmount' => $cartItem['total_amount'],
            'totalWishlistItem' => $totalWishlistItem
        ]);
    }

    /**
     * @param Request $productId
     */
    public function removeItem($productId)
    {
        $cart = Session::get('cart');
        unset($cart[$productId]);
        session(['cart' => $cart]);
        $data['cart'] = session()->get('cart');

        //Calculation
        $cartItem = $this->calculation($data);
        session(['totalItem' => $cartItem['total_item']]);
        session(['totalAmount' => $cartItem['total_amount']]);

        $view = view("Product::frontend.cart.add-cart", $data)->render();
        $cartView = view("Product::frontend.cart.update-cart", $data)->render();
        return response()->json(['html' => $view, 'cart_html' => $cartView, 'totalItem' => $cartItem['total_item'], 'totalAmount' => $cartItem['total_amount']]);
    }

    /**
     * @param Request $request
     */
    public function updateCartItem(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = CommonFunction::productInfo()
            ->where('products.id', $productId)
            ->groupBy('products.id')
            ->first([
                'products.*',
                'photos.path as photo_path'
            ]);

        $cart = session()->has('cart') ? session()->get('cart') : [];
        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id]['quantity'] = $quantity;
            $cart[$product->id]['tax'] = $product->tax * $quantity;
        }

        session(['cart' => $cart]);

        $data['cart'] = $cart;

        //Calculation
        $cartItem = $this->calculation($data);
        session(['totalItem' => $cartItem['total_item']]);
        session(['totalAmount' => $cartItem['total_amount']]);

        $view = view("Product::frontend.cart.add-cart", $data)->render();
        $cartView = view("Product::frontend.cart.update-cart", $data)->render();
        return response()->json(['html' => $view, 'cart_html' => $cartView, 'totalItem' => $cartItem['total_item'], 'totalAmount' => $cartItem['total_amount']]);
    }

}
