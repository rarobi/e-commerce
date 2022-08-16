<?php

namespace App\Modules\Settings\Controllers\Backend;

use App\DataTables\Backend\Settings\SliderListDataTable;
use App\Libraries\Encryption;
use App\Http\Controllers\Controller;
use App\Modules\Settings\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(SliderListDataTable $dataTable)
    {
        return $dataTable->render("Settings::backend.slider.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Settings::backend.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'mimes:jpeg,jpg,png,svg|max:1024',
            'description' => 'required',
            'status' => 'required'
        ]);

        $slider = new Slider();
        $slider->title = $request->input('title');
        $slider->description = $request->input('description');
        $slider->status = $request->input('status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $slider->image = $this->storeImage('uploads/setting/slider', $file);
            } else
                return null;
        }

        $slider->save();
        return redirect(route('admin.settings.sliders.index'))->with('flash_success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sliderId)
    {
        $decodedSliderId = Encryption::decodeId($sliderId);
        $data['slider'] = Slider::find($decodedSliderId);
        return view('Settings::backend.slider.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sliderId)
    {
        $decodedSliderId = Encryption::decodeId($sliderId);
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $slider = Slider::find($decodedSliderId);
        $slider->title = $request->input('title');
        $slider->description = $request->input('description');
        $slider->status = $request->input('status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $this->deleteExistingImage('setting/slider', $slider->image);
                $slider->image = $this->storeImage('uploads/setting/slider', $file);
            } else
                return null;
        }
        $slider->save();
        return redirect(route('admin.settings.sliders.index'))->with('flash_success', 'Slider updated successfully.');
    }

    /**
     * @param $sliderId
     */
    public function delete($sliderId)
    {
        $decodedSliderId = Encryption::decodeId($sliderId);
        $slider = Slider::find($decodedSliderId);
        $this->deleteExistingImage('setting/slider', $slider->image);
        $slider->delete();
        session()->put('flash_success', 'Slider deleted successfully!');
    }

    // image delete from folder
    public function deleteExistingImage($path, $file)
    {
        if ($file) {
            $previousExistingPhoto = 'uploads/' . $path . '/' . $file; // get previous photo from folder
            if (File::exists($previousExistingPhoto)) // unlink or remove previous photo from folder
                unlink($previousExistingPhoto);
        }
    }

    // image store into folder
    public function storeImage($path, $file)
    {
        if (!file_exists($path))
            mkdir($path, 0777, true);

        $fileName = md5($file . microtime()) . '.' . $file->extension();
        Image::make($file)->resize(1920,500)->save($path . '/' . $fileName);
        return $fileName;
    }

}
