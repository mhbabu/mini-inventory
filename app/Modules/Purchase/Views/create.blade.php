@extends('backend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/dist/css/bootstrap-datepicker3.css') !!}
    {!! Html::style('assets/backend/dist/css/util.css') !!}
    {!! Html::style('assets/backend/dist/css/jquery-ui-smooth.css') !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <span class="card-title"><i class="fa fa-shopping-cart"></i> Purchase Products</span>
                </div><!--col-->
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'purchase.product.add.cart', 'method'=>'post','id'=>'purchaseForm']) !!}
            <div class="form-group">
                <div class="input-group">
                    {!! Form::text('add_product','',['class'=>'form-control required add_product','placeholder'=>'Enter Product Name / Double click','autocomplete'=>'off','required'=>true,'autofocus'=>true]) !!}
                    <span class="input-group-btn">
                        <button type="button" id="showProductList" class="btn btn-info rounded-0"><i class="fa fa-search-plus"aria-hidden="true"></i> </button>
                    </span>
                    <input type="hidden" id="productId" name="productId" value=""/>
                </div>
                <ul class="append hidden" id="productList"></ul>
            </div>
            {!! Form::close() !!}


            <div class="form-group">
                <label>Select Products:</label>
                <div class="table-responsive" id="purchaseOrderTable">
                    <table class="table table-bordered table-striped order-product-table">
                        <thead class="alert alert-info">
                        <tr>
                            <th>#</th>
                            <th>Product (Name-Code)</th>
                            <th width="12%">Stock</th>
                            <th width="12%">Price</th>
                            <th width="12%">Qty</th>
                            <th width="12%">Unit Price</th>
                            <th width="10%"><i class="fa fa-trash" aria-hidden="true"></i></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 0;
                        $total = 0;
                        if (Session::get('purchase.products')) {
                            $reverse_products = array_reverse(Session::get('purchase.products'));
                        }
                        ?>
                        @if(Session::get('purchase.products'))

                            @foreach($reverse_products as $product)
                                {!! Form::open(['route'=>'purchase.product.cart.editDelete', 'method'=>'post']) !!}
                                <tr>
                                    {!! Form::hidden('product_id',$product['id']) !!}
                                    {!! Form::hidden('product_name',$product['name']) !!}
                                    {!! Form::hidden('product_code',$product['code']) !!}
                                    {!! Form::hidden('price',$product['price']) !!}

                                    <td>{{++$i}}</td>
                                    <td class="text-left">{{ $product['name'] . " " . "(".$product['code'].")"  }}</td>
                                    <td class="text-left">{{ $product['stock'] }}</td>
                                    <td class="text-left">{{ $product['price'] }}</td>
                                    <td>
                                        <div class="input-group">
                                            {!! Form::number('quantity',$product['quantity'],['class'=>'form-control input-sm quantity text-right','maxlength'=>'1']) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            {!! Form::number('unit_price',$product['unit_price'],['class'=>'form-control input-sm quantity text-right']) !!}
                                        </div>
                                    </td>
                                    <td class="span2" style="text-align: center">
                                        <button title="Add" type="submit" class="hidden edit btn btn-primary btn-sm" name="edit_delete" value="edit"><i class="fa fa-save"></i></button>
                                        <button title="Remove" type="submit" class="btn btn-danger btn-sm" name="edit_delete" onclick="return confirm('Are you sure to remove product?');"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                @php $total+= $product['price'] * $product['quantity']; @endphp
                                {!! Form::close() !!}
                            @endforeach
                            <tr>
                                <td colspan="6">Total</td>
                                <td>{{ $total }} Tk</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="7" style="text-align:center; color:#f10505;"><strong>There are no products in the cart</strong></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--card-body-->
        {!! Form::open(['route'=>'purchases.store', 'method'=>'post']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                   <div class="form-group">
                       {{ Form::label('company_id','Company : ',['class'=>'required-star']) }}
                       {!! Form::select('company_id',$companies,'',['class'=>'required form-control company '.($errors->has('company_id')?'is-invalid':''),'placeholder'=>'Select company']) !!}
                   </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('supplier_id','Supplier : ',['class'=>'required-star']) !!}
                        {!! Form::select('supplier_id',$suppliers,'',['class'=>'required form-control supplier '.($errors->has('supplier_id')?'is-invalid':''),'placeholder'=>'Select supplier']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    {{ Form::label('purchase_date','Purchase Date : ',['class'=>'required-star']) }}
                    <div class="input-group">
                        {!! Form::text('purchase_date','',['class'=>'required form-control purchase_date '.($errors->has('purchase_date')?'is-invalid':''),'autocomplete'=>'off','placeholder'=>'YYYY-MM-DD']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('purchases.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            {!! Form::submit('Complete Purchase',['class'=>'btn btn-primary float-right']) !!}
        </div>
        {!! Form::close() !!}
    </div><!--card-->
@endsection

@section('footer-script')
    {!! Html::style('assets/backend/dist/css/bootstrap-datepicker.min.js') !!}
    {!! Html::script('assets/backend/dist/js/jquery-migrate-3.0.0.min.js') !!}
    {!! Html::script('assets/backend/dist/js/jquery-ui-1.10.2.js') !!}

    <script type="text/javascript">
        /****************************
         PRODUCT AUTO SUGGEST SCRIPT
         ****************************/
        $(".add_product").autocomplete({
            source: function (request, response) {
                $.ajax({
                    dataType: "json",
                    type: 'POST',
                    url: "{{ url('purchase/product/auto-suggest') }}",
                    data: request,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        console.log(data);

                        response($.map(data, function (value) {
                            return {
                                label: value.name + "-(" + value.code + ")",
                                value: value.name + "-(" + value.code + ")",
                                hitem: value.id
                            };
                        }));
                    },
                    error: function (data) {
                        console.log("error");
                    }
                });
            },
            autoFocus:true,
            matchContains: true,
            focus: function (event, ui) {
                $("#productId").val(ui.item.hitem);
            },
            select: function (event, ui) {
                $("#productId").val(ui.item.hitem);
                $("#purchaseForm").submit();
            }
        }).bind('dblclick', function () { $(this).autocomplete("search", "all"); });
        $('#showProductList').focus(function(event) {
            $(".add_product").autocomplete('search' , 'all');
            $(".add_product").focus();
        });

        /*******************
         DATE PICKER SCRIPT
         *******************/
        $('.purchase_date').datepicker({
            dateFormat:'yy-mm-dd'
        });

        /****************************************
         COMPANY WISE SUPPLIERS AUTOLOAD SCRIPT
         ***************************************/
        $(document).on("change", ".company", function () {
            let route = "/company-wise-suppliers";
            let targetHtml = '.supplier';
            companyWiseSupplier(this,route,targetHtml);
        });

        $(".company").trigger("change");
    </script>
@endsection
