<?php

namespace App\Libraries;


use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductBrand;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Product\Models\ProductSubcategory;
use App\Modules\Product\Models\Wishlist;
use App\Modules\Settings\Models\Appearance;
use App\Modules\Settings\Models\EmailQueue;
use App\Modules\Settings\Models\Photo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Self_;

class CommonFunction {

    /**
     * @param Carbon|string $updated_at
     * @param string $updated_by
     * @return string
     * @internal param $Users->id /string $updated_by
     */
    public static function showAuditLog($updated_at = '', $updated_by = '') {
        $update_was = 'Unknown';
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }

        $user_name = 'Unknown';
        if ($updated_by) {
            $name = User::where('id', $updated_by)->first();
            if ($name) {
                $user_name = $name->user_full_name;
            }
        }
        return '<span class="help-block">Last updated : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
    }

    public static function getUserId() {

        if (Auth::user()) {
            return Auth::user()->id;
        } else {
            return 0;
        }
    }

    public static function getUserType() {

        if (Auth::user()) {
            return Auth::user()->user_type;
        } else {
            dd('Invalid User Type');
        }
    }

//    public static function GlobalSettings(){
//        $logoInfo=Logo::orderBy('id','DESC')->first();
//        if($logoInfo!="") {
//            Session::set('logo', $logoInfo->logo);
//            Session::set('title', $logoInfo->title);
//            Session::set('manage_by', $logoInfo->manage_by);
//            Session::set('help_link', $logoInfo->help_link);
//        }else{
//            Session::set('logo', 'assets/images/company_logo.png');
//        }
//        //return $logoInfo;
//    }



    public static function convertUTF8($string) {
//        $string = 'u0986u09a8u09c7u09beu09dfu09beu09b0 u09b9u09c7u09beu09b8u09beu0987u09a8';
        $string = preg_replace('/u([0-9a-fA-F]+)/', '&#x$1;', $string);
        return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
    }

    public static function isAdmin() {
        $user_type = Auth::user()->user_type;
        /*
         * 1x101 for System Admin
         */
        if ($user_type == '1x101')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function convert2Bangla($eng_number) {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['à§¦', 'à§§', 'à§¨', 'à§©', 'à§ª', 'à§«', 'à§¬', 'à§­', 'à§®', 'à§¯'];
        return str_replace($eng, $ban, $eng_number);
    }

    public static function convert2English($ban_number) {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['à§¦', 'à§§', 'à§¨', 'à§©', 'à§ª', 'à§«', 'à§¬', 'à§­', 'à§®', 'à§¯'];
        return str_replace($ban, $eng, $ban_number);
    }

    public static function generateTrackingID($prefix, $id) {
        $prefix = strtoupper($prefix);
        $str = $id . date('Y') . mt_rand(0, 9);
        if ($prefix == 'M' || $prefix == 'N') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } elseif ($prefix == 'G') {
            if (strlen($str) > 10) {
                $str = substr($str, strlen($str) - 10);
            }
        } elseif ($prefix == 'T') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } else {
            if (strlen($str) > 14) {
                $str = substr($str, strlen($str) - 14);
            }
        }
        return $prefix . dechex($str);
    }



