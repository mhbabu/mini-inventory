@extends('backend.layouts.modal')
@section('title') <h5><i class="fa fa-edit"></i> Supplier edit</h5> @endsection
@section('content')
    {!! Form::open(['route'=>array('suppliers.update',\App\Libraries\Encryption::encodeId($supplier->id)), 'method'=>'patch','id'=>'dataForm']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('company_id','Company : ',['class'=>'required-star']) !!}
                    {!! Form::select('company_id',$companies,$supplier->company_id,['class'=>$errors->has('company_id')?'form-control is-invalid':'form-control required','placeholder'=>'Select a company']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('name','Supplier Name : ',['class'=>'required-star']) !!}
                    {!! Form::text('name',$supplier->name,['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Supplier name']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('email','Email : ',['class'=>'required-star']) !!}
                    {!! Form::email('email',$supplier->email,['class'=>$errors->has('email')?'form-control is-invalid':'form-control required','placeholder'=>'Email address']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    {{ Form::label('mobile','Mobile',['class'=>'required-star']) }}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+88</span>
                        </div>
                        {!! Form::text('mobile',$supplier->mobile,['class'=>$errors->has('mobile')?'form-control is-invalid':'form-control required','minlength'=>'11','maxlength'=>'11','placeholder'=>'Mobile']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('address','Address : ',['class'=>'required-star']) !!}
                    {!! Form::textarea('address',$supplier->address,['class'=>$errors->has('address')?'form-control is-invalid':'form-control required','maxlength'=>'80','rows'=>1]) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('status','Status : ',['class'=>'required-star']) !!}
                    {!! Form::select('status',['1'=>'Active','0'=>'Inactive'],$supplier->status,['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('suppliers.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
        <button type="submit" class="btn float-right btn-primary" id="productCategorySubmit"><i class="fa fa-save"></i> Update</button>
    </div>
    {!! Form::close() !!}
@endsection
