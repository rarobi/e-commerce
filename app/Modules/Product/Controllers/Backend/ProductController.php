<?php

namespace App\Modules\Product\Controllers\Backend;

use App\DataTables\Backend\Product\ProductDataTable;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Product\Models\ProductSubcategory;
use App\Modules\Product\Models\ProductBrand;
use App\Modules\Product\Models\ProductSku;
use App\Libraries\Encryption;
use App\Modules\Settings\Models\Photo;
use App\Modules\Settings\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $referenceType;
    protected $referenceId;
    protected $photo;
    protected $dimensions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render("Product::backend.products.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = ProductCategory::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['subCategories'] = ProductSubcategory::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['brands'] = ProductBrand::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['skus'] = ProductSku::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        return view("Product::backend.products.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('products')->where(function ($query) use ($request) {
                $query->where([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'brand_id' => $request->input('brand_id'),
                'sku_id' => $request->input('sku_id'),
                'is_archive' => false
                ]);
            })],
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'sku_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'size' => 'required',
            'color' => 'required',
            'weight' => 'required',
            'tax' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'is_feature_product' => 'required',
            'status' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->category_id = $request->input('category_id');
        $product->sub_category_id = $request->input('sub_category_id');
        $product->brand_id = $request->input('brand_id');
        $product->sku_id = $request->input('sku_id');
        $product->title = $request->input('title');
        $product->slug = $request->input('slug');
        $product->price = $request->input('price');
        $product->size = $request->input('size');
        $product->color = $request->input('color');
        $product->weight = $request->input('weight');
        $product->tax = $request->input('tax');
        $product->short_description = $request->input('short_description');
        $product->long_description = $request->input('long_description');
        $product->is_feature_product = ($request->input('is_feature_product'))? 1 : 0;
        $product->status = $request->input('status');
        $product->save();

        if ($request->hasFile('photo')) {
            $this->referenceType = 'product';
            $this->referenceId = $product->id;
            $photos = $request->file('photo');
            $this->dimensions = [854, 460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType, $this->referenceId, $this->photo, $this->dimensions);
        }

        return redirect(route('admin.products.index'))->with('flash_success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        $decodedId = Encryption::decodeId($productId);
        $data['product'] = Product::getProductInfo($decodedId);
        $data['photos'] = Photo::where('reference_id', $decodedId)
            ->where('reference_type', 'product')
            ->where('is_archive', 0)
            ->where('status', 1)
            ->get();

        return view("Product::backend.products.view", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function edit($productId)
    {
        $decodedId = Encryption::decodeId($productId);
        $data['product'] = Product::find($decodedId);
        $data['photos'] = Photo::where('reference_id', $decodedId)
            ->where('reference_type', 'product')
            ->where('is_archive', 0)
            ->where('status', 1)
            ->get();

        $data['categories'] = ProductCategory::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['subCategories'] = ProductSubcategory::where(['is_archive' => false, 'status' => true, 'category_id' => $data['product']->category_id])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['brands'] = ProductBrand::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['skus'] = ProductSku::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        return view("Product::backend.products.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId)
    {
        $decodedId = Encryption::decodeId($productId);
        $this->validate($request, [
            'name' => ['required', Rule::unique('products')->ignore($decodedId)->where(function ($query) use ($request) {
                $query->where([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'brand_id' => $request->input('brand_id'),
                'sku_id' => $request->input('sku_id'),
                'is_archive' => false
                ]);
            })],
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'sku_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'size' => 'required',
            'color' => 'required',
            'weight' => 'required',
            'tax' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'is_feature_product' => 'required',
            'status' => 'required'
        ]);

        $product = Product::find($decodedId);
        $product->name = $request->input('name');
        $product->category_id = $request->input('category_id');
        $product->sub_category_id = $request->input('sub_category_id');
        $product->brand_id = $request->input('brand_id');
        $product->sku_id = $request->input('sku_id');
        $product->title = $request->input('title');
        $product->slug = $request->input('slug');
        $product->price = $request->input('price');
        $product->size = $request->input('size');
        $product->color = $request->input('color');
        $product->weight = $request->input('weight');
        $product->tax = $request->input('tax');
        $product->short_description = $request->input('short_description');
        $product->long_description = $request->input('long_description');
        $product->is_feature_product = $request->input('is_feature_product');
        $product->status = $request->input('status');
        $product->save();

        if ($request->hasFile('photo')) {
            $this->referenceType = 'product';
            $this->referenceId = $product->id;
            $photos = $request->file('photo');
            $this->dimensions = [854, 460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType, $this->referenceId, $this->photo, $this->dimensions);
        }

        return redirect(route('admin.products.index'))->with('flash_success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function delete($productId)
    {
        $decodedId = Encryption::decodeId($productId);
        $product = Product::find($decodedId);
        $product->is_archive = 1;
        $product->deleted_by = auth()->user()->id;
        $product->deleted_at = Carbon::now();
        $product->save();

        Photo::where('reference_type','product')->where('reference_id',$decodedId)->update(['is_archive'=>1]);
        session()->flash('flash_success', 'Product deleted successfully!');
    }
}
