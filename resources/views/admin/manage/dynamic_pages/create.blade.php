@extends('admin.layouts.app')
@section('title','Add Dynamic Page')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::dynamic_pages')}}">Dynamic Pages</a></li>
    <li class="active"><span>Add Page</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Page</h3>
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

            <form id="dynamic_content_form" role="form" method="post" action="{{route('scubaya::admin::manage::add_page')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="active" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm ">
                                        <input type="radio" value="0" name="active">NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" data-toggle="tooltip">Display Name*</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="slug" data-toggle="tooltip">Slug*</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{old('slug')}}">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="dynamic_content" data-toggle="tooltip">Your Content goes here</label>
                                    <textarea class="form-control" id="dynamic_content" placeholder="Enter the html here" rows="15" name="dynamic_content">{{old('dynamic_content')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::dynamic_pages') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">

        $( "#dynamic_content_form" ).validate({
            rules: {
                name            :   "required",
                slug            :   "required",
                dynamic_content :   "required",
            },
            messages:{
                name            :   "Mention the name of the page",
                slug            :   "Give a unique slug",
                dynamic_content :   "Write the content here"
            }
        });

        $('#dynamic_content').summernote({
            height: 300,
        });

    </script>
@stop