@extends('admin.layouts.app')
@section('title','Dive Sites')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Dive Sites</span></li>
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
                    <a href="{{route('scubaya::admin::manage::dive_sites::create')}}">
                        <button type="button" style="margin-right: 20px;" class="pull-right button-blue btn btn-primary">
                            Add Dive Site
                        </button>
                    </a>
                </div>
            </div>

            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Dive Sites</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="dive_site">
                        @if(count($diveSites))
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Active</th>
                                    <th>Need A Boat</th>
                                    <th>Diver Level</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($diveSites as $diveSite)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>
                                        @if($diveSite->image)
                                            <img width="100" height="60" src="{{ asset('assets/images/scubaya/dive_sites/'.$diveSite->id.'-'.$diveSite->image) }}" alt="Scubaya-{{ $diveSite->image }}">
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>{{ ucwords($diveSite->name) }}</td>
                                    <td>
                                        <button type="button" id="non-diving{{$diveSite->id}}" onclick="isActive(this)" class="btn btn-toggle @if($diveSite->is_active == 1) active @endif" data-toggle="button" aria-pressed="@if($diveSite->is_active == 1) true @else false @endif">
                                            <div class="handle"></div>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" id="need-a-boat{{$diveSite->id}}" onclick="needABoat(this)" class="btn btn-toggle @if($diveSite->need_a_boat == 1) active @endif" data-toggle="button" aria-pressed="@if($diveSite->need_a_boat == 1) true @else false @endif">
                                            <div class="handle"></div>
                                        </button>
                                    </td>
                                    <td>{{ $diveSite->diver_level ? ucwords($diveSite->diver_level) : '---'}}</td>

                                    @php
                                        $types  =   json_decode($diveSite->type);
                                    @endphp

                                    <td>
                                    @if($types)
                                        <ul>
                                        @foreach($types as $type)
                                                <li>{{ ucwords($type) }} Dive </li>
                                        @endforeach
                                        </ul>
                                    @else
                                        ---
                                    @endif
                                    </td>
                                    <td>
                                        <a href="{{route('scubaya::admin::manage::dive_sites::update',[$diveSite->id])}}">
                                            <button type="button" class="btn btn-primary submit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="inline-flex" method="post" action="{{route('scubaya::admin::manage::dive_sites::delete',[$diveSite->id])}}">
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
                                <th class="text-center"> No Dive Site Available.</th>
                            </tr>
                        @endif
                    </table>
                    <div class="text-center">{{$diveSites->links()}}</div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function isActive(data)
        {
            var id          = (data.id).replace ( /[^\d.]/g, '' );
            var dsId        = parseInt(id, 10);
            var isActive    = jQuery('#'+data.id).attr('aria-pressed');
            var token       = '{{ csrf_token() }}';

            jQuery.ajax({
                url:"{{route('scubaya::admin::manage::dive_sites::active')}}",
                method:'post',
                data:{
                    dsId:dsId,
                    isActive:(isActive.trim() == 'true') ? 0 : 1,
                    _token:token
                }
            });
        }

        function needABoat(data)
        {
            var id          = (data.id).replace ( /[^\d.]/g, '' );
            var dsId        = parseInt(id, 10);
            var needABoat   = jQuery('#'+data.id).attr('aria-pressed');
            var token       = '{{ csrf_token() }}';

            jQuery.ajax({
                url:"{{route('scubaya::admin::manage::dive_sites::needABoat')}}",
                method:'post',
                data:{
                    dsId:dsId,
                    needABoat:(needABoat.trim() == 'true') ? 0 : 1,
                    _token:token
                }
            });
        }
    </script>
@endsection

