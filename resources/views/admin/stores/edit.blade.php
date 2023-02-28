@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.store') }}  <small>{{ trans('labels.EditCurrentStore') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/listingStore')}}"><i class="fa fa-industry"></i> {{ trans('labels.Store') }}</a></li>
                <li class="active">{{ trans('labels.EditStore') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditStoreInfo') }} </h3>
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

                                            {!! Form::open(array('url' =>'admin/stores/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('id',  $editStore[0]->id , array('class'=>'form-control', 'id'=>'id')) !!}

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Name') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('name',  $editStore[0]->name , array('class'=>'form-control field-validate', 'id'=>'name'), value(old('name'))) !!}
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="phone" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Phone') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('phone',  $editStore[0]->phone, array('class'=>'form-control', 'id'=>'phone'), value(old('phone')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Email') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('email',  $editStore[0]->email, array('class'=>'form-control field-validate', 'id'=>'email'), value(old('email')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Address') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('address',  $editStore[0]->address, array('class'=>'form-control', 'id'=>'address'), value(old('address')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="store_lat" class="col-sm-2 col-md-3 control-label">{{ trans('labels.store_lat') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('store_lat',  $editStore[0]->store_lat, array('class'=>'form-control', 'id'=>'store_lat'), value(old('store_lat')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="store_lng" class="col-sm-2 col-md-3 control-label">{{ trans('labels.store_lng') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('store_lng',  $editStore[0]->store_lng, array('class'=>'form-control', 'id'=>'store_lng'), value(old('store_lng')))  !!}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="status" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="status">
                                                        <option @if($editStore[0]->status == 1) selected @endif value="1">Active</option>
                                                        <option @if($editStore[0]->status == 0) selected @endif value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
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
