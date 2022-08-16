<?php

namespace App\Modules\Product\Controllers\Backend;

use App\DataTables\Backend\Product\ProductCategoryDataTable;
use App\Modules\Product\Models\ProductCategory;
use App\Libraries\Encryption;
use App\Modules\Settings\Models\Photo;
use App\Modules\Settings\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
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
    public function index(ProductCategoryDataTable $dataTable){
        return $dataTable->render("Product::backend.categories.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view("Product::backend.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $this->validate($request, [
            'name' => ['required', Rule::unique('product_categories')->where(function ($query) { $query->where('is_archive', false);})],
            'description' => 'required',
            'status' => 'required'
        ]);

        $productCategory = new ProductCategory();
        $productCategory->name = $request->input('name');
        $productCategory->slug = strtolower($request->input('name'));
        $productCategory->description = $request->input('description');
        $productCategory->status = $request->input('status');
        $productCategory->save();

        if($request->hasFile('photo')) {
            $this->referenceType = 'product_category';
            $this->referenceId = $productCategory->id;
            $photos = $request->file('photo');
            $this->dimensions = [854,460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType,$this->referenceId,$this->photo,$this->dimensions);
        }

        return redirect(route('admin.product.categories.index'))->with('flash_success', 'Product category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $productCategoryId
     * @return \Illuminate\Http\Response
     */
    public function show($productCategoryId)
    {
        $decodedId = Encryption::decodeId($productCategoryId);
        $data['productCategory'] = ProductCategory::find($decodedId);
        $data['photos'] = Photo::where('reference_id',$decodedId)
            ->where('reference_type','product_category')
            ->where('is_archive',0)
            ->where('status',1)
            ->get();

        return view("Product::backend.categories.view", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $productCategoryId
     * @return \Illuminate\Http\Response
     */
    public function edit($productCategoryId)
    {
        $decodedId = Encryption::decodeId($productCategoryId);
        $data['productCategory'] = ProductCategory::find($decodedId);
        $data['photos'] = Photo::where('reference_id',$decodedId)
            ->where('reference_type','product_category')
            ->where('is_archive',0)
            ->where('status',1)
            ->get();

        return view("Product::backend.categories.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $ProductCategoryId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productCategoryId)
    {
        $decodedId = Encryption::decodeId($productCategoryId);
        $this->validate($request, [
            'name' => ['required', Rule::unique('product_categories')->ignore($decodedId)->where(function ($query) { $query->where('is_archive', false);})],
            'description' => 'required',
            'status' => 'required'
        ]);

        $productCategory = ProductCategory::find($decodedId);
        $productCategory->name = $request->input('name');
        $productCategory->slug = strtolower($request->input('name'));
        $productCategory->description = $request->input('description');
        $productCategory->status = $request->input('status');
        $productCategory->save();

        if($request->hasFile('photo')) {
            $this->referenceType = 'product_category';
            $this->referenceId = $productCategory->id;
            $photos = $request->file('photo');
            $this->dimensions = [854,460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType,$this->referenceId,$this->photo,$this->dimensions);
        }

        return redirect(route('admin.product.categories.index'))->with('flash_success', 'Product category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $ProductCategoryId
     * @return \Illuminate\Http\Response
     */
    public function delete($productCategoryId)
    {
        $decodedId = Encryption::decodeId($productCategoryId);
        $productCategory = ProductCategory::find($decodedId);
        $productCategory->is_archive = 1;
        $productCategory->deleted_by = auth()->user()->id;
        $productCategory->deleted_at = Carbon::now();
        $productCategory->save();
        Photo::where('reference_type','product_category')->where('reference_id',$decodedId)->update(['is_archive'=>1]);
        session()->flash('flash_success', 'Product category deleted successfully!');
    }
}
