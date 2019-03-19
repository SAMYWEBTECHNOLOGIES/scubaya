@extends('admin.layouts.app')
@section('title','Edit Email Template')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::user_email_template')}}">Email Templates</a></li>
    <li class="active"><span>{{$templateData->name}}</span></li>
@endsection
@section('content')
    @php
        $actions = config('email-actions.user');
    @endphp

    <section class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Email Template For User</h3>
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

            <form id="email_template_form" role="form" method="post" action ="{{route('scubaya::admin::manage::edit_email_template',[$templateData->id])}}"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="user_type" value="user">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name" data-toggle="tooltip">Name*</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$templateData->name}}">
                            </div>
                            <div class="form-group">
                                <label for="action" data-toggle="tooltip">Action*</label>
                                <select id="action" class="form-control selectpicker show-tick" name="action" title="Select Action">
                                    @foreach($actions as $action_key => $action)
                                        <option @if($templateData->action == $action_key) selected @endif data-variables="{{$action['variables']}}" value ="{{$action_key}}">{{$action['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subject" data-toggle="tooltip">Subject*</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{$templateData->subject}}">
                            </div>
                            <div class="form-group">
                                <label for="sender_name" data-toggle="tooltip">Sender Name*</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" value="{{$templateData->sender_name}}">
                            </div>
                            <div class="form-group">
                                <label for="sender_email" data-toggle="tooltip">Sender Email*</label>
                                <input type="text" class="form-control" id="sender_email" name="sender_email" value="{{$templateData->sender_email}}">
                            </div>
                            {{--<div class="form-group">--}}
                            {{--<label for="rules">Email template General tags</label>--}}
                            {{--<span id="rules">--}}
                            {{--<div>{firstname} for First Name</div>--}}
                            {{--<div>{lastname} for Last Name</div>--}}
                            {{--<div>{userphone} for User Phone</div>--}}
                            {{--<div>{useremail} for Email</div>--}}
                            {{--<div>{bookingsnumber} for Booking Number</div>--}}
                            {{--<div>{userid} for User Id</div>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="template_content" data-toggle="tooltip">Your Email Template
                                        Formation</label>
                                    <textarea class="form-control" id="template_content" placeholder="Enter the html here" rows="25" name="template_content">{{$templateData->template_content}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($templateData->action && $actions[$templateData->action]['variables'])
                        <div class="notes">
                            <p><strong>Note:</strong> {{ 'Use these general tags in the template. For example: &#123;&#123; $merchant->variable_name_listed_below &#125;&#125;' }}</p>
                            <ul id="variables">
                                @php
                                    $variables  =   explode(',', $actions[$templateData->action]['variables']);
                                @endphp

                                @foreach($variables as $variable)
                                    <li>{{ $variable }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::user_email_template') }}">
                            <button type="button" class="btn btn-default">Cancel</button>
                        </a>
                        <button type="submit" class="btn btn-info pull-right">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $('#template_content').summernote({
            height: 300,
        });

        $("#email_template_form").validate({
            rules: {
                name: "required",
                template_content: "required"
            },
            messages: {
                name: "Template Name is required",
                template_content: "Write the content here and put only {{' <content></content> '}} tag where you want to display content in template"
            }
        });

        $('#action').change(function(){
            $('#variables > li').remove();

            var selected    =   $(this).find('option:selected');
            var variables   =   selected.data('variables');

            if(variables) {
                variables       =   variables.split(',');

                $('.notes p').removeClass('hidden');

                var html    =   '';
                $.each(variables, function (k, v) {
                    html    +=  '<li>'+v+'</li>'
                });

                $('#variables').append(html);
            }
        });
    </script>
@endsection
