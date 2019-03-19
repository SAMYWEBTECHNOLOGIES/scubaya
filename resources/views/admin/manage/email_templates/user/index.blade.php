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
                        <a href="{{route('scubaya::admin::manage::add_email_template',['user'])}}">
                            <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                                Add Template
                            </button>
                        </a>
                    </div>
                </div>

            <div class="box box-primary margin-top-10">
                <div class="box-header">
                    <h3 class="box-title">User Email Templates</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover "style="margin-top: 15px" id="dev-table">
                        @if(count($userEmailTemplates))
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
                                @foreach($userEmailTemplates as $userEmailTemplate)
                                    <tr>
                                        <td>{{$sno++}}</td>
                                        <td>{{$userEmailTemplate->name}}</td>
                                        <td>{{$userEmailTemplate->action}}</td>
                                        <td>{{$userEmailTemplate->sender_name}}</td>
                                        <td>{{$userEmailTemplate->sender_email}}</td>
                                        <td>
                                            <form method="post" action="{{route('scubaya::admin::manage::delete_email_template',[$userEmailTemplate->id])}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                           <a href="{{route('scubaya::admin::manage::edit_email_template',[$userEmailTemplate->id])}}">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="#">
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