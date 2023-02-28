@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.stores') }} <small>{{ trans('labels.ListingAllStores') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class=" active">{{ trans('labels.stores') }}</li>
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
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-6 form-inline">
                                                <form name='filter' id="registration" class="filter  " method="get" action="{{url('admin/stores/filter')}}">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <div class="input-group-form search-panel ">
                                                      <select type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" name="FilterBy" id="FilterBy" >
                                                        <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                                        <option value="Name" @if(isset($name)) @if  ($name == "Name") {{ 'selected' }} @endif @endif>{{trans('labels.Name')}}</option>
                                                        <option value="Email" @if(isset($name)) @if  ($name == "Email") {{ 'selected' }} @endif @endif>{{trans('labels.Email')}}</option>
                                                      </select>
                                                      <input type="text" class="form-control input-group-form " name="parameter" placeholder="{{trans('labels.Search')}}..." id="parameter" @if(isset($param)) value="{{$param}}" @endif>
                                                      <button class="btn btn-primary " type="submit" id="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                      @if(isset($param,$name))  <a class="btn btn-danger " href="{{URL::to('admin/stores/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                                    </div>
                                                </form>
                                                <div class="col-lg-4 form-inline" id="contact-form12"></div>
                                        </div>
                                    </div>
                                </div>

                            <div class="box-tools pull-right">
                                <a href="{{ URL::to('admin/stores/add') }}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNew') }}</a>
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
                                            <th>@sortablelink('storess_id', trans('labels.ID'))</th>
                                            <th>@sortablelink('store_name', trans('labels.Name'))</th>
                                            <th>@sortablelink('store_phone', trans('labels.Phone'))</th>
                                            <th>@sortablelink('store_email', trans('labels.Email'))</th>
                                            <th>@sortablelink('store_address', trans('labels.Address')) </th>
                                            <th>@sortablelink('status', trans('labels.Status')) </th>
                                            <th>{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($stores)>0)
                                            @foreach ($stores  as $key=>$store)
                                                <tr @if($store->status != '1') style="background: #fdeaea;" @endif>
                                                    <td>{{ $store->id }}</td>
                                                    <td>{{ $store->name }}</td>
                                                    <td>{{ $store->phone }}</td>
                                                    <td>{{ $store->email }}</td>
                                                    <td>{{ $store->address }}</td>
                                                    <td>@if($store->status == '1') Active @else Inactive @endif</td>
                                                    <td>
                                                        <a data-toggle="tooltip" data-placement="bottom" title="Edit" href="{{ URL::to('admin/stores/edit/'.$store->id)}}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                        <a id="storeFrom" stores_id='{{ $store->id }}' data-toggle="tooltip" data-placement="bottom" title="Delete" data-href="{{url('admin/stores/delete')}}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @else
                                            <tr>
                                                <td colspan="7">{{ trans('labels.NoRecordFound') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if($stores != null)
                                    <div class="col-xs-12 text-right">
                                        {{$stores->links()}}
                                    </div>
                                    @endif
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

            <!-- deleteManufacturerModal -->
            <div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="deleteStoreModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteStoreModalLabel">{{ trans('labels.DeleteStore') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/stores/delete', 'name'=>'deleteStore', 'id'=>'deleteStore', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('stores_id',  '', array('class'=>'form-control', 'id'=>'stores_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteStoreText') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('labels.Delete') }}</button>
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
