@extends('admin.layouts.app')
@section('title','Edit Email Template')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::email_template')}}">Email Templates</a></li>
    <li class="active"><span>{{$group_name}}</span></li>

@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit {{$group_name}} Template</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if($errors->any())
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
            @if(count($email_templates))
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <ul class="nav nav-tabs" role="tablist">
                        @foreach($email_templates as $email_template)
                                <li role="presentation" @if ($loop->first) class="active" @endif><a href="#{{$email_template->name}}" aria-controls="{{$email_template->name}}" role="tab" data-toggle="tab">{{$email_template->name}}</a></li>
                        @endforeach
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style="min-height: 400px">
                            @foreach($email_templates as $email_template)
                                <div role="tabpanel" class="tab-pane @if ($loop->first) active @endif" id="{{$email_template->name}}">
                                    <form id="email_template_form" role="form" method="post" action="{{route('scubaya::admin::manage::edit_email_template',[$email_template->id])}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                                        <div class="btn-group" id="status" data-toggle="buttons">
                                                            <label class="btn btn-default btn-on btn-sm @if($email_template->active) active @endif">
                                                                <input type="radio" value="1" name="active" @if($email_template->active) checked @endif>YES</label>

                                                            <label class="btn btn-default btn-off btn-sm @if(!$email_template->active) active @endif">
                                                                <input type="radio" value="0" name="active" @if(!$email_template->active) checked @endif>NO</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="name" data-toggle="tooltip">Name*</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{$email_template->name}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="template_content" data-toggle="tooltip">Your Email Template Formation</label>
                                                            <textarea class="template_content form-control" id="template_content-{{$email_template->id}}" placeholder="Enter the html here" rows="25" name="template_content">{{$email_template->template_content}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="box-footer">
                                                <a href="{{ route('scubaya::admin::manage::email_template') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                                                <button type="submit" class="btn btn-info pull-right">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div>
                    <p class="text-center"> No Templates available.</p>
                </div>
            @endif
        </div>
    </section>

    <script type="text/javascript">

        $("#email_template_form").validate({
            rules: {
                name                :   "required",
                template_content    :   "required"
            },
            messages:{
                name                :   "Template Name is required",
                template_content    :   "Write the content here and put only {{' <content></content> '}} tag where you want to display content in template"
            }
        });

        {{-- script to active tab after redirecting page --}}
        jQuery('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
        });

        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            jQuery('a[href="' + activeTab + '"]').tab('show');
        }

        @foreach($email_templates as $email_template)
        $('#template_content-{{$email_template->id}}').summernote({
            height: 300,
        });
        @endforeach

    </script>
@stop