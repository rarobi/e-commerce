<?php use Illuminate\Support\Carbon; ?>
@extends('backend.layouts.app')
@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/dist/js/chart/uplot/uPlot.min.css') }}">
@endsection
@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-industry"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Products</span>
                    <span class="info-box-number">{{ $product }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-toolbox"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Processing Order</span>
                    <span class="info-box-number">{{ $processingOrder }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-toolbox"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Completed Order</span>
                    <span class="info-box-number">{{ $completedOrder }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-shopping-cart"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Cancelled Order</span>
                    <span class="info-box-number">{{ $cancelledOrder }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-users"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Users</span>
                    <span class="info-box-number">{{ $totalUsers }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>


    {{-- previous sale report start  --}}

    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Sales Report</h5>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 col-6">
                      <div class="description-block border-right">
                        <h5 class="description-header mb-2">${{ $lastdaySale }}</h5>
                        <span class="description-text text-capitalize">Yesterday's Sales</span>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-6">
                      <div class="description-block border-right">
                        <h5 class="description-header mb-2">${{ $lastWeekSale }}</h5>
                        <span class="description-text text-capitalize">Last Week's Sales</span>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-6">
                      <div class="description-block border-right">
                        <h5 class="description-header mb-2">${{ $lastMonthSale }}</h5>
                        <span class="description-text text-capitalize">Last Month's Sales</span>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-6">
                      <div class="description-block">
                        <h5 class="description-header mb-2">${{ $lastYearSale }}</h5>
                        <span class="description-text text-capitalize">Last Year's Sales</span>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
    </div>

    {{-- previous sale report end  --}}



    {{-- present sale report start  --}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sales Report</h5>
                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                       $dayPercentage =  getSalePercentage($lastdaySale,$todaySale)
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                        <span class="description-percentage {{ $dayPercentage['textColor'] }}"><i class="{{ $dayPercentage['icon'] }}"></i> {{ $dayPercentage['data'] }}%</span>
                        <h5 class="description-header mb-2">${{ $todaySale }}</h5>
                        <span class="description-text text-capitalize">Today's Sales</span>
                        </div>
                    </div>
                    <!-- /.col -->
                    @php
                    $weekPercentage =  getSalePercentage($lastWeekSale,$thisWeekSale)
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                        <span class="description-percentage {{ $weekPercentage['textColor'] }}"><i class="{{ $weekPercentage['icon'] }}"></i> {{ $weekPercentage['data'] }}%</span>
                        <h5 class="description-header mb-2">${{ $thisWeekSale }}</h5>
                        <span class="description-text text-capitalize">This Week's Sales</span>
                        </div>
                    </div>
                    <!-- /.col -->
                    @php
                    $monthPercentage =  getSalePercentage($lastMonthSale,$thisMonthSale)
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                        <span class="description-percentage {{ $monthPercentage['textColor'] }}"><i class="{{ $monthPercentage['icon'] }}"></i> {{ $monthPercentage['data'] }}%</span>
                        <h5 class="description-header mb-2">${{ $thisMonthSale }}</h5>
                        <span class="description-text text-capitalize">This Month's Sales</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    @php
                    $yearPercentage =  getSalePercentage($lastYearSale,$thisYearSale)
                    @endphp
                    <div class="col-sm-3 col-6">
                        <div class="description-block">
                        <span class="description-percentage {{ $yearPercentage['textColor'] }}"><i class="{{ $yearPercentage['icon'] }}"></i> {{ $yearPercentage['data'] }}%</span>
                        <h5 class="description-header mb-2">${{ $thisYearSale }}</h5>
                        <span class="description-text text-capitalize">This Year's Sales</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    </div>
            </div>
            </div>
        </div>
        <!-- /.col -->
    </div>

    {{-- present sale report end  --}}

    <section class="content">
        <div class="container-fluid">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                 Monthly Sales Statistic
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="earning-report">
                    <p>This Month sales of product statistic</p>
                    <div>
                        <h2> {{ sprintf('%0.2f',array_sum($currentMonthData)) }} Tk</h2>
                    </div>
                </div>
                <div class="text-right">
                   <p> {{ $totalCustomerCurrentMonth }} Subscribers</p>
                </div>
                <div class="sale-day-by-month-chart">
                    <canvas id="sale-day-month-chart"  style="height:auto; max-width: 100%;"></canvas>
                </div>
            </div>
          </div>
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                 Yearly Sales statistic
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="earning-report">
                    <p>This Year sales of product statistic</p>
                    <div>
                        <h2> {{ sprintf('%0.2f',array_sum($currentYearSaleData)) }} Tk</h2>
                    </div>
                </div>
                <div class="text-right">
                   <p> {{ $currentYearCustomer }} Subscribers</p>
                </div>
                <div class="sale-day-by-month-chart">
                    <canvas id="sale-month-year-chart"  style="height:auto; max-width: 100%;"></canvas>
                </div>
            </div>
          </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                Last year and running year Sales Statistic
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="d-sm-flex justify-content-between">
                    <div>
                        <h4>Current Year revenue</h4>
                        <h4> {{ sprintf('%0.2f',array_sum($currentYearSaleData)) }} Tk</h4>
                        <p> {{ $currentYearCustomer }} Subscribers</p>
                    </div>
                    <div>
                        <h4>Last Year revenue</h4>
                        <h4> {{ sprintf('%0.2f',array_sum($lastYearSaleData)) }} Tk</h4>
                        <p> {{ $lastYearCustomer }} Subscribers</p>
                    </div>
                </div>
                <div class="compare-yearly-sales-chart">
                    <canvas id="compare-yearly-sales-chart" style="min-height:300px; max-width: 100%;" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
          </div>
        </div>
    </section>
    @endsection
@section('footer-script')
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/backend/dist/js/chart/chart.min.js') }}"></script>
<script src="{{ asset('assets/backend/dist/js/chart/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/backend/dist/js/chart/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/backend/dist/js/chart/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/backend/dist/js/chart/uplot/uPlot.iife.min.js') }}"></script>
<script>
  $(function () {

var ticksStyle = {fontColor: '#495057',fontStyle: 'bold'}
var incomeChartId = document.getElementById('sale-day-month-chart').getContext('2d');
var incomeData = @json($currentMonthData);
var incomeChartObj = new Chart(incomeChartId,{
  // The type of chart we want to create
  type: 'line',
  // The data for our dataset
  data: {
      labels: [{{ implode(",",$days) }}],
      datasets: [{
          label:'Sale',
          backgroundColor: 'rgba(210, 214, 222, .7)',
          borderColor:'rgba(60,141,188,0.8)',
          pointBackgroundColor:'rgba(210, 214, 222)',
          borderWidth:1,
          hoverBorderWidth:3,
          hoverBorderColor:'#fff',
          data: incomeData
      }]
  },
  // Configuration options go here
  options: {
    title:{
      display:false,
      text:'',
      fontSize:20
    },
    legend:{
      display:true,
      labels:{
        fontColor:'#000',
        fontSize:12
      }
    },
    layout:{
      padding:{
        left:0,
        right:0,
        bottom:0,
        top:0
      }
    },
    tooltips:{
      enabled:true
    },
    scales: {
        yAxes: [{
          display: true,
          ticks: $.extend({
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          ticks: ticksStyle
        }]
      }
  }
});

// month wise yearly report
var monthYearChartId = document.getElementById('sale-month-year-chart').getContext('2d');
var monthData = @json($currentYearSaleData);
var chartObj = new Chart(monthYearChartId,{
  // The type of chart we want to create
  type: 'line',
  // The data for our dataset
  data: {
    labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
          label:'Sale',
          backgroundColor: 'rgba(210, 214, 222, .7)',
          borderColor:'rgba(60,141,188,0.8)',
          pointBackgroundColor:'rgba(210, 214, 222)',
          borderWidth:1,
          hoverBorderWidth:3,
          hoverBorderColor:'#fff',
          data: monthData
      }]
  },
  // Configuration options go here
  options: {
    title:{
      display:false,
      text:'Yearly Income',
      fontSize:20
    },
    legend:{
      display:true,
      labels:{
        fontColor:'#000',
        fontSize:12
      }
    },
    layout:{
      padding:{
        left:0,
        right:0,
        bottom:0,
        top:0
      }
    },
    tooltips:{
      enabled:true
    },
    scales: {
        yAxes: [{
          display: true,
          ticks: $.extend({
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          ticks: ticksStyle
        }]
      }
  }
});




  var mode = 'index'
  var intersect = true
  var $salesChart = document.getElementById('compare-yearly-sales-chart').getContext('2d');

  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug','Sep','Oct','Nov','Dec'],
      datasets: [
        {
          label:'Last Year',
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: @json($lastYearSaleData)
        },
        {
          label:'Running Year',
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: @json($currentYearSaleData)
        },
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: true
      },
      scales: {
        yAxes: [{
          display: true,
          ticks: $.extend({
            beginAtZero: true,
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: true
          },
          ticks: ticksStyle
        }]
      }
    }
  })


  })
</script>


@endsection
