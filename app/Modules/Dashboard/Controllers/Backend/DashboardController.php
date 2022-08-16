<?php

namespace App\Modules\Dashboard\Controllers\Backend;

use App\Modules\User\Models\User;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    private $totalCustomer;

    public function index()
    {
        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);

      //  total day of a month
       $daysInMonth = now()->daysInMonth;

      // All current month Data

       $currentMonthData =  Order::selectRaw('count(*) as customers,sum(total_amount) as amount,DAY(created_at) as day,Year(created_at) as year,MONTHNAME(created_at) as month')
                ->whereBetween('created_at', [now()->startOfMonth()->toDateString().' 00:00:00',now()->endOfMonth()->toDateString().' 23:59:59'])
                ->orderBy('day')
                ->groupBy('day')
                ->get();



        $collection = collect($currentMonthData);
        $data['totalCustomerCurrentMonth'] = $currentMonthData->sum('customers');


        $currentMonthData = [];
        $days = [];
        for($i=1;$i<=$daysInMonth;$i++)
        {
            if($record = $collection->where('day',$i)->first())
             $currentMonthData[$i] = floatval($record->amount);
             else
             $currentMonthData[$i] = 0;

             $days[$i] = $i;
        }

        $data['days'] = $days;

       $data['currentMonthData'] = array_values($currentMonthData);


        // All Data current year

         $data['currentYearSaleData'] = array_values($this->getYealySale(Carbon::now()));
         $data['currentYearCustomer'] = $this->totalCustomer;

         // All Data last year

         $data['lastYearSaleData'] = array_values($this->getYealySale(Carbon::now()->subYear()));
         $data['lastYearCustomer'] = $this->totalCustomer;

        $data['product'] = Product::where('is_archive',false)->count();
        $data['processingOrder'] = Order::where(['is_archive' => false,'order_status' => 'processing'])->count();
        $data['completedOrder'] = Order::where(['is_archive' => false,'order_status' => 'completed'])->count();
        $data['cancelledOrder'] = Order::where(['is_archive' => false,'order_status' => 'cancelled'])->count();

        //running year data

        $data['todaySale'] = Order :: whereBetween('created_at', [now()->toDateString().' 00:00:00',now()->toDateString().' 23:59:59'])->sum('total_amount');

         $data['thisWeekSale'] = Order ::whereBetween('created_at', [now()->startOfWeek()->toDateString().' 00:00:00',now()->toDateString().' 23:59:59'])->sum('total_amount');

         $data['thisMonthSale'] = array_sum($data['currentMonthData']);
         $data['thisYearSale'] = array_sum($data['currentYearSaleData']);

        // last year data

       $data['lastdaySale']   = Order :: whereBetween('created_at',[now()->subDay()->toDateString().' 00:00:00',now()->subDay()->toDateString().' 23:59:59'])->sum('total_amount');


       $data['lastWeekSale'] = Order :: whereBetween('created_at', [now()->subWeek()->startOfWeek()->toDateString().' 00:00:00',now()->subWeek()->endOfWeek()->toDateString().' 23:59:59'])->sum('total_amount');

       $data['lastMonthSale'] = Order :: whereBetween('created_at', [now()->subMonth()->startOfMonth()->toDateString().' 00:00:00',now()->subMonth()->endOfMonth()->toDateString().' 23:59:59'])->sum('total_amount');

        $data['lastYearSale'] = array_sum($data['lastYearSaleData']);

        $data['totalUsers'] = User::count();

        return view("Dashboard::backend.index",$data);
    }

    public function getYealySale($date)
    {
        $yearlyData =  Order::selectRaw('count(*) as customers,sum(total_amount) as amount,Month(created_at) as month,Year(created_at) as year,MONTHNAME(created_at) as month_name')
        ->whereBetween('created_at', [$date->startOfYear()->toDateString().' 00:00:00',$date->endOfYear()->toDateString().' 23:59:59'])
        ->orderBy('month')
        ->groupBy('month')
        ->get();

        $collection = collect($yearlyData);
        $this->totalCustomer = $yearlyData->sum('customers');
        $yearlyData = [];
        for($i=1;$i<=12;$i++)
        {
            if($record = $collection->where('month',$i)->first())
            $yearlyData[$i] = floatval($record->amount);
            else
            $yearlyData[$i] = 0;
        }

        return $yearlyData;
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
}
