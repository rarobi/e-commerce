@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-edit"></i> Edit Product Category</h5>
            </div>
        </div>
        {!! Form::open(['route'=>array('admin.product.categories.update',\App\Libraries\Encryption::encodeId($productCategory->id)), 'method'=>'patch','enctype'=>'multipart/form-data','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {!! Form::label('name','Name',['class'=>'required-star']) !!}
                    {!! Form::text('name',$productCategory->name,['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                </div>
                <div class="col-md-6 form-group">
                    {!! Form::label('status','Status',['class'=>'font-weight-bold required-star']) !!}
                    {!! Form::select('status',[1 => 'Active',0 => 'Inactive'],$productCategory->status,['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
                <div class="col-md-12 form-group">
                    {!! Form::label('description','Description',['class'=>'required-star']) !!}
                    {!! Form::textarea('description',$productCategory->description,['class'=>$errors->has('description')?'form-control is-invalid':'form-control required','rows'=>'5','placeholder'=>'Description']) !!}
                </div>
            </div>
            @include('backend.includes.photo-html')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.product.categories.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn float-right btn-primary"><i class="fa fa-save"></i> Update</button>
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

        /********************
         PHOTO DELETE HERE
         ********************/
        $(document.body).on('click','.action-delete',function(ev){
            ev.preventDefault();
            let URL = $(this).attr('href');
            let redirectURL = "{{ route('admin.product.categories.edit',\App\Libraries\Encryption::encodeId($productCategory->id)) }}";
            warnBeforeAction(URL, redirectURL);
        });
    </script>
@endsection
