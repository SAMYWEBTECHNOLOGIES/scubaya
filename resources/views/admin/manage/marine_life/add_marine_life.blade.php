@extends('admin.layouts.app')
@section('title','Add Marine Life')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::marine_life')}}">Marine Life</a></li>
    <li class="active"><span>Add Marine Life</span></li>
@endsection
@section('content')
    <section class="container screen-fit">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Marine Life</h3>
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

            <form role="form" method="post" action="{{route('scubaya::admin::manage::add_marine_life')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm active">
                                        <input type="radio" value="1" name="active" checked>YES</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('course_repeat') === '0') active @endif">
                                        <input type="radio" value="0" name="active" @if(old('course_repeat') === '0') checked @endif>NO</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="common_name" data-toggle="tooltip">Common name</label>
                                <input type="text" class="form-control" id="common_name" name="common_name">
                            </div>

                            <div class="form-group">
                                <label for="scientific_name" data-toggle="tooltip">Scientific name</label>
                                <input type="text" class="form-control" id="scientific_name" name="scientific_name">
                            </div>

                            <div class="form-group">
                                <label for="main_image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload main image</label>
                                <input type="file" class="form-control" id="main_image" name="main_image" onchange="readURL(this);">
                            </div>

                            <div class="form-group">
                                <label for="max_images" data-toggle="tooltip" title="Upload Max 6 images"><i class="fa fa-upload" aria-hidden="true"></i>   Upload max 6 images</label>
                                <input type="file" class="form-control" id="max_images" name="max_images[]" multiple onchange="readURL(this);">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="from-group">
                                <label for="description">Description</label>
                                <textarea rows="8" class="form-control form-group" id="description" placeholder="Short Description" name="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::admin::manage::marine_life') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0] && input.files.length == 1 ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');

                };
                reader.readAsDataURL(input.files[0]);
            }else{
                var i =1;
                for(i;i<input.files.length+1;i++){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        jQuery(input).after('<img src="'+e.target.result+'" width="30%" height="30%">');
                    };
                    reader.readAsDataURL(input.files[i-1]);
                }
            }
        }
    </script>

@stop