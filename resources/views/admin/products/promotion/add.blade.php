@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> {{ trans('labels.promotion') }} <small>{{ trans('labels.ProductPromotion') }}...</small> </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li><a href="{{ URL::to('admin/products/display') }}">{{ trans('labels.ListingAllProducts') }}</a></li>
            @if($result['products'][0]->products_type==1)
            <li><a href="{{ URL::to('admin/products/attach/attribute/display/'.$result['products'][0]->products_id) }}">{{ trans('labels.AddOptions') }}</a></li>
            @endif
            <li class="active">{{ trans('labels.ProductPromotion') }}</li>
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
                                        <h3 class="box-title">{{ trans('labels.AddPromotion') }}</h3>
                                        <div class="box-tools pull-right"></div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        {!! Form::open(array('url' =>'admin/products/promotion/addnewpromotion', 'name'=>'promotionfrom', 'id'=>'addewpromotionfrom', 'method'=>'post', 'class' => 'form-horizontal form-validate')) !!}
                                            
                                            <!-- Products From -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ProductsFrom') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate promo_product_from" name="products_id_from">
                                                        <option value="">{{ trans('labels.Choose Product') }}</option>
                                                        @foreach ($result['products'] as $pro)
                                                        <option value="{{$pro->products_id}}">{{$pro->products_name}}</option>
                                                        @endforeach
                                                    </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.Product Type Text') }}.</span>
                                                </div>
                                            </div>
                                            <!-- Product Attributes List -->
                                            <div id="attribute_from" style="display:none"></div>
                                            <!-- /.product attributes-list -->

                                            <!-- Products From -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ProductsTo') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate promo_product_to" name="products_id_to">
                                                        <option value="">{{ trans('labels.Choose Product') }}</option>
                                                        @foreach ($result['products'] as $pro)
                                                        <option value="{{$pro->products_id}}">{{$pro->products_name}}</option>
                                                        @endforeach
                                                    </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.Product Type Text') }}.</span>
                                                </div>
                                            </div>
                                            <!-- Product Attributes List -->
                                            <div id="attribute_to" style="display:none"></div>
                                            <!-- /.product attributes-list -->
                                            

                                            <!-- Product Buying Price -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Buy Frequency') }}(X)<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="number" name="buy_frequency" value="" class="form-control number-validate">
                                                </div>
                                            </div>
                                            <!-- Product Get Price -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Get Frequency') }}(Y)<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="number" name="get_frequency" value="" class="form-control number-validate">
                                                </div>
                                            </div>
                                            <!-- Start Date -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.promotion_date_from') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" autocomplete="off" name="start" value="" class="form-control field-validate datepicker"  data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            <!-- End Date -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.promotion_date_to') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" autocomplete="off" name="end" value="" class="form-control field-validate datepicker" data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            
                                            <!-- Offer Platform -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.offer_platform') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate" name="offer_platform">
                                                        <option value="">{{ trans('labels.Choose Platform') }}</option>
                                                        <option>Web</option>
                                                        <option>App</option>
                                                        <option>Both</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Offer Type -->
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.is_active') }}<span style="color:red;">*</span> </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control field-validate" name="is_active">
                                                        <option value="">{{ trans('labels.Status') }}</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            @if(count($result['products'])> 0)
                                                @if($result['products'][0]->products_type==1 or $result['products'][0]->products_type==0)
                                                <!-- /.box-body -->
                                                <div class="box-footer text-center">
                                                    <button type="submit" id="attribute-btn" class="btn btn-primary">{{ trans('labels.AddPromotion') }}</button>
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
