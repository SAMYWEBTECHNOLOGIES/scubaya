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
                    <button type="button" class="pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#add-email-template">
                        Add Template
                    </button>
                </div>
            </div>

            <div class="box box-primary margin-top-10">
                <div class="box-header">
                    <h3 class="box-title">Email Templates</h3>
                </div>

                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter templates groups" />
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover "style="margin-top: 15px" id="dev-table">
                        @if(count($groups))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $sno        =   1;
                                @endphp
                                @foreach($groups as $group)
                                    <tr>
                                        <td>{{$sno++}}</td>
                                        <td><a href="{{route('scubaya::admin::manage::edit_email_templates',[$group->id])}}">{{$group->name}}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No Templates Group Available.</th>
                                </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{--modal to create email template--}}
        <div class="modal fade" id="add-email-template" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                        <h4 class="modal-title custom_align" id="Heading">Add Template</h4>
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
                                    {{--<h4 class="login-box-msg text-center">Add Policy</h4>--}}
                                    <form  id="create_email_template" method="post" action="{{route('scubaya::admin::manage::add_email_template')}}">
                                        {{csrf_field()}}
                                        {{method_field('add_email_template')}}
                                        <label for="email_template">Select the group</label>
                                        <div class="form-group">
                                            <select class="form-control selectpicker show-tick" title="Select the group..." id="email_template" name="email_template" data-size="5">
                                                @foreach($groups as $group)
                                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success"><span class="fa fa-ok-sign"></span> Add</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">

        $("#create_email_template").validate({

            rules: {
                email_template: "required"
            },
            messages:{
                email_template:"Select a Group"
            }
        });

        @if($errors->create->any())
            $(function(){
                $('#add-email-template').modal('show');
            });
        @endif

    </script>
@endsection