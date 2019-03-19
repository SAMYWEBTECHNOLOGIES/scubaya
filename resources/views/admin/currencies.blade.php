@extends('admin.layouts.app')
@section('title','Currencies')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Settings</a></li>
    <li class="active"><span>Currency</span></li>
@endsection
@section('content')
    <div class="container screen-fit">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="row" style="margin-right: 0px;">
            <div>
                <button type="button" class="pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#add-currency-form">
                    Add Currency
                </button>
            </div>

            <div>
                <a href="{{route('scubaya::admin::currency_settings')}}">
                    <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                        Settings
                    </button>
                </a>
            </div>
        </div>

        <div class="box box-primary margin-top-10">

            <div class="box-header">
                <h3 class="box-title">Active Currencies</h3>
                {{--<div class="pull-right">--}}
                {{--<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">--}}
                {{--<i class="fa fa-filter"></i>--}}
                {{--</span>--}}
                {{--</div>--}}
            </div>

            <div class="panel-body">
                <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Currencies" />
            </div>

            <div class="box-body table-responsive no-padding">
                {{--table view goes here--}}
                <table class="table table-hover" id="dev-table">
                    @if(count($currencies))
                        <thead>
                            <tr>
                                <th></th>
                                <th>S.no</th>
                                <th>Currency Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php   $sno        =   1;

                        ?>
                        @foreach($currencies as $currency)
                            <tr>
                                <td></td>
                                <td>{{$sno++}}</td>
                                <td>{{$currency->name}}</td>
                                <td>
                                    <form method="post" action="{{route('scubaya::admin::delete_currency',[$currency->id])}}">
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-danger delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th class="text-center"> No Currencies Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
            <!-- modal display button -->
        </div>
    </div>

    {{--modal to create currency--}}
    <div class="modal fade create-currency" id="add-currency-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Currency</h4>
                </div>
                <div class="modal-body">
                    <div class="top-margin">
                        <div class="login-box">
                            @if ($errors->create->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        @foreach ($errors->create->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="login-box-body">
                                <h4 class="login-box-msg text-center">Add Policy</h4>
                                <form  method="post" action="{{route('scubaya::admin::create_currency')}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Currency" id="currency" value="{{old('menu_name')}}" name="currency" autofocus required>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"><span class="fa fa-ok-sign"></span> Add</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <script>
        /*get the focus on selected*/
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        @if($errors->create->any())
            $(function() {
                $('#add-currency-form').modal('show');
            });
        @endif
    </script>
@endsection