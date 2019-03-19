@extends('admin.layouts.app')
@section('title','Add Affiliate')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li><a href="{{route('scubaya::admin::manage::affiliates')}}">Affiliations</a></li>
    <li class="active"><span>Add Affiliate</span></li>
@endsection
@section('content')
    <section id="create_room_section" class="padding-20">
        <div class="box box-primary padding-20" style="width:60%; margin: auto">

            <div class="box-header with-border">
                <h3 class="box-title">Add Affiliate</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-6 col-md-offset-3 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="add_affiliate"  method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="affiliation_name">Affiliation name</label>
                            <input type="text" class="form-control" placeholder="Affiliation Name" id="affiliation_name" value="{{old('affiliation_name')}}" name="affiliation_name" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="active" class="control-label" data-toggle="tooltip">Active</label><br>
                            <div class="btn-group" id="status" data-toggle="buttons">
                                <label class="btn btn-default btn-on btn-sm active">
                                    <input type="radio" value="1" name="active" checked>ON</label>

                                <label class="btn btn-default btn-off btn-sm">
                                    <input type="radio" value="0" name="active" >OFF</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="international" class="control-label" data-toggle="tooltip">International</label><br>
                            <div class="btn-group" id="status" data-toggle="buttons">
                                <label class="btn btn-default btn-on btn-sm active">
                                    <input type="radio" value="1" name="international" checked>ON</label>

                                <label class="btn btn-default btn-off btn-sm">
                                    <input type="radio" value="0" name="international">OFF</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" data-toggle="tooltip" title="Upload main image"><i class="fa fa-upload" aria-hidden="true"></i>   Upload image</label>
                        <input type="file" class="form-control" id="image" name="image" onchange="readURL(this);">
                    </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::admin::manage::affiliates') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0] && input.files.length == 1 ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(input).closest('div').find('img').remove();
                    jQuery(input).after('<img  src="'+e.target.result+'" width="30%" height="30%">');

                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#add_affiliate").validate({
            rules: {
                affiliation_name:"required"
            },
            messages:{
                affiliation_name:"Affiliation name is required"
            }
        });
    </script>
@endsection