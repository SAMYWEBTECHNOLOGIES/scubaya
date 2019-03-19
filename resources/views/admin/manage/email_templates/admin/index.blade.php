@extends('admin.layouts.app')
@section('title','Email Templates')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Email Templates</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
                <div class="row">
                    <div>
                        <a href="{{route('scubaya::admin::manage::add_email_template',['admin'])}}">
                            <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                                Add Template
                            </button>
                        </a>
                    </div>
                </div>

            <div class="box box-primary margin-top-10">
                <div class="box-header">
                    <h3 class="box-title">Admin Email Templates</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover "style="margin-top: 15px" id="dev-table">
                        @if(count($adminEmailTemplates))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                    <th>Sender Name</th>
                                    <th>Sender Email</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $sno        =   1;
                                @endphp
                                @foreach($adminEmailTemplates as $adminEmailTemplate)
                                    <tr>
                                        <td>{{$sno++}}</td>
                                        <td>{{$adminEmailTemplate->name}}</td>
                                        <td>{{$adminEmailTemplate->action}}</td>
                                        <td>{{$adminEmailTemplate->sender_name}}</td>
                                        <td>{{$adminEmailTemplate->sender_email}}</td>
                                        <td>
                                            <form method="post" action="{{route('scubaya::admin::manage::delete_email_template',[$adminEmailTemplate->id])}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                           <a href="{{route('scubaya::admin::manage::edit_email_template',[$adminEmailTemplate->id])}}">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No Templates Data Available.</th>
                                </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection