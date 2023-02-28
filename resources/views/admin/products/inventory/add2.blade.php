@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> {{ trans('labels.Inventory') }} <small>{{ trans('labels.Inventory') }}...</small> </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li><a href="{{ URL::to('admin/products/display') }}"><i class="fa fa-database"></i> {{ trans('labels.ListingAllProducts') }}</a></li>
            
            <li class="active">{{ trans('labels.Inventory') }}</li>
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
                        <h3 class="box-title">{{ trans('labels.addinventory') }} </h3>

                    </div>
                    <div class="box-body">
                            <div class="box-header">
                            <div class="col-lg-12 form-inline">
                                <form method="get" action="">
                                    <div class="input-group-form search-panel">
                                        <input type="text" class="form-control input-group-form" name="product_name" placeholder="Search Product Name" value="{{ Request::get('product_name') }}"/>
                                        <button class="btn btn-primary" id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

           
<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0" style="background: #d7d7d7;text-align: center;">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: black;font-size: 21px;font-weight: 800;width: 100%;">
                    Product Name
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Flavours</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Peach Vibe</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Total Purchase Price</th>
                                    <th class="text-center">Enter Stock</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Reference / Purchase Code</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                    <td class="text-center"><button type="button" class="btn btn-primary">Update</button></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                    <td class="text-center"><button type="button" class="btn btn-primary">Update</button></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                    <td class="text-center"><button type="button" class="btn btn-primary">Update</button></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                    <td class="text-center"><button type="button" class="btn btn-primary">Update</button></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                    <td class="text-center"><button type="button" class="btn btn-primary">Update</button></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="heading2">
            <h5 class="mb-0" style="background: #d7d7d7;text-align: center;">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2" style="color: black;font-size: 21px;font-weight: 800;width: 100%;">
                    Product Name 2
                </button>
            </h5>
        </div>

        <div id="collapse2" class="collapse " aria-labelledby="heading2" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Flavours</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Peach Vibe</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Total Purchase Price</th>
                                    <th class="text-center">Enter Stock</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Reference / Purchase Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="heading3">
            <h5 class="mb-0" style="background: #d7d7d7;text-align: center;">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3" style="color: black;font-size: 21px;font-weight: 800;width: 100%;">
                    Product Name 3
                </button>
            </h5>
        </div>

        <div id="collapse3" class="collapse " aria-labelledby="heading3" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Flavours</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Peach Vibe</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Total Purchase Price</th>
                                    <th class="text-center">Enter Stock</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Reference / Purchase Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Flavours</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">Size</td>
                                    <td class="text-center">Peach Vibe</td>
                                    <td class="text-center">Current Stock</td>
                                    <td class="text-center">Total Purchase Price</td>
                                    <td class="text-center">Enter Stock</td>
                                    <td class="text-center">Purchase Price</td>
                                    <td class="text-center">Reference / Purchase Code</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
                    </div>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </div>


    </section>
    <!-- /.row -->

    <!-- Main row -->
</div>

<!-- /.row -->

@endsection
