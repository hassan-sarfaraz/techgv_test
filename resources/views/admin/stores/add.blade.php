@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.store') }} <small>{{ trans('labels.AddStore') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/manufacturers/display')}}"><i class="fa fa-industry"></i> {{ trans('labels.stores') }}</a></li>
                <li class="active">{{ trans('labels.AddStore') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.AddStore') }} </h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info">
                                        <br>

                                        @if (session('update'))
                                            <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> {{ session('update') }} </strong>
                                            </div>
                                        @endif


                                        @if (count($errors) > 0)
                                            @if($errors->any())
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    {{$errors->first()}}
                                                </div>
                                        @endif
                                    @endif
                                    <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">
                                            {!! Form::open(array('url' =>'admin/stores/add', 'method'=>'post', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Name') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('name',  '', array('class'=>'form-control  field-validate', 'id'=>'name'), value(old('name'))) !!}
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="phone" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Phone') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('phone',  '', array('class'=>'form-control', 'id'=>'phone'), value(old('phone')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Email') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('email',  '', array('class'=>'form-control field-validate', 'id'=>'email'), value(old('email')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Address') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('address',  '', array('class'=>'form-control', 'id'=>'address'), value(old('address')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="store_lat" class="col-sm-2 col-md-3 control-label">{{ trans('labels.store_lat') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('store_lat',  '', array('class'=>'form-control', 'id'=>'store_lat'), value(old('store_lat')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="store_lng" class="col-sm-2 col-md-3 control-label">{{ trans('labels.store_lng') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('store_lng',  '', array('class'=>'form-control', 'id'=>'store_lng'), value(old('store_lng')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="status" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="status">
                                                        <option selected value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.submit') }}</button>
                                                <a href="{{ URL::to('admin/stores/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                            </div>
                                            <!-- /.box-footer -->
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
