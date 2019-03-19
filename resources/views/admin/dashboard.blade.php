@extends('admin.layouts.app')
@section('title','Dashboard')
@section('breadcrumb')
    <li><a href="#">Admin</a></li>
    <li class="active"><span>Dashboard</span></li>
@endsection

@section('content')

    <section class="merchant_dashboard">
        <h2 class="text-center">Admin Dashboard</h2>
    </section>
    {{--<script type="text/javascript">
        Lobibox.confirm({

        });
    </script>--}}
@endsection
