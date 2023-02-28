@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Best Performing Categories <small>Top 20 best performing categories</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active">Best Performing Categories</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Best Performing Categories</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-primary table-bordered table-sm table-striped">
                                        <thead class="bg-gray">
                                        <tr>
                                            <td style="font-weight: bold;">ID#</td>
                                            <td style="font-weight: bold;">Category Name</td>
                                            <td style="font-weight: bold;">Total Visited</td>
                                            <td style="font-weight: bold;">Total Sales</td>
                                            <td style="font-weight: bold;">Total Ordered Amount</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="5">No Record available.</td>
                                        </tr>
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
