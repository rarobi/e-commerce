<?php

namespace App\Modules\Product\Controllers\Backend;

use App\DataTables\Backend\Product\ProductSkuDataTable;
use App\Modules\Product\Models\ProductSku;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductSkuController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductSkuDataTable $dataTable)
    {
        return $dataTable->render("Product::backend.skus.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Product::backend.skus.create");
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
            'name' => ['required', Rule::unique('product_skus')->where(function ($query) {
                $query->where('is_archive', false);
            })],
            'status' => 'required',
        ]);

        $productSku = new ProductSku();
        $productSku->name = $request->input('name');
        $productSku->status = $request->input('status');
        $productSku->save();
        return redirect(route('admin.product.skus.index'))->with('flash_success', 'Product sku created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $productSkuId
     * @return \Illuminate\Http\Response
     */
    public function show($productSkuId)
    {
        $decodedId = Encryption::decodeId($productSkuId);
        $data['productSku'] = ProductSku::find($decodedId);
        return view("Product::backend.skus.view", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $productSkuId
     * @return \Illuminate\Http\Response
     */
    public function edit($productSkuId)
    {
        $decodedId = Encryption::decodeId($productSkuId);
        $data['productSku'] = ProductSku::find($decodedId);
        return view("Product::backend.skus.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $productSkuId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productSkuId)
    {
        $decodedId = Encryption::decodeId($productSkuId);
        $this->validate($request, [
            'name' => ['required', Rule::unique('product_skus')->ignore($decodedId)->where(function ($query) {
                $query->where('is_archive', false);
            })],
            'status' => 'required',
        ]);

        $productSku = ProductSku::find($decodedId);
        $productSku->name = $request->input('name');
        $productSku->status = $request->input('status');
        $productSku->save();
        return redirect(route('admin.product.skus.index'))->with('flash_success', 'Product sku updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productSkuId
     * @return \Illuminate\Http\Response
     */
    public function delete($productSkuId)
    {
        $decodedId = Encryption::decodeId($productSkuId);
        $productSku = ProductSku::find($decodedId);
        $productSku->is_archive = 1;
        $productSku->deleted_by = auth()->user()->id;
        $productSku->deleted_at = Carbon::now();
        $productSku->save();
        session()->flash('flash_success', 'Product sku deleted successfully!');
    }
}
