@extends('user.layouts.app')
@section('title','Privacy Settings')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li class="active">Privacy Settings</li>
    </ol>
@endsection
@section('content')
    <div class="content">
        <div class="row margin-20">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Privacy Settings</h3>
                    </div>
                    <form method="post" action="{{route('scubaya::user::settings::privacy_settings',[Auth::id()])}}">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="row">

                                @if(Session::has('error'))
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        <p>{{ Session::get('error') }}</p>
                                    </div>
                                @endif
                                @if(Session::has('success_privacy_settings'))
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        <p>{{ Session::get('success_privacy_settings') }}</p>
                                    </div>
                                @endif


                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="user_profile">User Profile</label>
                                            <select id="user_profile" class="selectpicker form-control show-tick"
                                                    name="user_profile">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="diver_profile">Diver Profile</label>
                                            <select id="diver_profile" class="selectpicker form-control show-tick"
                                                    name="diver_profile">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}s">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="dive_log">Dive Log</label>
                                            <select id="dive_log" class="selectpicker form-control show-tick"
                                                    name="dive_log">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="my_reviews">My Reviews</label>
                                            <select id="my_reviews" class="selectpicker form-control show-tick"
                                                    name="my_reviews">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_details">Contact Details</label>
                                            <select id="contact_details" class="selectpicker form-control show-tick"
                                                    name="contact_details">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="photos">Photos</label>
                                            <select id="photos" class="selectpicker form-control show-tick"
                                                    name="photos">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="friends">Friends</label>
                                            <select id="friends" class="selectpicker form-control show-tick"
                                                    name="friends">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="emergency_info">Emergency Info</label>
                                            <select id="emergency_info" class="selectpicker form-control show-tick"
                                                    name="emergency_info">
                                                <option value="{{PUBLICC}}">Public</option>
                                                <option value="{{ONLY_ME}}">Only Me</option>
                                                <option value="{{FRIENDS}}">Friends</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@if($privacy_settings)
<script type="text/javascript">
    console.log("{{$privacy_settings->dive_log}}");
        jQuery(document).ready(function(scubaya) {
            scubaya('select[name=user_profile]').selectpicker('val',"{{$privacy_settings->user_profile}}");
            scubaya('select[name=diver_profile]').selectpicker('val',"{{$privacy_settings->diver_profile}}");
            scubaya('select[name=dive_log]').selectpicker('val',"{{$privacy_settings->dive_log}}");
            scubaya('select[name=my_reviews]').selectpicker('val',"{{$privacy_settings->my_reviews}}");
            scubaya('select[name=contact_details]').selectpicker('val',"{{$privacy_settings->contact_details}}");
            scubaya('select[name=photos]').selectpicker('val',"{{$privacy_settings->photos}}");
            scubaya('select[name=friends]').selectpicker('val',"{{$privacy_settings->friends}}");
            scubaya('select[name=emergency_info]').selectpicker('val',"{{$privacy_settings->emergency_info}}");
        });
</script>
@endif
@endsection