@extends('backend.layouts.app')
@section('content')
    <!-- Info boxes -->
    <div class="row">
        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-3 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-industry"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Companies</span>
                    <span class="info-box-number">{{ $totalCompany }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-12 col-sm-3 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-users"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Suppliers</span>
                    <span class="info-box-number">{{ $totalSupplier }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-12 col-sm-3 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-shopping-cart"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Products</span>
                    <span class="info-box-number">{{ $totalProduct }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

    </div>
    <!-- /.row -->
@endsection
