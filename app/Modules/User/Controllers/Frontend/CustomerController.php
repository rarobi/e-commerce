<?php

namespace App\Modules\User\Controllers\Frontend;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{

    public function index()
    {
        return view("User::frontend.forget-password");
    }

    public function signup(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required'
        ]);

        $emailCheck = User::where('email',$request->input('email'))->first();
        if($emailCheck){
            session()->put('flash_danger', 'Email address already exist!!!');
            return redirect()->back()->withInput();
        }

        $mobile =  substr($request->input('mobile'),-11,11);
        $mobileCheck = User::where(DB::raw('RIGHT(mobile,11)'),$mobile)->first();
        if($mobileCheck){
            session()->put('flash_danger', 'Mobile number already exist!!!');
            return redirect()->back()->withInput();
        }

        $customer = User::firstOrNew(['email'=>$request->input('email')]);
        $customer->user_type = '2x202';
        $customer->email = $request->input('email');
        $customer->mobile = $request->input('mobile');
        $customer->password = Hash::make($request->input('password'));
        $customer->date_of_birth = $request->input('date_of_birth');
        $customer->gender = $request->input('gender');
        $customer->status = 1;
        $customer->save();
        session()->put('flash_success', 'Success!! You can login after approve from system admin.');
        return redirect('/login');

//        $email = [
//            'to' => [$customer->email],
//            'cc' => [config('misc.email_cc')],
//            'subject' => "Hello",
//            'content' => "Body"
//        ];
//        event(new SendEmail($email));
    }
}
