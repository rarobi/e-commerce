<?php

namespace App\Modules\Product\Controllers\Backend;

use App\DataTables\Backend\Product\ProductBrandDataTable;
use App\Libraries\Encryption;
use App\Modules\Product\Models\ProductBrand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductBrandController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductBrandDataTable $dataTable)
    {
        return $dataTable->render("Product::backend.brands.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Product::backend.brands.create");
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
            'name' => ['required', Rule::unique('product_brands')->where(function ($query) { $query->where('is_archive', false); })],
            'website' => 'required',
            'photo' => 'required',
            'status' => 'required'
        ]);

        $productBrand = new ProductBrand();
        $productBrand->name = $request->input('name');
        $productBrand->website = strtolower($request->input('website'));
        $productBrand->status = $request->input('status');

        if($request->hasFile('photo')){
            $path = 'uploads/brand-photos/';
            $_brandPhoto = $request->file('photo');
            $mimeType = $_brandPhoto->getClientMimeType();

            if(!in_array($mimeType,['image/jpg', 'image/jpeg', 'image/png']))
                return redirect()->back()->with('flash_danger','Only PNG or JPEG or JPG type images are allowed!');

            if(!file_exists($path))
                mkdir($path, 0777, true);

            $brandPhoto = trim(sprintf('%s', uniqid('BrandPhoto_', true))) . '.' . $_brandPhoto->getClientOriginalExtension();
            Image::make($_brandPhoto->getRealPath())->resize(190,68)->save($path . '/' . $brandPhoto);
            $productBrand->photo = $brandPhoto;
        }

        $productBrand->save();
        return redirect(route('admin.product.brands.index'))->with('flash_success', 'Product brand created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $productBrandId
     * @return \Illuminate\Http\Response
     */
    public function show($productBrandId)
    {
        $decodedId = Encryption::decodeId($productBrandId);
        $data['productBrand'] = ProductBrand::find($decodedId);
        return view("Product::backend.brands.view", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $productBrandId
     * @return \Illuminate\Http\Response
     */
    public function edit($productBrandId)
    {
        $decodedId = Encryption::decodeId($productBrandId);
        $data['productBrand'] = ProductBrand::find($decodedId);

        return view("Product::backend.brands.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $productBrandId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productBrandId)
    {
        $decodedId = Encryption::decodeId($productBrandId);
        $this->validate($request, [
            'name' => ['required', Rule::unique('product_brands')->ignore($decodedId)->where(function ($query) { $query->where('is_archive', false); })],
            'website' => 'required',
            'photo' => 'required',
            'status' => 'required',
        ]);

        $productBrand = ProductBrand::find($decodedId);
        $productBrand->name = $request->input('name');
        $productBrand->website = strtolower($request->input('website'));
        $productBrand->status = $request->input('status');

        if($request->hasFile('photo')){
            $path = 'uploads/brand-photos/';
            $_productBrandPhoto = $request->file('photo');
            $mimeType = $_productBrandPhoto->getClientMimeType();

            if(!in_array($mimeType,['image/jpg', 'image/jpeg', 'image/png']))
                return redirect()->back()->with('flash_danger','Only PNG or JPEG or JPG type images are allowed!');

            if(!file_exists($path))
                mkdir($path, 0777, true);

            $previousExistingPhoto = $path.'/'.$productBrand->photo; // get previous photo from folder
            if (File::exists($previousExistingPhoto)) // unlink or remove previous photo from folder
                @unlink($previousExistingPhoto);

            $productBrandPhoto = trim(sprintf('%s', uniqid('BrandPhoto_', true))) . '.' . $_productBrandPhoto->getClientOriginalExtension();
            Image::make($_productBrandPhoto->getRealPath())->resize(190,68)->save($path . '/' . $productBrandPhoto);
            $productBrand->photo = $productBrandPhoto;
        }
        $productBrand->save();

        return redirect(route('admin.product.brands.index'))->with('flash_success', 'Product brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productBrandId
     * @return \Illuminate\Http\Response
     */
    public function delete($productBrandId)
    {
        $decodedId = Encryption::decodeId($productBrandId);
        $productBrand = ProductBrand::find($decodedId);
        $productBrand->is_archive = 1;
        $productBrand->deleted_by = auth()->user()->id;
        $productBrand->deleted_at = Carbon::now();
        $productBrand->save();
        session()->flash('flash_success', 'Product brand deleted successfully!');
    }
}
