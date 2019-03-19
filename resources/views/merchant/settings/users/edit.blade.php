@extends('merchant.layouts.app')
@section('title', 'Edit User')
@section('content')
    @include('merchant.layouts.mainheader')
    <?php
        $Roles          =   array();
        $isUserActive   =   0;
        $roles          =   (array)json_decode($user->group_id);
        $rights         =   (array)json_decode($user->sub_account_rights);

        $instructorRoleId   =   \App\Scubaya\model\Group::where('name', 'instructor')->value('id');

        foreach ($roles as $key => $value) {
            $isUserActive   =   $value->is_user_active;
            $Roles['main'][]    =   $key;

            if($key == $instructorRoleId) {
                if(isset($value->extra_role)) {
                    foreach ($value->extra_role as $role)
                        $Roles['extra'][$key][] = $role;
                }
            }
        }
    ?>
    @section('breadcrumb')
        <li><a href="#">Settings</a></li>
        <li><a href="{{route('scubaya::merchant::settings::users',[Auth::id()])}}">Users</a></li>
        <li class="active"><span>{{ App\Scubaya\model\User::decryptString($user->first_name) }}</span></li>
    @endsection

    <section id="edit_user_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit User</h3>
            </div>

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

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::settings::edit_user', [Auth::id(), $user->user_id]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="is_user_active" class="control-label" data-toggle="tooltip" title="Specify User Is Active Or Not">Active*</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if($isUserActive) active @endif">
                                        <input type="radio" value="1" name="is_user_active" @if($isUserActive) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(!$isUserActive) active @endif">
                                        <input type="radio" value="0" name="is_user_active" @if(!$isUserActive) checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user_first_name" class="control-label" data-toggle="tooltip" title="First Name Of User">First Name*</label>
                                <input type="text" name="user_first_name" class="form-control" value="{{ App\Scubaya\model\User::decryptString($user->first_name) }}" placeholder="Enter First name">
                            </div>

                            <div class="form-group">
                                <label for="user_last_name" class="control-label" data-toggle="tooltip" title="Last Name Of User">Last Name*</label>
                                <input type="text" name="user_last_name" class="form-control" value="{{ App\Scubaya\model\User::decryptString($user->last_name) }}" placeholder="Enter Last name">
                            </div>

                            <div class="form-group">
                                <label for="user_email" class="control-label" data-toggle="tooltip" title="Email Of User">Email*</label>
                                <input type="text" name="user_email" class="form-control" value="{{ App\Scubaya\model\User::decryptString($user->email) }}" placeholder="Enter Email">
                            </div>
                        </div>

                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="user_access_rights" class="control-label" data-toggle="tooltip" title="Add Boats">User Access Rights*</label>
                                {{--<select name="user_access_rights[]" class="form-control selectpicker" multiple data-live-search="true">
                                    @if(count($userGroups) > 0)
                                        @foreach($userGroups as $userGroup)
                                            <option value="{{ $userGroup->id }}" @if(in_array($userGroup->id, $Roles)) selected @endif>{{ ucwords($userGroup->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>--}}
                                <br>
                                @if(count($userGroups) > 0)
                                    @foreach($userGroups as $userGroup)
                                        <input type="checkbox" name="user_access_rights[]"  value="{{ $userGroup->id }}" @if(in_array($userGroup->id, $Roles['main'])) checked @endif>  <span>{{ ucwords($userGroup->name) }}</span><br>
                                    @endforeach
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="sub_account_access_rights" class="control-label" data-toggle="tooltip" title="Sub Account Access Rights">Sub Account Access Rights*</label>
                                <select data-actions-box="true" data-selected-text-format="count > 2" class="form-control selectpicker show-tick" multiple name="sub_account_access_rights[]" data-size="5">
                                    @if(count($subAccounts))
                                        @foreach($subAccounts as $website_type => $website_details)
                                            <optgroup label="{{$website_type}}">
                                                @foreach($website_details as $detail)
                                                    <option value="{{ $website_type.'.'.$detail->id }}" @if(!empty($rights[$website_type]) && in_array($detail->id, $rights[$website_type])) selected @endif>{{ ucwords($detail->name) }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::settings::users', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function () {
            $("input[type='checkbox']").on('change', function(){
                var mytext =  $(this).next('span').text();

                if(mytext.toLowerCase() == 'instructor'){

                    var container =  $(this).next('span');

                    if ($(this).prop('checked') == true){

                        var html    =   '<div class="instructor-role" style="padding-left: 20px">'+
                            '<input type="checkbox" id="dive-master" name="ins_additional_role['+ $(this).val() +'][]" value="{{ DIVE_MASTER }}" onclick="disableHiddenField(this)">'+
                            '<input type="hidden" name="ins_additional_role['+ $(this).val() +'][]" value="">'+
                            '<span>Dive Master</span><br>'+
                            '<input type="checkbox" id="dive-guide" name="ins_additional_role['+ $(this).val() +'][]" value="{{ DIVE_GUIDE }}" onclick="disableHiddenField(this)">'+
                            '<input type="hidden" name="ins_additional_role['+ $(this).val() +'][]" value="">'+
                            '<span>Dive Guide</span></div>';

                        $(html).insertAfter(container);
                        $('div.instructor-role + br').remove();

                    } else {

                        $('div.instructor-role').remove();
                        $('<br>').insertAfter(container);

                    }
                }
            });

            var extraRoles  =   [];

            @if(isset($Roles['extra']))
                extraRoles      =  JSON.parse('{!! json_encode($Roles['extra']) !!}');
            @endif

            var isDiveMaster            =  ' ';
            var isDiveMasterDisabled    =  ' ';
            var isDiveGuide             =  ' ';
            var isDiveGuideDisabled     =  ' ';

            $("input[type='checkbox']").each(function () {
                var mytext =  $(this).next('span').text();

                if(mytext.toLowerCase() == 'instructor' && $(this).prop('checked')) {
                    if(extraRoles && (extraRoles[$(this).val()]).indexOf('{{ DIVE_MASTER }}') >= 0) {
                        isDiveMaster            =   'checked';
                        isDiveMasterDisabled    =   'disabled'
                    }

                    if(extraRoles && (extraRoles[$(this).val()]).indexOf('{{ DIVE_GUIDE }}') >= 0) {
                        isDiveGuide         =   'checked';
                        isDiveGuideDisabled =   'disabled';
                    }

                    var container =  $(this).next('span');

                    var html      =  '<div class="instructor-role" style="padding-left: 20px">'+
                        '<input type="checkbox" id="dive-master" name="ins_additional_role['+ $(this).val() +'][]" '+isDiveMaster+' value="{{ DIVE_MASTER }}" onclick="disableHiddenField(this)">'+
                        '<input type="hidden" name="ins_additional_role['+ $(this).val() +'][]" '+isDiveMasterDisabled+' value="">'+
                        '<span>Dive Master</span><br>'+
                        '<input type="checkbox" id="dive-guide" name="ins_additional_role['+ $(this).val() +'][]" '+isDiveGuide+' value="{{ DIVE_GUIDE }}" onclick="disableHiddenField(this)">'+
                        '<input type="hidden" name="ins_additional_role['+ $(this).val() +'][]" '+isDiveGuideDisabled+' value="">'+
                        '<span>Dive Guide</span></div>';

                    $(html).insertAfter(container);
                    $('div.instructor-role + br').remove();
                }
            });
        });

        function disableHiddenField(data) {
            if(data.checked) {
                $('#'+data.id).next().attr('disabled', 'disabled');
            } else {
                $('#'+data.id).next().removeAttr('disabled');
            }
        }
    </script>
@endsection