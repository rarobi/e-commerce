<?php

namespace App\Modules\Payment\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Modules\Payment\Models\PaymentLog;
use App\Modules\Payment\Models\ProviderInfo;
use App\Modules\Payment\Models\SalesInvoice;
use App\Modules\Payment\Models\PaymentIPNLog;
use App\Modules\Payment\Models\PaymentEndPointLog;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected $sslProviderName;

    public function __construct() {
        $this->sslProviderName   =   'sslwireless';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("Payment::index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function paymentRequest(Request $request) {
        // dd($request->all(), json_encode(session()->get('cart')));
        try{
            //Requests validation
            if(!$request->input('platform')) {
                throw new \Exception('Please provide platform');
            }
            // if(!$request->input('provider_id')) {
            //     throw new \Exception('Please provide provider_id');
            // }
            if(!$request->input('price')) {
                throw new \Exception("Please provide price");
            }

            if(!$request->input('num_of_item')) {
                throw new \Exception("Please provide num_of_item");
            }
            if(!$request->input('customer_mobile')) {
                throw new \Exception("Please provide customer_mobile");
            }

            $user   =   User::where('mobile', 'like', '%' . $request->input('customer_mobile') . '%')->first();

            //Check user authentication
            if( is_null($user)) {
                throw new \Exception('Sorry, this user is not found in database');
            }

            //Manage input requests
            $manage_requests = $this->manageRequests($request->all(), $user);

            //Find provider information
            $providerInformation =  ProviderInfo::find($manage_requests['provider_id']);

            if($providerInformation->provider_type == 'payment-gateway') {
                //check to handle multiple payment gateway
                if (ucfirst($providerInformation->name) == 'Sslwireless') {
                    // get ssl information
                    $end_point_info = $this->getSslEndPointInfo($manage_requests);

                    /*
                     * Store request data into log table
                     * A Request Response Log is first created HERE, which
                     * is then updated later, irrespective of whether
                     * the transaction was successful or not
                     */
                    $initialRequestResponse = $this->createRequestResponseLog($manage_requests, $end_point_info, null,null, true);

                    //Format gateway data
                    $post_data = $this->formattedGatewayData($manage_requests, $end_point_info, $user);
                    $method    = 'post';

                    # REQUEST SEND TO SSLCOMMERZ BY CURL
                    $direct_api_url = config('payment.gateway-url.sslwireless.base_url');
                    $curl_response = $this->getCurlResponse($direct_api_url, $post_data, $method);

                    if($curl_response['code'] == 200 && !( curl_errno($curl_response['handle']))) {
                        curl_close( $curl_response['handle']);
                        $sslcommerzResponse = $curl_response['content'];
                        //response store in log table
                        $updateRequestResponseLog = $this->createRequestResponseLog(null, null, $sslcommerzResponse, $initialRequestResponse);

                    } else {
                        curl_close( $curl_response['handle']);
                        //response store in log table
                        $updateRequestResponseLog = $this->createRequestResponseLog(null, null, $curl_response['content'], $initialRequestResponse);

                        if($manage_requests['platform'] == 'mobile') {
                            return  json_encode(['code' => '400', 'status' => 'fail', 'data' => null, 'message' => "FAILED TO CONNECT WITH SSLCOMMERZ API!"]);
                        } else {
                            return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail');
                        }
                    }
                    # PARSE THE JSON RESPONSE
                    $sslcz = json_decode($sslcommerzResponse, true);
                    if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
                        // redirect to payment getway page
                        return redirect($sslcz['GatewayPageURL']);
                    } else {
                        return json_encode(['code' => '400', 'status' => 'fail', 'data' => null, 'message' => "JSON Data parsing error!"]);
                    }

                } else {
                    //check another payment gateway
                }
            }
        } catch (\Exception $e) {

            $data['code']       = 400;
            $data['status']     = "error";
            $data['error']      = [
                'message'       => $e->getMessage()
            ];
        }
    }

    /**
     * Manage request params
     * @param $request_params
     * @return array
     */
    protected function manageRequests($request_params, $user){
        $request_list = [];
        $cartInfo = session()->get('cart');

        $request_list['platform']           = strip_tags($request_params['platform']);
        $request_list['provider_id']        = 1;
        // $request_list['product_category']   = isset($request_params['product_category']) ? strip_tags($request_params['product_category']) : null;
        $request_list['product_name']       = isset($cartInfo) ? strip_tags($cartInfo[1]['name']) : null;
        $request_list['price']              = isset($request_params['price']) ? strip_tags($request_params['price']) : null;
        $request_list['customer_name']      = isset($user->name) ? strip_tags($user->name) : null;
        $request_list['customer_email']     = isset($user->email) ? strip_tags($user->email) : null;
        $request_list['customer_phone']     = isset($request_params['customer_mobile']) ? strip_tags($request_params['customer_mobile']) : null;
        $request_list['num_of_item']        = isset($request_params['num_of_item']) ? strip_tags($request_params['num_of_item']) : null;

        return $request_list;
    }

    /**
     * Format ssl end point info
     * @param $request_params
     * @return array
     */
    protected function getSslEndPointInfo($request_params){
        //Generate transaction id
        $transactionId  =  $this->generateExternalTrxId();

        //define endpoints
        $appURL         = env('APP_URL', 'https://ieszone.com.bd').'/';
        $successUrl     = $appURL.config('payment.gateway-url.sslwireless.success_url');
        $failedUrl      = $appURL.config('payment.gateway-url.sslwireless.fail_url');
        $canceledUrl    = $appURL.config('payment.gateway-url.sslwireless.cancel_url');
        $ipnUrl         = $appURL.config('payment.gateway-url.sslwireless.ipn_url');

        $providerName   = 'sslwireless';
        $price          =  $request_params['price'];

        $data    =   [
            'transaction_id' =>  $transactionId,
            'success_url'    =>  $successUrl,
            'failed_url'     =>  $failedUrl,
            'cancel_url'     =>  $canceledUrl,
            'ipn_url'        =>  $ipnUrl,
            'provider_name'  =>  $providerName,
            'price'          =>  $price,
        ];

        return $data;
    }

    /**
     * Store SSL request & response into log table
     * @param $manage_requests
     * @param $end_point_info
     * @param null $ssl_response
     * @param null $initialRequestResponse
     * @param bool $initialRequest
     * @return SslRequestResponseLog
     */
    protected function createRequestResponseLog($manage_requests, $end_point_info, $ssl_response = null, $initialRequestResponse = null, $initialRequest = false) {
        //dd($manage_requests, $end_point_info, $ssl_response, $initialRequestResponse, $initialRequest);
        if($initialRequest == true){
            $request_log = new PaymentLog();
            $request_log->product_category  = 'online product'; //TODO:: this need to be dynamic
            $request_log->product_name      = $manage_requests['product_name'];
            $request_log->num_of_item       = $manage_requests['num_of_item'];
            $request_log->request_price     = $end_point_info['price'];
            $request_log->currency          = 'BDT';
            $request_log->platform          = $manage_requests['platform'];
            $request_log->provider_name     = $end_point_info['provider_name'];
            $request_log->transaction_id    = $end_point_info['transaction_id'];
            $request_log->customer_name     = $manage_requests['customer_name'];
            $request_log->customer_email    = $manage_requests['customer_email'];
            $request_log->customer_phone    = $manage_requests['customer_phone'];
            $request_log->save();

            return $request_log;
        } else {
            $ssl_response = json_decode($ssl_response);
            $response_log = PaymentLog::find($initialRequestResponse->id);
            $response_log->status               = $ssl_response->status;
            $response_log->gateway_page_url     = isset($ssl_response->redirectGatewayURL) ? $ssl_response->redirectGatewayURL : null;
            $response_log->failed_reason        = isset($ssl_response->failedreason) ? $ssl_response->failedreason : null;
            $response_log->session_key          = isset($ssl_response->sessionkey) ? $ssl_response->sessionkey : null;
            $response_log->save();
        }
    }

    /**
     * Formatted gateway data
     * @param $request_params
     * @param $end_point_info
     * @param $user
     * @return array
     */
    protected function formattedGatewayData($request_params, $end_point_info, $user) {
        //dd($request_params, $end_point_info, $user);
        $data    = [
            'total_amount' => $end_point_info['price'],
            'store_id'     => config('payment.gateway-cred.sslwireless.store_id'),
            'store_passwd' => config('payment.gateway-cred.sslwireless.store_pass'),
            'tran_id'      => $end_point_info['transaction_id'],
            'success_url'  => $end_point_info['success_url'],
            'fail_url'     => $end_point_info['failed_url'],
            'cancel_url'   => $end_point_info['cancel_url'],
            'ipn_url'      => $end_point_info['ipn_url'],

            'cus_name'    => isset($user->name) ? $user->name : 'Test',
            'cus_email'   => isset($user->email) ? $user->email : 'info@test.com',
            'cus_phone'   => isset($user->mobile) ? $user->mobile : '+880xxxxxxx',
            'cus_add1'    => isset($user->present_address) ? $user->present_address : 'Dhaka',
            'cus_city'    => isset($user->district) ? $user->district : 'Dhaka',
            'cus_country' => isset($user->country) ? $user->country : 'Bangladesh',

            'shipping_method'  => 'NO',
            'product_name'     => $request_params['product_name'],
            'product_category' => 'online product', //TODO:: this need to be dynamic
            'product_profile'  => 'general',

            'value_c' => $request_params['platform'],
            'value_d' => $user->mobile,
        ];

        return $data;
    }

    /**
     * Hit ssl API with curl
     * @param $url
     * @param $params
     * @return array
     */
    protected function getCurlResponse($url, $params, $method) {

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        if($method == 'post') {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle );
        $code    = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return [
            'handle'  => $handle,
            'code'    => $code,
            'content' => $content,
        ];
    }

    /**
     * SSL redirect to success endpoint
     * @param Request $request
     * @param null $id
     * @return false|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|string
     * @throws \Throwable
     */
    protected function sslResponseSuccess(Request $request, $id = null) {

        $logMessage = "Success Payment Request comes from: " . json_encode($request->getClientIps());
        Log::emergency($logMessage);

        # CALL THE FUNCTION TO CHECK THE RESULT
        $storePass  =   config('payment.gateway-cred.'.$this->sslProviderName.'.store_pass');

        $transactionInformation =   json_encode($request->all());
        $transactionInformation =   json_decode($transactionInformation);

        $platform  = $transactionInformation->value_c;
        $mobile    = $transactionInformation->value_d;

        //Store sslCommerz endpoint  request
        $end_point_log = $this->storeSslEndpointRequest($transactionInformation);

        try {
            if($this->_SSLCOMMERZ_hash_varify($storePass)) {

                $user   =   User::where('mobile', 'like', '%' . $mobile . '%')->first();
                session(['subscribed_by'=>$user->id]);

                //Call order validation API with credentials
                $val_id        = $transactionInformation->val_id;
                $store_id      = config('payment.gateway-cred.sslwireless.store_id');
                $store_passwd  = config('payment.gateway-cred.sslwireless.store_pass');
                $method        = 'get';

                # REQUEST SEND TO SSLCOMMERZ BY CURL
                $order_api_url   = config('payment.gateway-url.sslwireless.order_validate_url');
                $api_with_params = $order_api_url.'?val_id='.$val_id.'&store_id='.$store_id.'&store_passwd='.$store_passwd.'&format=json';

                $curl_response = $this->getCurlResponse($api_with_params, null, $method);

                if($curl_response['code'] == 200 && !( curl_errno($curl_response['handle']))) {
                    curl_close( $curl_response['handle']);
                    $response = json_decode($curl_response['content']);

                    if($response->status == 'VALID' || $response->status == 'VALIDATED') {

                        $providerName           = 'Sslwireless';
                        $providerInformation    =  $this->getProviderInformation($providerName);

                        DB::transaction(function () use ($user, $transactionInformation, $response, $mobile, $platform, $providerInformation ) {
                            // store into telemedicine invoice table
                            //TODO:: Need to check if this code is required here or not..
                            $salesInvoice = new SalesInvoice();

                            $salesInvoice->customer_mobile       =   $mobile;
                            $salesInvoice->platform              =   $platform;
                            $salesInvoice->transaction_price     =   isset($transactionInformation->amount)?$transactionInformation->amount:null;;
                            $salesInvoice->provider_id           =   $providerInformation['id'];
                            $salesInvoice->provider_name         =   $providerInformation['name'];
                            $salesInvoice->store_id              =   $providerInformation['store_id'];
                            $salesInvoice->is_success            =   true;
                            $salesInvoice->external_trx_id       =   isset($transactionInformation->tran_id)?$transactionInformation->tran_id:null;
                            $salesInvoice->internal_trx_id       =   isset($transactionInformation->val_id)?$transactionInformation->val_id:null;
                            $salesInvoice->bank_tran_id          =   isset($transactionInformation->bank_tran_id)?$transactionInformation->bank_tran_id:null;
                            $salesInvoice->card_type             =   isset($transactionInformation->card_type)?$transactionInformation->card_type:null;
                            $salesInvoice->card_no               =   isset($transactionInformation->card_no)?$transactionInformation->card_no:null;
                            $salesInvoice->card_issuer           =   isset($transactionInformation->card_issuer)?$transactionInformation->card_issuer:null;
                            $salesInvoice->card_brand            =   isset($transactionInformation->card_brand)?$transactionInformation->card_brand:null;
                            $salesInvoice->verify_sign           =   isset($transactionInformation->verify_sign)?$transactionInformation->verify_sign:null;
                            $salesInvoice->verify_key            =   isset($transactionInformation->verify_key)?$transactionInformation->verify_key:null;
                            $salesInvoice->purchase_date         =   isset($transactionInformation->tran_date)?$transactionInformation->tran_date:Carbon::today()->toDateTimeString();
                            $salesInvoice->save();
                        });

                            //event trigger for sending an email to adminstations
//                            $telemedicine = TelemedicineRequest::find($appointmentId);
//                            event(new UserRequestTeleMedicine($telemedicine));

                        $data['status']  = 'success';

                    } else {
                        $data['status']  = $response->status;
                    }
                } else {
                    curl_close( $curl_response['handle']);
                    if($platform == 'mobile') {
                        return  json_encode(['code' => '400', 'status' => 'fail', 'data' => null, 'message' => "FAILED TO CONNECT WITH SSLCOMMERZ VALIDATION API!"]);
                    } else {
                        return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail');//TODO::Change this URL
                    }
                }
                if($platform == 'mobile'){
                    $data['code']   = 200;
//                    $data['user']   = $this->userTransformer->transform($user);
                    return response()->json($data);
                } else {
                    $data['code']  = 200;
//                    $data['user'] = $this->userTransformer->transform($user);
                    return redirect()->away(config('misc.site.main_site').'/dashboard?status=success'); //TODO::Change this URL
                }

            } else {
                $data['code']    = 400;
                $data['status']  = 'failed';
                if($platform == 'mobile') {
                    return response()->json($data);
                } else {
                    return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail'); //TODO::Change this URL
                }
            }
        } catch (\Exception $e) {
            $data['code']    = 400;
            $data['status']  = 'failed';
            if($platform == 'mobile') {
                return response()->json($data);
            } else {
                return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail'); //TODO::Change this URL
            }
        }
    }

    /**
     * SSL redirect to fail endpoint
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sslResponseFail(Request $request, $id=null) {
        $platform  = $request->value_c;

        $logMessage = "Failed Payment Request comes from: " . json_encode($request->getClientIps()). " and the request data is: " . json_encode($request->all());
        Log::emergency($logMessage);

        //Store sslCommerz endpoint  request
        $transactionInformation =   json_encode($request->all());
        $transactionInformation =   json_decode($transactionInformation);
        $end_point_log = $this->storeSslEndpointRequest($transactionInformation);

        $data['code']    = 400;
        $data['status']  = $request->status;
//        $data['invoice'] = array();
        if($platform == 'mobile'){
            return response()->json($data);
        } else {
            return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail'); //TODO::Change this URL
        }
    }

    /**
     * SSL redirect to cancell endpoint
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sslResponseCancel(Request $request, $id=null) {
        $platform  = $request->value_c;

        $logMessage = "Cancelled Payment Request comes from: " . json_encode($request->getClientIps()). " and the request data is: " . json_encode($request->all());
        Log::emergency($logMessage);

        //Store sslCommerz endpoint  request
        $transactionInformation =   json_encode($request->all());
        $transactionInformation =   json_decode($transactionInformation);
        $end_point_log = $this->storeSslEndpointRequest($transactionInformation);

        $data['code']    = 400;
        $data['status']  = $request->status;
//        $data['invoice'] = array();
        if($platform == 'mobile'){
            return response()->json($data);
        } else {
            return redirect()->away(config('misc.site.main_site').'/dashboard?status=fail'); //TODO::Change this URL
        }
    }

    /**
     * SSL redirect to ipn endpoint
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sslResponseIpn(Request $request, $id=null) {

        $logMessage = "IPN Request comes from: " . json_encode($request->getClientIps()). " and the request data is: " . json_encode($request->all());
        Log::emergency($logMessage);

        $platform  = $request->value_c;
        $mobile    = $request->value_d;

//        $user   =   User::where('mobile', 'like', '%' . $mobile . '%')->first();
        //dd($service_name, $api_token, $platform);
        try{
            //store request into log
            //if transaction found second time check log & update field otherwise create new log
//            if ($request->tran_id) {
//                $ipnLog = SslIpnRequestsLog::where('tran_id', $request->tran_id)->first();
//                if(is_null($ipnLog)){
            $ipn_log = new PaymentIPNLog();
            $ipn_log->status                = $request->status;
            $ipn_log->tran_date             = $request->tran_date;
            $ipn_log->tran_id               = $request->tran_id;
            $ipn_log->val_id                = $request->val_id;
            $ipn_log->amount                = $request->amount;
            $ipn_log->store_amount          = $request->store_amount;
            $ipn_log->card_type             = $request->card_type;
            $ipn_log->card_no               = $request->card_no;
            $ipn_log->currency              = $request->currency;
            $ipn_log->bank_tran_id          = $request->bank_tran_id;
            $ipn_log->card_issuer           = $request->card_issuer;
            $ipn_log->card_brand            = $request->card_brand;
            $ipn_log->card_issuer_country   = $request->card_issuer_country;
            $ipn_log->currency_amount       = $request->currency_amount;
            $ipn_log->verify_sign           = $request->verify_sign;
            $ipn_log->verify_key            = $request->verify_key;
            $ipn_log->risk_level            = $request->risk_level;
            $ipn_log->risk_title            = $request->risk_title;
//            $ipn_log->request_id            = isset($request_id) ? $request_id : null;
            $ipn_log->customer_mobile       = $mobile;
            $ipn_log->platform              = $platform;
            $ipn_log->provider_name         = 'sslwireless';
            $ipn_log->save();

            //Call order validation API with credentials
            if($request->status == 'VALID' || $request->status == 'VALIDATED'){
                $val_id        = $request->val_id;
                $store_id      = config('payment.gateway-cred.sslwireless.store_id');
                $store_passwd  = config('payment.gateway-cred.sslwireless.store_pass');
                $method        = 'get';

                # REQUEST SEND TO SSLCOMMERZ BY CURL
                $order_api_url   = config('payment.gateway-url.sslwireless.order_validate_url');
                $api_with_params = $order_api_url.'?val_id='.$val_id.'&store_id='.$store_id.'&store_passwd='.$store_passwd.'&format=json';

                $curl_response = $this->getCurlResponse($api_with_params, null, $method);
                Log::emergency('SSL order validation log:- '.$curl_response['content']);
                if($curl_response['code'] == 200 && !( curl_errno($curl_response['handle']))) {
                    curl_close($curl_response['handle']);
                    $response = json_decode($curl_response['content']);

                    //update sampleCollection request table
                    $ipnRequestsLog = DB::table('payment_ipn_log')
                        ->where('id', $ipn_log->id)
                        ->update(['order_validation_status' => $response->status]);

                    $data['code']   = 200;
                    $data['status'] = 'success';
                    return response()->json($data);

                } else {
                    // exception handle
                    $data['code']    = 400;
                    $data['status']  = 'failed';
                    return response()->json($data);
                }
            } else {
                $data['code']    = 400;
                $data['status']  = $request->status;
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['code']     = 400;
            $data['status']   = 'failed';
            $data['message']  = $e->getMessage();
            return response()->json($data);
        }
    }

    /**
     * generate random external trx id for transaction
     * @return int
     * */
    protected function generateExternalTrxId()
    {
        $externalTrxId = random_int(1,999999999); //TODO:: Need to add timestamp for identify unique value
        $validator = Validator::make(['transaction_id' => $externalTrxId], [
            'transaction_id' => 'unique:payment_request_response_log',
        ]);

        if($validator->fails()) {
            $externalTrxId = null;
            $this->generateExternalTrxId();
        }

        return $externalTrxId;
    }

    protected function storeSslEndpointRequest($params) {

        $platform  = $params->value_c;
        $mobile    = $params->value_d;

        $endpoint_log = new PaymentEndPointLog();
        $endpoint_log->status                = isset($params->status) ? $params->status : null;
        $endpoint_log->tran_date             = isset($params->tran_date) ? $params->tran_date : null;
        $endpoint_log->request_tran_id       = isset($params->tran_id) ? $params->tran_id : null;
        $endpoint_log->val_id                = isset($params->val_id) ? $params->val_id : null;
        $endpoint_log->amount                = isset($params->amount) ? $params->amount : null;
        $endpoint_log->store_amount          = isset($params->store_amount) ? $params->store_amount : null;
        $endpoint_log->card_type             = isset($params->card_type) ? $params->card_type : null;
        $endpoint_log->card_no               = isset($params->card_no) ? $params->card_no : null;
        $endpoint_log->currency              = isset($params->currency) ? $params->currency : null;
        $endpoint_log->bank_tran_id          = isset($params->bank_tran_id) ? $params->bank_tran_id : null;
        $endpoint_log->card_issuer           = isset($params->card_issuer) ? $params->card_issuer : null;
        $endpoint_log->card_brand            = isset($params->card_brand) ? $params->card_brand : null;
        $endpoint_log->card_issuer_country   = isset($params->card_issuer_country) ? $params->card_issuer_country : null;
        $endpoint_log->currency_amount       = isset($params->currency_amount) ? $params->currency_amount : null;
        $endpoint_log->verify_sign           = isset($params->verify_sign) ? $params->verify_sign : null;
        $endpoint_log->error                 = isset($params->error) ? $params->error : null;
        $endpoint_log->risk_level            = isset($params->risk_level) ? $params->risk_level : 0;
        $endpoint_log->risk_title            = isset($params->risk_title) ? $params->risk_title : null;
//        $endpoint_log->request_id            = isset($request_id) ? $request_id : null;
        $endpoint_log->customer_mobile       = $mobile;
        $endpoint_log->platform              = $platform;
        $endpoint_log->provider_name         = 'sslwireless';
        $endpoint_log->save();

        return $endpoint_log;
    }

    # FUNCTION TO CHECK HASH VALUE FOR SSLCOMMERZ
    protected function _SSLCOMMERZ_hash_varify($store_passwd="") {

        if(isset($_POST) && isset($_POST['verify_sign']) && isset($_POST['verify_key'])) {
            # NEW ARRAY DECLARED TO TAKE VALUE OF ALL POST

            $pre_define_key = explode(',', $_POST['verify_key']);

            $new_data = array();
            if(!empty($pre_define_key )) {
                foreach($pre_define_key as $value) {
                    if(isset($_POST[$value])) {
                        $new_data[$value] = ($_POST[$value]);
                    }
                }
            }
            # ADD MD5 OF STORE PASSWORD
            $new_data['store_passwd'] = md5($store_passwd);

            # SORT THE KEY AS BEFORE
            ksort($new_data);

            $hash_string="";
            foreach($new_data as $key=>$value) {
                $hash_string .= $key.'='.($value).'&';
                //Log::info($hash_string);
            }
            $hash_string = rtrim($hash_string,'&');

            if(md5($hash_string) == $_POST['verify_sign']) {

                return true;

            } else {
                return false;
            }
        } else return false;
    }

    private function getProviderInformation($providerName)
    {
        $provider   =   ProviderInfo::where('name',$providerName)->first();
        if  (!$provider) {
            throw new \Exception('invalid provider name');
        }

        $providerName   =   $provider->name;
        $providerId     =   $provider->id;
        $providerType   =   $provider->provider_type;

        $storeId = $provider->store_id;
        $storePass = $provider->password;
        $sourceAddress = $provider->source_address;

        return [
            'store_id'        => $storeId,
            'password'        => $storePass,
            'name'            => $providerName,
            'id'              => $providerId,
            'source_address'  => $sourceAddress,
            'provider_type'   => $providerType
        ];
    }

}
