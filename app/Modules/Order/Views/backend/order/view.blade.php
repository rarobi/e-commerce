@extends('backend.layouts.app')
@section('content')
    <ol class="breadcrumb alert alert-primary p-2">
        <li class="breadcrumb-item"><strong>Creator Name :  </strong><span>{{ $companyJob->author_name }}</span></li>
    </ol>
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-list-alt pr-2"></i>Order Details</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            Status
                        </div>
                        <div class="col-lg-8">
                            @if($orderDetails->status == 1)
                                <label class='badge badge-success'>Active</label>
                            @else
                                <label class='badge badge-danger'>Inactive</label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            Title
                        </div>
                        <div class="col-lg-8">
                            {{$companyJob->title}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            Position
                        </div>
                        <div class="col-lg-8">
                            {{$companyJob->job_role}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            Job Expired In
                        </div>
                        <div class="col-lg-8">
                            {{Carbon\Carbon::parse($companyJob->link_expire)->format('F d, Y')}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            Image
                        </div>
                        <div class="col-lg-8">
                            <img src="{{asset('uploads/career/job/'.$companyJob->logo)}}" alt="image" width="50"
                                 height="50">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group font-weight-bold">
                  <u>Short Description : </u>
                </div>
                <div class="col-md-12 form-group">
                      {!! $companyJob->short_description !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group font-weight-bold">
                  <u>Long Description : </u>
                </div>
                <div class="col-md-12 form-group">
                      {!! $companyJob->long_description !!}
                </div>
            </div>
            <div class="card-footer px-1">
                <a href="{{ route('backend.admin.career.job.index') }}" class="btn btn-primary"><i class="fa fa-backward pr-2"></i>
                    Back</a>
            </div>
        </div><!--card-->
@endsection
