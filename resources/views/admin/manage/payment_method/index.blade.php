@extends('admin.layouts.app')
@section('title','Payment Methods')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Payment Methods</span></li>
@endsection
@section('content')
    <section>
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

            <div class="row">
                <div>
                    <a href="{{route('scubaya::admin::manage::payment_method::create')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Payment Method
                        </button>
                    </a>
                </div>
            </div>

            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Payment Methods</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="payment_method">
                        @if(count($paymentMethods))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($paymentMethods as $paymentMethod)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>
                                        <img width="80" height="60" src="{{ asset('assets/images/scubaya/payment_methods/'.$paymentMethod->id.'-'.$paymentMethod->icon) }}" alt="Scubaya-{{ $paymentMethod->icon }}">
                                    </td>
                                    <td>{{ $paymentMethod->name }}</td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::payment_method::update',[$paymentMethod->id])}}">
                                            <button type="button" class="btn btn-primary submit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="inline-flex" method="post" action="{{route('scubaya::admin::manage::payment_method::delete',[$paymentMethod->id])}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger submit delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Payment Method Available.</th>
                            </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$paymentMethods->links()}}</div>
                </div>
            </div>
        </div>
    </section>
@endsection

