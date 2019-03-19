@extends('merchant.layouts.app')
@section('title','Tax Rate')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Settings</a></li>
    <li class="active"><span>Tax Rate</span></li>
@endsection
@section('content')
    @include('merchant.layouts.mainheader')
    <section id="tax-rate-section" class="padding-20">
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        <div class="row">
            <a href="{{ route('scubaya::merchant::settings::create_tax_rate', [Auth::id()]) }}">
                <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#taxRateModal">
                    Add Tax Rate
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Tax Rate</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover" id="tax-rate-table">
                    @if(count($taxRates))
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Title</th>
                                <th>Country</th>
                                <th>Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($taxRates as $rate)
                            <tr>
                                <td>{{$sno++}}</td>
                                <td>{{$rate->title}}</td>

                                <?php
                                if(!empty($rate->country)) {
                                    $country    =   json_decode($rate->country);
                                }
                                ?>

                                <td>{{$country->name}}</td>
                                <td>{{$rate->rate}}</td>
                                <td>
                                    <div class="inline-flex">
                                        <form method="post" action="{{route('scubaya::merchant::settings::delete_tax_rate',[Auth::id(), $rate->id])}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        <a href="{{route('scubaya::merchant::settings::edit_tax_rate',[Auth::id(), $rate->id])}}" class="padding-left5">
                                            <button type="button" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <tr>
                            <th class="text-center"> No Tax Rates Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </section>

    @include('merchant.layouts.delete_script')
@endsection