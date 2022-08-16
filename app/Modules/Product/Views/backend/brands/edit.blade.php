@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-edit"></i> Edit Product Brand</h5>
            </div>
        </div>
        {!! Form::open(['route'=>array('admin.product.brands.update',\App\Libraries\Encryption::encodeId($productBrand->id)), 'method'=>'patch','enctype'=>'multipart/form-data','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    {!! Form::label('name','Name',['class'=>'required-star']) !!}
                    {!! Form::text('name',$productBrand->name,['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('website','Website',['class'=>'required-star']) !!}
                    {!! Form::text('website',$productBrand->website,['class'=>$errors->has('website')?'form-control is-invalid':'form-control required','rows'=>'2','placeholder'=>'Website link']) !!}
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('status','Status',['class'=>'font-weight-bold required-star']) !!}
                    {!! Form::select('status',[1 => 'Active',0 => 'Inactive'],$productBrand->status,['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::hidden('photo',$productBrand->photo) !!}
                    {{ Form::label('photo', 'Photo :',['class'=>'required-star']) }}
                    <br/>
                    <div>
                        <img class="img img-responsive img-thumbnail" src="{{ (!empty($productBrand->photo)? url('/uploads/brand-photos/'.$productBrand->photo):url('/assets/backend/img/photo.png')) }}" id="photoViewer" height="100" width="120">
                    </div>
                    <label class="btn btn-block btn-secondary btn-sm border-0" style="width: 120px;">
                        <input onchange="changePhoto(this)" type="file" name="photo" style="display: none" required>
                        <i class="fa fa-image"></i> Browse
                    </label>
                    <span id="photo_err" class="text-danger" style="font-size: 16px;"></span>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.product.brands.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
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
    </script>
@endsection
