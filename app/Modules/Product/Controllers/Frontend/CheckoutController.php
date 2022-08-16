<?php

namespace App\Modules\Product\Controllers\Frontend;
use App\Modules\Settings\Models\Division;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Settings\Models\District;

class CheckoutController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         if(!auth()->user())
             return redirect('/login');

         $data['division']  = Division::where('is_archive', 0)->pluck('name','id');
         $data['districts'] = District::where('is_archive', 0)->pluck('name','id');
  
         return view("Product::frontend.checkout.index",$data);
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateShippingAddress(Request $request)
    {
        $authUserId = auth()->user()->id;

        if(!$authUserId)
            return redirect('/login');

        $this->validate($request, [
            'name' => 'required',
            'mobile' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'address' => 'required'
        ]);

        $user = User::find($authUserId);
        $user->name = $request->input('name');
        $user->mobile = $request->input('mobile');
        $user->division_id = $request->input('division_id');
        $user->district_id = $request->input('district_id');
        $user->address = $request->input('address');
        $user->save();

        return redirect()->back()->with('flash_success','Your shipping address updated successfully.');

    }
}
