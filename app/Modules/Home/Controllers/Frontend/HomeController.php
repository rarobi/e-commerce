<?php

namespace App\Modules\Home\Controllers\Frontend;

use App\Libraries\CommonFunction;
use App\Modules\Settings\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $data['products'] = CommonFunction::productInfo()
        ->select('products.*', 'product_categories.id as product_category_id', 'product_categories.name as product_category_name', 'photos.path as photo_path')
        ->orderBy('products.id', 'desc')
        ->groupBy('products.id')
        ->paginate(20);

        if ($request->ajax()) {
            $view = view("Home::frontend.load-more",$data)->render();
            return response()->json(['html'=>$view]);
        }

        $data['topProducts'] = CommonFunction::productInfo()
            ->where('products.status',1)
            ->where('products.is_archive',0)
            ->take(10)
            ->groupBy('products.id')
            ->orderBy('products.total_view','desc')
            ->get([
                'products.*',
                'product_categories.id as product_category_id',
                'product_categories.name as product_category_name',
                'photos.path as photo_path'
            ]);

        $data['sliders'] = Slider::take(5)->orderBy('id','desc')->get();

        $data['brands'] = CommonFunction::getBrandList();
        $data['topCategories'] = CommonFunction::getTopCategories();
        CommonFunction::setCompanyInfo();

        return view("Home::frontend.index",$data);
    }


    public function aboutUs(){
        
        dd("About Us");
    }

    public function contactUs(){
        dd("Contact Us");
    }

    public function blogs(){
        dd("Blogs");
    }

    public function wishList()
    {
        return view("Home::frontend.wish-list");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faqs()
    {
        return view("Home::frontend.faq");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function termCondition()
    {
        return view("Home::frontend.term-condition");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
