@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Search Console Report <small>Top 50 Google Search Terms</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active">Top 50 Google Search terms</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Top 50 Google Search terms</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-primary table-bordered table-sm table-striped">
                                        <thead class="bg-gray">
                                            <tr>
                                                <td style="font-weight: bold;">Terms</td>
                                                <td style="font-weight: bold;">Clicks</td>
                                                <td style="font-weight: bold;">Impressions</td>
                                                <td style="font-weight: bold;">CTR</td>
                                                <td style="font-weight: bold;">Avg. Position</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1. protien</td>
                                                <td>10</td>
                                                <td>100</td>
                                                <td>15%</td>
                                                <td>7</td>
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
