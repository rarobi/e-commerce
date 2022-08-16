<?php

namespace App\Modules\Settings\Controllers\Backend;

use App\DataTables\Backend\Settings\AdvertisementDataTable;
use App\Libraries\Encryption;
use App\Http\Controllers\Controller;
use App\Modules\Settings\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class AdvertisementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(AdvertisementDataTable $dataTable)
    {
        return $dataTable->render("Settings::backend.advertisement.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Settings::backend.advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'mimes:jpeg,jpg,png,svg|max:1024',
            'link' => 'required',
            'description' => 'required',
            'expired_date' => 'required|date',
            'status' => 'required'
        ]);

        $advertisement = new Advertisement();
        $advertisement->title = $request->input('title');
        $advertisement->link = $request->input('link');
        $advertisement->expired_date = $request->input('expired_date');
        $advertisement->description = $request->input('description');
        $advertisement->status = $request->input('status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $advertisement->image = $this->storeImage('uploads/setting/advertisement', $file);
            } else
                return null;
        }

        $advertisement->save();
        return redirect(route('admin.settings.advertisements.index'))->with('flash_success', 'Advertisement crated successfully.');
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
    public function edit($advertisementId)
    {
        $decodedId = Encryption::decodeId($advertisementId);
        $data['advertisement'] = Advertisement::find($decodedId);
        return view('Settings::backend.advertisement.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $advertisementId)
    {
        $decodedId = Encryption::decodeId($advertisementId);
        $this->validate($request, [
            'title' => 'required',
            'image' => 'sometimes|mimes:jpeg,jpg,png,svg|max:1024',
            'link' => 'required',
            'description' => 'required',
            'expired_date' => 'required|date',
            'status' => 'required'
        ]);

        $advertisement = Advertisement::find($decodedId);
        $advertisement->title = $request->input('title');
        $advertisement->link = $request->input('link');
        $advertisement->expired_date = $request->input('expired_date');
        $advertisement->description = $request->input('description');
        $advertisement->status = $request->input('status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $this->deleteExistingImage('setting/advertisement', $advertisement->image);
                $advertisement->image = $this->storeImage('uploads/setting/advertisement', $file);
            } else
                return null;
        }
        $advertisement->save();
        return redirect(route('admin.settings.advertisements.index'))->with('flash_success', 'Advertisement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($advertisementId)
    {
        $decodedId = Encryption::decodeId($advertisementId);
        $advertisement = Advertisement::find($decodedId);
        $this->deleteExistingImage('setting/advertisement', $advertisement->image);
        $advertisement->delete();
        session()->put('flash_success', 'Advertisement deleted successfully!');
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
        Image::make($file)->save($path . '/' . $fileName);
        return $fileName;
    }

}
