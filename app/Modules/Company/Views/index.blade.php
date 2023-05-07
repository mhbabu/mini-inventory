@extends('backend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/dist/css/dataTables.bootstrap4.min.css') !!}
    {!! Html::style('assets/backend/dist/css/buttons.dataTables.min.css') !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h5><i class="fa fa-list-alt"></i> Companies</h5>
                </div><!--col-->

                <div class="col-sm-7 pull-right">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-success ml-1 AppModal"
                           data-toggle="modal" data-target="#AppModal" title="Create new" data-original-title="Create New">
                            <i class="fa fa-plus-circle"></i> Create
                        </a>
                    </div>
                </div><!--col-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table table-striped table-borderless">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($companies) > 0)
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ ($company->name) ? $company->name : 'N/A' }}</td>
                                    <td>
                                        @if ($company->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class='badge badge-danger'>Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/companies/'.\App\Libraries\Encryption::encodeId($company->id)).'/edit/' }}"
                                           class="btn btn-sm btn-primary AppModal" title="Edit" data-toggle='modal' data-target='#AppModal'>
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ url('/companies/'.\App\Libraries\Encryption::encodeId($company->id)).'/delete/' }}"
                                           redirect-url="{{ url('/companies') }}" class="btn btn-sm btn-danger action-delete" title="Delete">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
    @include('backend.includes.modal-dialogue-md')

@endsection

@section('footer-script')
    {!! Html::script('assets/backend/dist/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.bootstrap4.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.buttons.min.js') !!}

    <script type="text/javascript">
        $('table').DataTable();

        $(document.body).on('click','.action-delete',function(ev){
            ev.preventDefault();
            let URL = $(this).attr('href');
            let redirectURL = "{{ route('companies.index') }}";
            warnBeforeAction(URL, redirectURL);
        });
    </script>
@endsection
