@extends('backend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/dist/css/dataTables.bootstrap4.min.css') !!}
    {!! Html::style('assets/backend/dist/css/buttons.dataTables.min.css') !!}
@endsection
@section('content')
    <ol class="breadcrumb alert alert-info p-2">
        <li class="breadcrumb-item"><strong>Purchase Date - </strong> <span>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('F d, Y') }}</span></li>
        <li class="breadcrumb-item"><strong>Purchase No - </strong> <span>{{ $purchase->purchase_no }}</span></li>
        <li class="breadcrumb-item"><strong>Company - </strong> <span>{{ $purchase->company_name }}</span></li>
        <li class="breadcrumb-item"><strong>Supplier - </strong> <span>{{ $purchase->supplier_name }}</span></li>
        <li class="breadcrumb-item"><strong>Purchase Total - </strong> <span>{{ $purchase->total }} Tk</span></li>
    </ol>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <span class="card-title"><i class="fa fa-list-alt"></i> Purchase Product Details </span>
                </div><!--col-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table table-striped table-borderless">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($purchaseDetails) > 0)
                            @foreach($purchaseDetails as $purchaseDetail)
                                <tr>
                                    <td>{{ ($purchaseDetail->product_name) ? $purchaseDetail->product_name : 'N/A' }}</td>
                                    <td>{{ ($purchaseDetail->product_code) ? $purchaseDetail->product_code : 'N/A' }}</td>
                                    <td>{{ ($purchaseDetail->price) ? $purchaseDetail->price.' Tk' : 'N/A' }}</td>
                                    <td>{{ ($purchaseDetail->quantity) ? $purchaseDetail->quantity : 'N/A' }}</td>
                                    <td>{{ ($purchaseDetail->unit_price) ? $purchaseDetail->unit_price.' Tk' : 'N/A' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"><strong class="text-bold">Purchase total </strong></td>
                                <td><strong class="text-bold">{{ $purchase->total }} Tk</strong></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div><!--row-->
        </div><!--card-body-->
        <div class="card-footer">
            <a href="{{ route('purchases.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
        </div>
    </div><!--card-->

@endsection

@section('footer-script')
    {!! Html::script('assets/backend/dist/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.bootstrap4.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.buttons.min.js') !!}

    <script type="text/javascript">
        $('table').DataTable();
    </script>
@endsection
