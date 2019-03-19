@extends('admin.layouts.app')
@section('title','Boat Types')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Boat Types</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(Session::has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="row">
                <div>
                    <a href="{{route('scubaya::admin::manage::add_boat_type')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Boat Types
                        </button>
                    </a>
                </div>
            </div>
            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Boat Types</h3>
                </div>
                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#marine-life-table" placeholder="Filter by Names" />
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="marine-life-table">
                        @if(count($data))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Active</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $boat_types)
                                <tr>
                                    <td>{{$sno++}}</td>
                                    <td> <div class="btn-group colors boat-type" id="{{$boat_types->id}}" title="You can change the status here" onclick="changeStatus(this)" data-toggle="buttons" >
                                            <label class="btn btn-default btn-on btn-sm @if($boat_types->active) active @endif">
                                                <input type="radio" value="1" name="is_boat_active" @if($boat_types->is_boat_active == 1) checked @endif>Yes</label>

                                            <label class="btn btn-default btn-off btn-sm @if(!$boat_types->active) active @endif">
                                                <input type="radio" value="0" name="is_boat_active" @if($boat_types->is_boat_active == 0) checked @endif>NO</label>
                                        </div></td>
                                    <td>
                                    {{$boat_types->name}}
                                    </td>
                                    <td style="width: 100px;">
                                        @if($boat_types->image)
                                            <img src="{{asset('/assets/images/scubaya/boat_types/'.$boat_types->id.'-'.$boat_types->image)}}" class="img-responsive" alt="{{$boat_types->name}}" >
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td></td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::edit_boat_type',[$boat_types->id])}}">
                                            <button type="button" class="btn btn-primary submit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @else
                                <tr>
                                    <th class="text-center"> No Boat Types Available.</th>
                                </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$data->links()}}</div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">


        function changeStatus(data)
        {
            var id = data.id;

            $("#"+id +" input:radio").change(function() {
                var optionValue = $(this).val();
                //console.log('For the Id '+ id+' THE VALUE SELECTED IS'+optionValue);
                jQuery.ajax({
                    url:"{{route('scubaya::admin::manage::update_boat_active_status')}}",
                    method:'get',
                    data:{
                        boatId:id,
                        isActive:optionValue
                    }
                });
            });
        }
        $(document).ready(function() {

            $('.btn-group colors').on('click',function(){
                $(this).tooltip('enable');
            })
        });
    </script>
@endsection

