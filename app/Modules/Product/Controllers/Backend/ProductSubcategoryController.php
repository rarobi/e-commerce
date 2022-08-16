<?php

namespace App\Modules\Product\Controllers\Backend;

use App\DataTables\Backend\Product\ProductSubcategoryDataTable;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Product\Models\ProductSubcategory;
use App\Libraries\Encryption;
use App\Libraries\CommonFunction;
use App\Modules\Settings\Models\Photo;
use App\Modules\Settings\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductSubcategoryController extends Controller
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
    public function index(ProductSubcategoryDataTable $dataTable)
    {
        return $dataTable->render("Product::backend.subcategories.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['categories'] = ProductCategory::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        return view("Product::backend.subcategories.create", $data);
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
            'name' => ['required', Rule::unique('product_subcategories')->where(function ($query) use ($request) { $query->where('category_id', $request->input('category_id'))->where('is_archive', false);})],
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $productSubcategory = new ProductSubcategory();
        $productSubcategory->name = $request->input('name');
        $productSubcategory->category_id = $request->input('category_id');
        $productSubcategory->slug = strtolower($request->input('name'));
        $productSubcategory->description = $request->input('description');
        $productSubcategory->status = $request->input('status');
        $productSubcategory->save();

        if($request->hasFile('photo')) {
            $this->referenceType = 'product_subcategory';
            $this->referenceId = $productSubcategory->id;
            $photos = $request->file('photo');
            $this->dimensions = [854,460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType,$this->referenceId,$this->photo,$this->dimensions);
        }

        return redirect(route('admin.product.subcategories.index'))->with('flash_success', 'Product subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $productSubcategoryId
     * @return \Illuminate\Http\Response
     */
    public function show($productSubcategoryId)
    {
        $decodedId = Encryption::decodeId($productSubcategoryId);
        $data['productSubcategory'] = ProductSubcategory::getProductSubcategoryInfo($decodedId);
        $data['photos'] = Photo::where('reference_id',$decodedId)
            ->where('reference_type','product_subcategory')
            ->where('is_archive',0)
            ->where('status',1)
            ->get();

        return view("Product::backend.subcategories.view", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $productSubcategoryId
     * @return \Illuminate\Http\Response
     */
    public function edit($productSubcategoryId)
    {
        $decodedId = Encryption::decodeId($productSubcategoryId);
        $data['categories'] = ProductCategory::where(['is_archive' => false, 'status' => true])->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['productSubcategory'] = ProductSubcategory::getProductSubCategoryInfo($decodedId);
        $data['photos'] = Photo::where('reference_id',$decodedId)
            ->where('reference_type','product_subcategory')
            ->where('is_archive',0)
            ->where('status',1)
            ->get();

        return view("Product::backend.subcategories.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $productSubcategoryId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productSubcategoryId)
    {
        $decodedId = Encryption::decodeId($productSubcategoryId);
        $this->validate($request, [
            'name' => ['required', Rule::unique('product_subcategories')->ignore($decodedId)->where(function ($query) use ($request) { $query->where(['category_id' => $request->input('category_id'),'is_archive'=> false]);})],
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);
        $productSubcategory = ProductSubcategory::find($decodedId);
        $productSubcategory->name = $request->input('name');
        $productSubcategory->category_id = $request->input('category_id');
        $productSubcategory->slug = strtolower($request->input('name'));
        $productSubcategory->description = $request->input('description');
        $productSubcategory->status = $request->input('status');
        $productSubcategory->save();

        if($request->hasFile('photo')) {
            $this->referenceType = 'product_subcategory';
            $this->referenceId = $productSubcategory->id;
            $photos = $request->file('photo');
            $this->dimensions = [854,460];
            foreach ($photos as $this->photo)
                Settings::StorePhoto($this->referenceType,$this->referenceId,$this->photo,$this->dimensions);
        }

        return redirect(route('admin.product.subcategories.index'))->with('flash_success', 'Product subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productSubcategoryId
     * @return \Illuminate\Http\Response
     */
    public function delete($productSubcategoryId)
    {
        $decodedId = Encryption::decodeId($productSubcategoryId);
        $productSubcategory = ProductSubcategory::find($decodedId);
        $productSubcategory->is_archive = 1;
        $productSubcategory->deleted_by = auth()->user()->id;
        $productSubcategory->deleted_at = Carbon::now();
        $productSubcategory->save();
        Photo::where('reference_type','product_subcategory')->where('reference_id',$decodedId)->update(['is_archive'=>1]);
        session()->flash('flash_success', 'Product subcategory deleted successfully!');
    }

    public function subcategoriesByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = ProductSubcategory::where('category_id', $categoryId)
            ->where(['is_archive' => false, 'status' => true])
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');
        $data = ['responseCode' => 1, 'data' => $subcategories];
        return response()->json($data);
    }
}
