@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-edit"></i> Edit Product</h5>
            </div>
        </div>
        {!! Form::open(['route'=>array('admin.products.update',\App\Libraries\Encryption::encodeId($product->id)), 'method'=>'patch','enctype'=>'multipart/form-data','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    {!! Form::label('name','Name',['class'=>'required-star']) !!}
                    {!! Form::text('name',$product->name,['class'=>'required form-control '.($errors->has('name')?'is-invalid':''),'placeholder'=>'Name']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('title','Title',['class'=>'required-star']) !!}
                    {!! Form::text('title',$product->title,['class'=>$errors->has('title')?'form-control is-invalid':'form-control required','placeholder'=>'Title']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('category_id','Category',['class'=>'required-star']) !!}
                    {!! Form::select('category_id',$categories,$product->category_id,['class'=>'categoryId required form-control '.($errors->has('category_id')?'is-invalid':''),'placeholder'=>'Select category']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('sub_category_id','Subcategory',['class'=>'required-star']) !!}
                    {!! Form::select('sub_category_id',$subCategories,$product->sub_category_id,['class'=>'subcategoryId required form-control '.($errors->has('sub_category_id')?'is-invalid':''),'placeholder'=>'Select subcategory']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('brand_id','Brand',['class'=>'required-star']) !!}
                    {!! Form::select('brand_id',$brands,$product->brand_id,['class'=>'required form-control '.($errors->has('brand_id')?'is-invalid':''),'placeholder'=>'Select brand']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('sku_id','Sku',['class'=>'required-star']) !!}
                    {!! Form::select('sku_id',$skus,$product->sku_id,['class'=>'required form-control '.($errors->has('sku_id')?'is-invalid':''),'placeholder'=>'Select sku']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('slug','Slug',['class'=>'required-star']) !!}
                    {!! Form::text('slug',$product->slug,['class'=>$errors->has('slug')?'form-control is-invalid':'form-control required','placeholder'=>'Slug']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('price','Price',['class'=>'required-star']) !!}
                    {!! Form::number('price',$product->price,['class'=>$errors->has('price')?'form-control is-invalid':'form-control required','placeholder'=>'Price']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('size','Size',['class'=>'required-star']) !!}
                    {!! Form::text('size',$product->size,['class'=>$errors->has('size')?'form-control is-invalid':'form-control required','placeholder'=>'Size']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('color','Color',['class'=>'required-star']) !!}
                    {!! Form::text('color',$product->color,['class'=>$errors->has('color')?'form-control is-invalid':'form-control required','placeholder'=>'Color']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('weight','Weight',['class'=>'required-star']) !!}
                    {!! Form::text('weight',$product->weight,['class'=>$errors->has('weight')?'form-control is-invalid':'form-control required','placeholder'=>'Weight']) !!}
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('tax','Tax',['class'=>'required-star']) !!}
                    {!! Form::number('tax',$product->tax,['class'=>$errors->has('tax')?'form-control is-invalid':'form-control required','placeholder'=>'Tax']) !!}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('is_feature_product','Featured Product',['class'=>'required-star']) !!}
                    <div class="d-flex d-flex-inline mt-2">
                        <div class="form-group col-sm-4">
                            {!! Form::radio('is_feature_product', 1,($product->is_feature_product)? 1 : '', ['id'=>'yes']) !!}
                            {!! Form::label('yes', 'Yes') !!}
                        </div>
                        <div class="form-group col-sm-4">
                            {!! Form::radio('is_feature_product', 0, (!$product->is_feature_product)? true : '', ['id'=>'no']) !!}
                            {!! Form::label('no', 'No') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('status','Status',['class'=>'font-weight-bold required-star']) !!}
                    {!! Form::select('status',[1 =>'Active',0 =>'Inactive'],$product->status,['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
                <div class="col-md-12 form-group">
                    {!! Form::label('short_description','Short Description',['class'=>'required-star']) !!}
                    {!! Form::textarea('short_description',$product->short_description,['class'=>$errors->has('short_description')?'form-control is-invalid':'form-control required','rows'=>'5','placeholder'=>'Short description']) !!}
                </div>
                <div class="col-md-12 form-group">
                    {!! Form::label('long_description','Long Description',['class'=>'required-star']) !!}
                    {!! Form::textarea('long_description',$product->long_description,['class'=>$errors->has('long_description')?'form-control is-invalid':'form-control required','rows'=>'5','placeholder'=>'Long description']) !!}
                </div>
            </div>
            @include('backend.includes.photo-html')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.products.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
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
            let redirectURL = "{{ route('admin.products.edit',\App\Libraries\Encryption::encodeId($product->id)) }}";
            warnBeforeAction(URL, redirectURL);
        });
    </script>
@endsection
