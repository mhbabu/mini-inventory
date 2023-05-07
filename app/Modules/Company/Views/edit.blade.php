@extends('backend.layouts.modal')
@section('title') <h5><i class="fa fa-edit"></i> Company edit</h5> @endsection
@section('content')
    {!! Form::open(['route'=>array('companies.update',\App\Libraries\Encryption::encodeId($company->id)), 'method'=>'patch','id'=>'dataForm']) !!}
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('name','Name : ',['class'=>'required-star']) !!}
                    {!! Form::text('name',$company->name,['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('status','Status : ',['class'=>'required-star']) !!}
                    {!! Form::select('status',['1'=>'Active','0'=>'Inactive'],$company->status,['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('companies.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
        <button type="submit" class="btn float-right btn-primary"><i class="fa fa-save"></i> Update</button>
    </div>
    {!! Form::close() !!}
@endsection
