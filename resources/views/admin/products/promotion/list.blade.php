@extends('admin.layout')

@section('content')

    <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

            <h1> {{ trans('labels.promotion_list') }} <small>{{ trans('labels.ListingAllPromotions') }}...</small> </h1>
            <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li><a href="{{ URL::to('admin/products/display') }}">{{ trans('labels.Products') }}</a></li>
            <li class="active">{{ trans('labels.promotion') }}</li>
        </ol>

        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Info boxes -->
            <!-- /.row -->
            <div class="row">

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> {{ trans('labels.ListingAllPromotions') }} </h3>
                            <div class="box-tools pull-right">
                                <a href="{{ URL::to('admin/products/promotion/new')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddPromotion') }}</a>
                            </div>
                        </div>


                        <!-- /.box-header -->

                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    @if (count($errors) > 0)
                                        @if($errors->any())
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                {{$errors->first()}}
                                            </div>
                                        @endif
                                    @endif
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('labels.OfferID') }}</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">End</th>
                                            <th class="text-center">Buying Frequency</th>
                                            <th class="text-center">Get Frequency</th>
                                            <th class="text-center">Offer Platform</th>
                                            <th class="text-center">Is Active</th>
                                            <th class="text-center">{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($result)>0)
                                            @foreach($result as $data)
                                                <tr>
                                                    <td class="text-center">{{$data->id}}</td>
                                                    <td class="text-center">{{$data->get_promotion_product_details->products_name}}</td>
                                                    <td class="text-center">{{$data->start}}</td>
                                                    <td class="text-center">{{$data->end}}</td>
                                                    <td class="text-center">{{$data->buy_frequency}}</td>
                                                    <td class="text-center">{{$data->get_frequency}}</td>
                                                    <td class="text-center">{{$data->offer_plateform}}</td>
                                                    <td class="text-center">{{$data->is_active ? 'Yes' : 'No'}}</td>
                                                    
                                                    <td class="text-center">
                                                        <a data-toggle="tooltip" data-placement="bottom" title="Edit" href="{{url('admin/products/promotion/edit/'. $data->id) }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                        <a id="p_delete" promotion_id="{{$data->id}}" href="#" class="badge bg-red " ><i class="fa fa-trash" aria-hidden="true"></i></a>    
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- Main row -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteModalLabel">{{ trans('labels.Delete') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/products/promotion/promotion_delete', 'name'=>'deleteBanner', 'id'=>'deleteBanner', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'promotion_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteText') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                            <button type="submit" class="btn btn-primary" id="deleteBanner">{{ trans('labels.Delete') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
