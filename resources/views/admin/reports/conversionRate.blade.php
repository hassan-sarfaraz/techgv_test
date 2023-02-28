@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Conversion Rate Report <small>Google Analytics</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active">Conversion Rate Report</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Conversions</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-primary table-bordered table-sm table-striped">
                                        <thead class="bg-gray">
                                            <tr>
                                                <td style="font-weight: bold;">Device Category</td>
                                                <td style="font-weight: bold;" colspan="3">Acquisition</td>
                                                <td style="font-weight: bold;" colspan="3">Behavior</td>
                                                <td style="font-weight: bold;" colspan="4">Conversions</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td style="font-weight: bold;">Sessions</td>
                                                <td style="font-weight: bold;">% New Sessions</td>
                                                <td style="font-weight: bold;">New Users</td>
                                                <td style="font-weight: bold;">Bounce Rate</td>
                                                <td style="font-weight: bold;">Page / Session</td>
                                                <td style="font-weight: bold;">Avg. Session Duration</td>
                                                <td style="font-weight: bold;" colspan="2">Conversion Rate</td>
                                                <td style="font-weight: bold;">Completions</td>
                                                <td style="font-weight: bold;">Value</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center" colspan="2"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                                <td class="text-center"><strong>0</strong> <br /> % of Total 100.00%</td>
                                            </tr>
                                            <tr>
                                                <td>1. Desktop</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td colspan="2">0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>2. Mobile</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td colspan="2">0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>3. Tablet</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td colspan="2">0</td>
                                                <td>0</td>
                                                <td>0</td>
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
