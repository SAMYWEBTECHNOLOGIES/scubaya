@extends('admin.layouts.app')
@section('title','Activities')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Activities</span></li>
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
                    <a href="{{route('scubaya::admin::manage::activities::create')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Activity
                        </button>
                    </a>
                </div>
            </div>

            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Activities</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="activity">
                        @if(count($activities))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Non Diving</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>
                                        <img width="60" height="60" src="{{ asset('assets/images/scubaya/activities/'.$activity->id.'-'.$activity->icon) }}" alt="Scubaya-{{ $activity->icon }}">
                                    </td>
                                    <td>{{ $activity->name }}</td>
                                    <td>
                                        <button type="button" id="non-diving{{$activity->id}}" onclick="isNonDiving(this)" class="btn btn-toggle @if($activity->non_diving == 1) active @endif" data-toggle="button" aria-pressed="@if($activity->non_diving == 1) true @else false @endif">
                                            <div class="handle"></div>
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::activities::update',[$activity->id])}}">
                                            <button type="button" class="btn btn-primary submit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="inline-flex" method="post" action="{{route('scubaya::admin::manage::activities::delete',[$activity->id])}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger submit delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Activity Available.</th>
                            </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$activities->links()}}</div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function isNonDiving(data)
        {
            var id          = (data.id).replace ( /[^\d.]/g, '' );
            var aId         = parseInt(id, 10);
            var nonDiving   = jQuery('#'+data.id).attr('aria-pressed');
            var token       = '{{ csrf_token() }}';

            jQuery.ajax({
                url:"{{route('scubaya::admin::manage::activities::non_diving')}}",
                method:'post',
                data:{
                    aId:aId,
                    nonDiving:(nonDiving.trim() == 'true') ? 0 : 1,
                    _token:token
                }
            });
        }
    </script>
@endsection

