@extends('admin.layouts.app')
@section('title','Currency Settings')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Settings</a></li>
    <li><a href="{{route('scubaya::admin::currencies')}}">Currency</a></li>
    <li class="active"><span>Currency Settings</span></li>
@endsection
@section('content')
@php
    if(isset($currency_settings['api.currency.priority_list'])){
        $exchange_api       =   $currency_settings['api.currency.priority_list'];
    }
@endphp

<section id="hotel_information_section" class="container screen-fit">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Currency Settings</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        @if ($errors->any())
            <div class="row margin-top-10">
                <div class="col-md-4 col-md-offset-4 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        <div class="box-body">
            <form method="post" action="{{route('scubaya::admin::currency_settings')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <input type="hidden" value="" id="priority" name="priority">
                    <div class="col-md-12 priority">

                        <label for="api_priority" class="col-md-3 control-label">Exchanges</label>
                        <div class="form-group col-md-3">
                            <input class="api-checkbox" name="exchange_api" @if(isset($exchange_api) && $exchange_api=='api.fixer.io') checked @endif type="checkbox" value="api.fixer.io"><span>Fixer.io</span>
                        </div>

                        <div class="form-group col-md-3">
                            <input class="api-checkbox" name="exchange_api" @if(isset($exchange_api) && $exchange_api=='currencylayer.net') checked  @endif  type="checkbox" value="currencylayer.net"><span>Currencylayer.net</span>
                        </div>

                        <div class="form-group col-md-3">
                            <input class="api-checkbox" name="exchange_api" @if(isset($exchange_api) && $exchange_api=='xe.com') checked  @endif type="checkbox" value="xe.com"><span>Xe.com</span>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="tariff_default_price" class="col-md-3 control-label">Cron Job</label>
                        <div class="form-group col-md-4">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-on btn-sm @if(@$currency_settings['api.currency.job'] == 1 ) active @endif">
                                    <input type="radio" value="1" name="cron_job" @if(@$currency_settings['api.currency.job'] == 1 ) checked @endif>ON</label>

                                <label class="btn btn-default btn-off btn-sm @if(@$currency_settings['api.currency.job'] == 0 ) active @endif ">
                                    <input type="radio" value="0" name="cron_job" @if(@$currency_settings['api.currency.job'] == 0 ) checked @endif>OFF</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="api_key" class="col-md-3 control-label">Api Key Currency layer.net</label>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" value="{{$currency_settings['api.currency.currency_layer_key'] or '' }}" id="currency_layer_key" name="currency_layer_key">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="api_key" class="col-md-3 control-label">Api Key Xe.com</label>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" value="{{$currency_settings['api.currency.xe_key'] or '' }}" id="xe_key" name="xe_key">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <label for="api_key" class="col-md-3 control-label">Api Fixer Key</label>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" value="{{$currency_settings['api.currency.fixer_key'] or '' }}" id="fixer_key" name="fixer_key">
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{route('scubaya::admin::currencies')}}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $(".api-checkbox").on("click", function() {
            var numberOfCheckedCheckbox = $('input.api-checkbox:checkbox:checked').length;
            if (numberOfCheckedCheckbox > 1) {
                $(this).prop('checked', false);
            }
        });
    });
</script>

@stop