@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Best Selling Products <small>Top 20 best selling products</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active">Best Selling Products</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Best Selling Products</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-primary table-bordered table-sm table-striped">
                                        <thead class="bg-gray">
                                        <tr>
                                            <td style="font-weight: bold;">ID#</td>
                                            <td style="font-weight: bold;">Image</td>
                                            <td style="font-weight: bold;">Product Name</td>
                                            <td style="font-weight: bold;">Total Liked Count</td>
                                            <td style="font-weight: bold;">Unit Price</td>
                                            <td style="font-weight: bold;">Total Qty. Sold</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($result['products'] as $product)
                                                <tr>
                                                    <td style="font-weight: bold;width: 50px;">{{$loop->iteration}})</td>
                                                    <td style="width: 100px;">
                                                        <img src="{{asset($product->image)}}" class="img-thumbnail" style="max-width: 100px;" alt="">
                                                    </td>
                                                    <td>{{$product->products_name}}</td>
                                                    <td style="width: 150px;">{{$product->products_liked}}</td>
                                                    <td style="width: 150px;">{{ $result['currency'][19]->value }} {{$product->products_price}}</td>
                                                    <td style="width: 150px;">{{$product->total}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td style="text-align: center;" colspan="6">No Record Available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
