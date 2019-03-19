@extends('admin.layouts.app')
@section('title','Homepage Content')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Home Page</span></li>
@endsection
@section('content')
    <section class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Homepage Content</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-12 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            <div class="box-body">
                <form method="post" id="dynamic_content_form" action="{{route('scubaya::admin::manage::home_page')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#subscription" aria-controls="subscription" role="tab" data-toggle="tab">Subscriptions section</a></li>
                                    <li role="presentation"><a href="#blog" aria-controls="blog" role="tab" data-toggle="tab">Blog section</a></li>
                                    <li role="presentation"><a href="#feature" aria-controls="feature" role="tab" data-toggle="tab">Features section</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content" style="min-height: 400px">
                                    <div role="tabpanel" class="tab-pane active" id="subscription">
                                        <div class="row content-padding">
                                            <div class ="col-md-8">
                                                <div class="from-group">
                                                    <label for="dynamic_content" data-toggle="tooltip">Add subscription content here</label>
                                                    <textarea class="form-control dynamic-content" id="dynamic_subscription_content" placeholder="Enter the html here" rows="15" name="dynamic_subscription_content">@if(isset($homepageContent->subscription_content)){{$homepageContent->subscription_content}} @endif</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="blog">
                                        <div class="row content-padding">
                                            <div class ="col-md-8">
                                                <div class="from-group">
                                                    <label for="dynamic_content" data-toggle="tooltip">Add blog content here</label>
                                                    <textarea class="form-control dynamic-content" id="dynamic_blog_content" placeholder="Enter the html here" rows="15" name="dynamic_blog_content">@if(isset($homepageContent->blog_content)) {{$homepageContent->blog_content}} @endif</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="feature">
                                        <div class="form-group row content-padding">
                                            <label for="feature_content" data-toggle="tooltip" class="col-md-8 col-form-label">Add features content here</label>
                                            <div class="col-md-4">
                                                <input type="button" id="add-more-feature" class = "btn btn-primary add-more-feature pull-left" value = "Add New">
                                            </div>
                                        </div>
                                        <div id="more_feature_row">
                                            <div class="row dynamic_features_content" style="padding: 10px">
                                                @if(isset($homepageContent->features_content) && json_decode($homepageContent->features_content) != null)
                                                    @foreach(json_decode($homepageContent->features_content) as $key=>$value)
                                                        <div class ="col-md-8">
                                                            <div class="from-group">
                                                                <textarea class="form-control dynamic-content" placeholder="Enter the html here" rows="15" name="dynamic_features_content[]">{{$value}}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class = "col-md-4">
                                                            <input type="button"  class = "btn btn-primary remove-feature" value = "Remove">
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class ="col-md-8">
                                                        <div class="from-group">
                                                            <textarea class="form-control dynamic-content" placeholder="Enter the html here" rows="15" name="dynamic_features_content[]"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class = "col-md-4">
                                                        <input type="button"  class = "btn btn-primary remove-feature" value = "Remove">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Tab panes -->
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::dashboard') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="text/javascript">

        $(document).ready(function() {

            $('.dynamic-content').summernote({
                height: 300,
            });

            $('#add-more-feature').click(function() {
                let featureHtml = '<div class="row dynamic_features_content" style="padding:10px">' +
                    '<div class ="col-md-8">' +
                    '<div class="from-group">' +
                    '<textarea class="form-control dynamic-content" placeholder="Enter the html here" rows="15" name="dynamic_features_content[]"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<div class = "col-md-4">' +
                    '<input type="button"  class = "btn btn-primary remove-feature" value = "Remove">' +
                    '</div>' +
                    '</div>';

                $('#more_feature_row').append(featureHtml);
                $('.dynamic-content').summernote({
                    height: 300,
                });
            });

            $('#more_feature_row').on('click','.remove-feature',function() {
                 $(this).parent().parent().remove();
            });
        });
    </script>
@endsection

