@extends('user.layouts.app')
@section('title','Account Settings')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li class="active">Account Settings</li>
    </ol>
@endsection
@section('content')
<div class="content">
    <div class="row margin-20">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Account Settings</h3>
                </div>
                <form id="account_settings_form" method="post" action="{{route('scubaya::user::settings::account_settings',[Auth::id()])}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if(Session::has('success_account_settings'))
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        <p>{{ Session::get('success_account_settings') }}</p>
                                    </div>
                                @endif

                                @if(Session::has('account_settings_error'))
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        <p>{{ Session::get('account_settings_error') }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_type" data-toggle="tooltip">Dive profile type</label>
                                    <select id="profile_type" class="selectpicker form-control show-tick" name="profile_type">
                                        <option value="male">Professional</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password" data-toggle="tooltip">Change Password</label>
                                    <input name="password" id="password" type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" data-toggle="tooltip">Confirm Password</label>
                                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4 col-md-offset-2 social-connection">
                                <h4 class="box-title">Social Connection</h4>
                                <ul class="scu-social-icons">
                                    <li><a href=" https://www.facebook.com/scubayacom/" target="_blank"><i class="big fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/scubayacom" target="_blank"><i class="big fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="big fa fa-google-plus"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="big fa fa-envelope"></i></a></li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <label>Additional roles</label>
                                @if(count($additionalRoles))
                                    @foreach($additionalRoles as $additionalRole)
                                        @php
                                            $merchant   =   \App\Scubaya\model\User::where('id', $additionalRole->merchant_id)
                                                                                    ->first(['email', 'first_name', 'last_name']);

                                            $roles      =   json_decode($additionalRole->group_id);
                                        @endphp
                                        <p>Merchant - {{ ucwords($merchant->first_name).' '.ucwords($merchant->last_name).' ('. $merchant->email .')' }}</p>
                                        <ul>
                                            @foreach($roles as $key => $value)
                                                @php
                                                    $roleName   =   \App\Scubaya\model\Group::where('id', $key)->value('name');
                                                @endphp
                                                <li class="padding-top-bottom-10">
                                                    {{ ucwords($roleName) }}
                                                    <br>
                                                    <input type="checkbox"
                                                           class="role-confirmation"
                                                           name="confirmed_role[{{$additionalRole->merchant_id}}][]"
                                                           value="{{ $key }}"
                                                           @if($value->confirmed) checked @endif>
                                                    <input type ="hidden"
                                                           class="role-confirmation-hidden"
                                                           name ="confirmed_role[{{$additionalRole->merchant_id}}][]">
                                                    I accept to be the {{ ucwords($roleName) }} of {{ ucwords($merchant->first_name).' '.ucwords($merchant->last_name) }}.
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                @endif
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
<script type="text/javascript">
    /*$( "#account_settings_form" ).validate({
        rules: {
            password: "required",
            password_confirmation: {
                equalTo: "#password"
            }
        },
        messages:{
            password_confirmation:{
                equalTo:"Password didnt match, enter again"
            }
        },
        errorClass: "invalid"
    });*/

    $('.role-confirmation').click(function () {
        if($(this).is(":checked")) {
            $(this).next().attr('disabled', 'disabled');
        } else {
            $(this).next().removeAttr('disabled', 'disabled');
        }
    });
</script>
@endsection