//    public static function getTeamNameById($id) {
//        if ($id) {
//            $name = TeamInfo::where('id', $id)->pluck('name');
//            return $name;
//        } else {
//            return 'N/A';
//        }
//    }

    public static function sendMessageFromSystem($param=array()) {

        $mobileNo = (empty($param[0]['mobileNo']) ? '0' : $param[0]['mobileNo']);
        $smsYes = (empty($param[0]['smsYes']) ? '0' : $param[0]['smsYes']);
        $smsBody = (empty($param[0]['smsBody']) ? 'No SMS Body' : $param[0]['smsBody']);
        $emailYes = (empty($param[0]['emailYes']) ? '1' : $param[0]['emailYes']);
        $emailBody = (empty($param[0]['emailBody']) ? 'No Email Body' : $param[0]['emailBody']);
        $emailHeader = (empty($param[0]['emailHeader']) ? '0' : $param[0]['emailHeader']);
        $emailAdd= (empty($param[0]['emailAdd']) ? 'base@gmail.com' : $param[0]['emailAdd']);
        $template= (empty($param[0]['emailTemplate']) ? '' : $param[0]['emailTemplate']);
        $emailSubject= (empty($param[0]['emailSubject']) ? 'No Subject' : $param[0]['emailSubject']);

        if ($emailYes == 1) {

            $email_content_html = <<<HERE
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Inventory Management System</title>
    <link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
    <style type="text/css">
        *{
            font-family: Vollkorn;
        }
    </style>
</head>


<body>
<table width="80%" style="background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;">
    <thead>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">

            <h4 style="text-align:center">
               Inventory Management System
            </h4>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="margin-top: 20px; padding: 15px;">
            <!--Dear Applicant,-->
            Dear User,
            <br/><br/>
          $emailBody

            <br/><br/>
        </td>
    </tr>
    <tr style="margin-top: 15px;">
        <td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
            <h5 style="text-align:center">All right reserved by Inventory Management System 2017.</h5>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
HERE;

            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->email_content = $email_content_html;
            $emailQueue->email_to = $emailAdd;
            $emailQueue->email_subject = $emailSubject;
            $emailQueue->email_cc = 'write2hkc@gmail.com';
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();
        }
        if ($smsYes == 1) {
            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->sms_content = $smsBody;
            $emailQueue->sms_to = $mobileNo;
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();
        }
    }

    public static function auditEmail(){
        return Configuration::where('caption','AUDIT_EMAIL')->pluck('value');
    }


    public static function setCompanyInfo(){

        $companyInfo = Appearance::leftJoin('users','users.id','=','appearance.created_by')
            ->where('appearance.status',1)
            ->where('appearance.is_archive',0)
            ->where('users.user_type','1x101')
            ->first([
                'appearance.*',
                'users.user_type'
            ]);

        $data = [
            'company_name'          => isset($companyInfo->name)?$companyInfo->name:null,
            'company_admin_name'    => isset($companyInfo->admin_name)?$companyInfo->admin_name:null,
            'company_description'   => isset($companyInfo->description)?$companyInfo->description:null,
            'company_logo'          => isset($companyInfo->logo)?$companyInfo->logo:null,
            'company_website'       => isset($companyInfo->website)? $companyInfo->website:null,
            'company_address'       => isset($companyInfo->address)?$companyInfo->address:null,
            'company_email'         => isset($companyInfo->email)?$companyInfo->email:null,
            'company_phone'         => isset($companyInfo->phone)?$companyInfo->phone:null,
            'facebook_link'         => isset($companyInfo->facebook)?$companyInfo->facebook:null,
            'twitter_link'          => isset($companyInfo->twitter)?$companyInfo->twitter:null,
            'instagram_link'        => isset($companyInfo->instagram)?$companyInfo->instagram:null,
            'linkedin_link'         => isset($companyInfo->linkedin)?$companyInfo->linkedin:null,
            'pinterest_link'        => isset($companyInfo->pinterest)?$companyInfo->pinterest:null,
            'google_plus_link'      => isset($companyInfo->google_plus)?$companyInfo->google_plus:null,
            'youtube_link'          => isset($companyInfo->youtube)?$companyInfo->youtube:null,
            'web_mail_link'         => 'https://mail.google.com/mail/u/0/',
        ];

        Session::put('company', $data);
    }


    public static function productMenu(){
        $productCategoryIds = [];
        $productMenu = [];

        $productCategories = ProductCategory::where('status',1)
            ->where('is_archive',0)
            ->orderBy('name','asc')
            ->get();
        if(count($productCategories) > 0){
            foreach ($productCategories as $productCategory){
                $productCategoryIds[] = $productCategory->id;
                $productMenu[$productCategory->id]['category_name'] = $productCategory->name;
            }
        }

        $productSubcategories = ProductSubcategory::whereIn('category_id',$productCategoryIds)
            ->where('status',1)
            ->where('is_archive',0)
            ->orderBy('name','asc')
            ->get();

        if(count($productSubcategories) > 0){
            foreach($productSubcategories as $productSubcategory){
                $productMenu[$productSubcategory->category_id]['product_subcategory'][$productSubcategory->id] = $productSubcategory->name;
            }
        }
        return $productMenu;
    }

    public static function countProductView($productId){
        return Product::where('id',$productId)->update(['total_view' => DB::raw('total_view + 1') ]);
    }

    public static function productInfo(){

        return Product::leftJoin('photos',function ($query){
            $query->on('photos.reference_id','=','products.id');
            $query->where('photos.reference_type','=','product');
            $query->where('photos.status','=',1);
            $query->where('photos.is_archive','=',0);
        })
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id');
    }

    public static function topProductList(){
        return Product::leftJoin('photos',function ($query){
            $query->on('photos.reference_id','=','products.id');
            $query->where('photos.reference_type','=','product');
            $query->where('photos.status','=',1);
            $query->where('photos.is_archive','=',0);
        })
            ->where('products.status',1)
            ->where('products.is_archive',0)
            ->take(9)
            ->groupBy('products.id')
            ->orderBy('products.total_view','desc')
            ->get([
                'products.*',
                'photos.path as photo_path'
            ]);
    }

    public static function getSingleProductInfo($productId){
        return self::productInfo()
            ->leftJoin('product_subcategories', 'product_subcategories.id', '=', 'products.sub_category_id')
            ->leftJoin('product_brands', 'product_brands.id', '=', 'products.brand_id')
            ->leftJoin('product_skus', 'product_skus.id', '=', 'products.sku_id')
            ->where('products.id', $productId)
            ->first([
                'products.*',
                'product_categories.id as product_category_id',
                'product_categories.name as product_category_name',
                'product_subcategories.id as product_subcategory_id',
                'product_subcategories.name as product_subcategory_name',
                'product_brands.name as product_brand_name',
                'product_brands.website',
                'product_brands.photo as brand_photo',
                'product_skus.name as product_sku_name',
                'photos.path as photo_path'
            ]);
    }

    public static function getSingleProductPhotos($productId){
        return Photo::where('photos.reference_type', '=', 'product')
            ->where('reference_id', $productId)
            ->where('is_archive', '=', 0)
            ->get();
    }


    public static function getBrandList(){
        return ProductBrand::where('status',1)
            ->where('is_archive',0)
            ->take(6)
            ->orderBy('id','desc')
            ->get();
    }

    public static function getTopCategories(){
        $topCategories = ProductCategory::where('status',1)
            ->where('is_archive',0)
            ->take(7)
            ->orderBy('id','desc')
            ->get();
       Session::put('topCategories', $topCategories);
    }

    public static function shippingFee(){
        return 60;
    }

    public static function getWishlistInfo(){
        return self::productInfo()
            ->leftJoin('wishlist','wishlist.product_id','=','products.id')
            ->where(['wishlist.user_id' => auth()->user()->id, 'wishlist.is_archive'=> false])
            ->orderBy('wishlist.id', 'asc')
            ->groupBy('products.id')
            ->get([
                'products.*',
                'wishlist.id as wishlist_id',
                'photos.path as photo_path'
            ]);
    }

    public static function authCustomerWishlistItem(){
        return Wishlist::where(['user_id' => auth()->user()->id,'is_archive' => false])->count();
    }

    /*     * ****************************End of Class***************************** */
}
