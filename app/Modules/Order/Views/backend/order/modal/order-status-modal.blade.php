@extends('backend.layouts.modal')
@section('title') <i class="fa fa-edit"></i> Order Status Edit @endsection
@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route'=>array('admin.orders.update',\App\Libraries\Encryption::encodeId($order->id)), 'method'=>'patch','enctype'=>'multipart/form-data','id'=>'dataForm']) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {{ Form::label('order_status','Order Status',['class'=>'col-sm-4 col-form-label required-star']) }}
                        <div class="col-sm-7 mr-sm-auto">
                            {!! Form::select('order_status',$orderStatus,$order->order_status,['class'=>$errors->has('order_status')?'form-control is-invalid':'form-control required','placeholder'=>'Select Status']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row float-right">
                <div class="form-group col-sm-12">
                    <button name="actionBtn" id="actionButton" type="submit" value="submit" class="actionButton btn btn-primary btn-sm w-100"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
