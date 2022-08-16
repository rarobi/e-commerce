<?php

namespace App\Modules\Product\Controllers\Frontend;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Product\Models\ProductSubcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{

    /**
     * @param Request $request
     * @param $subcategoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request,$subcategoryId)
    {
        $decodedSubcategoryId = Encryption::decodeId($subcategoryId);
        $data['subcategory'] = ProductSubcategory::find($decodedSubcategoryId);

        $data['subcategoryProducts'] = CommonFunction::productInfo()
            ->leftJoin('product_subcategories','product_subcategories.id','=','products.sub_category_id')
            ->where('products.is_archive', false)
            ->where('product_subcategories.id', $decodedSubcategoryId)
            ->select('products.*', 'product_categories.id as product_category_id', 'product_categories.name as product_category_name', 'photos.path as photo_path')
            ->orderBy('products.id', 'desc')
            ->groupBy('products.id')
            ->paginate(16);

        if ($request->ajax()) {
            $view = view("Product::frontend.subcategory.load-more",$data)->render();
            return response()->json(['html'=>$view]);
        }

        return view("Product::frontend.subcategory.index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //
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
    public function update(Request $request,$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
