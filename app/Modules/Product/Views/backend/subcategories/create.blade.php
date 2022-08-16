@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-plus-square"></i> Add Product Subcategory</h5>
            </div>
        </div>
        {!! Form::open(['route'=>'admin.product.subcategories.store', 'method'=>'post','enctype'=>'multipart/form-data','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    {!! Form::label('name','Name',['class'=>'required-star']) !!}
                    {!! Form::text('name','',['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('category_id','Product Category',['class'=>'required-star']) !!}
                    {!! Form::select('category_id',$categories,'',['class'=>$errors->has('category_id')?'form-control is-invalid':'form-control required','placeholder'=>'Select product category']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('status','Status',['class'=>'font-weight-bold required-star']) !!}
                    {!! Form::select('status',[1=>'Active',0=>'Inactive'],'',['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
                <div class="col-md-12 form-group">
                    {!! Form::label('description','Description',['class'=>'required-star']) !!}
                    {!! Form::textarea('description','',['class'=>$errors->has('description')?'form-control is-invalid':'form-control required','rows'=>'5','placeholder'=>'Description']) !!}
                </div>
            </div>
            @include('backend.includes.photo-html')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.product.subcategories.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn float-right btn-primary"><i class="fa fa-save"></i> Save</button>
        </div>
        {!! Form::close() !!}
    </div><!--card-->
@endsection
@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            /**********************
             VALIDATION START HERE
             **********************/
            $('#dataForm').validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection
