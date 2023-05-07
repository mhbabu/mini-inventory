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
                    <span class="card-title"><i class="fa fa-list-alt"></i> Purchase Products </span>
                </div><!--col-->

                <div class="col-sm-7 pull-right">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('purchases.create') }}"
                           class="btn btn-sm btn-success ml-1"
                           data-toggle="tooltip"
                           title="Add Purchase Product"
                           data-original-title="Create New">
                            <i class="fa fa-plus-circle"></i> Purchase Product
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
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Company</th>
                            <th>Supplier</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($purchaseInfos) > 0)
                            @foreach($purchaseInfos as $purchaseInfo)
                                <tr>
                                    <td>{{ ($purchaseInfo->purchase_date) ? \Carbon\Carbon::parse($purchaseInfo->purchase_date)->format('d F,Y', 'H:i:s p') : 'N/A' }}</td>
                                    <td>{{ ($purchaseInfo->purchase_no) ? $purchaseInfo->purchase_no : 'N/A' }}</td>
                                    <td>{{ ($purchaseInfo->company_name) ? $purchaseInfo->company_name : 'N/A' }}</td>
                                    <td>{{ ($purchaseInfo->supplier_name) ? $purchaseInfo->supplier_name : 'N/A' }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ url('/purchases/'.\App\Libraries\Encryption::encodeId($purchaseInfo->id)) }}" title="Details">
                                            <i class="fa fa-list-alt"></i> Details
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
            let redirectURL = "{{ route('suppliers.index') }}";
            warnBeforeAction(URL, redirectURL);
        });
    </script>
@endsection
