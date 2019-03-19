<div class="container screen-fit">
    @if($show)
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
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif
        <div>
            <button type="button" class="pull-right button-blue btn btn-primary" data-toggle="modal" data-target="{{$target}}">
                {{$button}}
            </button>
        </div>
        <div class="box box-primary margin-top-60">

                <div class="box-header">
                    <h3 class="box-title">{{$title}}</h3>
                </div>

                <div class="panel-body">
                    <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="{{$placeholder}}" />
                </div>

                <div class="box-body table-responsive no-padding">
                    {{--table view goes here--}}
                    {{$slot}}
                </div>
            <!-- modal display button -->
        </div>
</div>
