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
                    <span class="card-title"><i class="fa fa-list-alt"></i> Stocks </span>
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
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($stocks) > 0)
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ ($stock->product_name) ? $stock->product_name : 'N/A' }}</td>
                                    <td>{{ ($stock->product_code) ? $stock->product_code : 'N/A' }}</td>
                                    <td>{{ ($stock->quantity) ? $stock->quantity : 'N/A' }}</td>
                                    <td>
                                        @if ($stock->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class='badge badge-danger'>Inactive</span>
                                        @endif
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
    </script>
@endsection
