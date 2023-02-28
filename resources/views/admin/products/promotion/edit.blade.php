@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> {{ trans('labels.EditProduct') }} <small>{{ trans('labels.EditProduct') }}...</small> </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li><a href="{{ URL::to('admin/products/display')}}"><i class="fa fa-database"></i> {{ trans('labels.ListingAllProducts') }}</a></li>
            <li class="active">{{ trans('labels.EditProduct') }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->

        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
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
                            <!-- /.col -->
                            <div class="col-md-12">
                                <!-- Promotion LIST -->
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{ trans('labels.EditProduct') }}</h3>
                                        <div class="box-tools pull-right"></div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                    {!! Form::open(array('url' =>'admin/products/promotion/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                        {!! Form::hidden('id', $result['promotions'][0]->id , array('class'=>'form-control', 'id'=>'id')) !!}
                                        {!! Form::hidden('offer_type', $result['promotions'][0]->offer_type , array('class'=>'form-control', 'id'=>'offer_type')) !!}
                                        
                                            <!-- Products From -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ProductsFrom') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate promo_product promo_product_from" name="products_id_from">
                                                        <option value="">{{ trans('labels.Choose Product') }}</option>
                                                        @foreach ($result['products'] as $pro)
                                                            @if($result['promotions'][0]->product_id_from == $pro->products_id)
                                                                <option value="{{$pro->products_id}}" selected>{{$pro->products_name}}</option>
                                                            @else
                                                                <option value="{{$pro->products_id}}">{{$pro->products_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /Products From -->

                                            <!-- Product Attributes List -->
                                            <div id="attribute_from" style="display:none"></div>
                                            <!-- /.product attributes-list -->

                                            <!-- Products To -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ProductsTo') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate promo_product promo_product_to" name="products_id_to">
                                                        <option value="">{{ trans('labels.Choose Product') }}</option>
                                                        @foreach ($result['products'] as $pro)
                                                            @if($result['promotions'][0]->product_id_to == $pro->products_id)
                                                                <option value="{{$pro->products_id}}" selected>{{$pro->products_name}}</option>
                                                            @else
                                                                <option value="{{$pro->products_id}}">{{$pro->products_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /Products To -->
                                            <!-- Product Attributes List -->
                                                <div id="attribute_to" style="display:none"></div>
                                            <!-- /.product attributes-list -->
                                            
                                            <!-- Product Buying Price -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Buy Frequency') }}(X)<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="number" name="buy_frequency" value="{{$result['promotions'][0]->buy_frequency}}" class="form-control number-validate">
                                                </div>
                                            </div>
                                            <!-- Product Get Price -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Get Frequency') }}(Y)<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="number" name="get_frequency" value="{{$result['promotions'][0]->get_frequency}}" class="form-control number-validate">
                                                </div>
                                            </div>
                                            <!-- Start Date -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.promotion_date_from') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" name="start" value="{{date('d/m/Y', strtotime($result['promotions'][0]->start))}}" class="form-control field-validate datepicker"  data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            <!-- End Date -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.promotion_date_to') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" name="end" value="{{date('d/m/Y', strtotime($result['promotions'][0]->end))}}" class="form-control field-validate datepicker" data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            
                                            <!-- Offer Platform -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.offer_platform') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate" name="offer_platform">
                                                        <option value="">{{ trans('labels.Choose Platform') }}</option>
                                                        <option {{$result['promotions'][0]->offer_plateform == 'Web' ? 'selected' : ''}}>Web</option>
                                                        <option {{$result['promotions'][0]->offer_plateform == 'App' ? 'selected' : ''}}>App</option>
                                                        <option {{$result['promotions'][0]->offer_plateform == 'Both' ? 'selected' : ''}}>Both</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Offer Type -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.is_active') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate" name="is_active">
                                                        <option value="">{{ trans('labels.Status') }}</option>
                                                        <option value="1" {{$result['promotions'][0]->is_active == '1' ? 'selected' : ''}}>Yes</option>
                                                        <option value="0" {{$result['promotions'][0]->is_active == '0' ? 'selected' : ''}}>No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            @if(count($result['products'])> 0)
                                                @if($result['products'][0]->products_type==1 or $result['products'][0]->products_type==0)
                                                <!-- /.box-body -->
                                                <div class="box-footer text-center">
                                                    <button type="submit" id="attribute-btn" class="btn btn-primary">{{ trans('labels.EditPromotion') }}</button>
                                                </div>
                                                @endif
                                            @endif

                                        {!! Form::close() !!}
                                    </div>
                                    <!-- /.box-footer -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        <!-- Left col -->
                            <div class="box-footer col-xs-12">
                                @if(count($result['products'])> 0 && $result['products'][0]->products_type==1)
                                <a href="{{ URL::to("admin/products/attach/attribute/display/".$result['products'][0]->products_id) }}" class="btn btn-default pull-left">{{ trans('labels.AddOptions') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.row -->
    </section>
    <!-- Main row -->
</div>
<!-- /.row -->
@endsection