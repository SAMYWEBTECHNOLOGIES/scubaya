@extends('merchant.layouts.app')
@section('title', 'Add User By ID')
@section('content')
    @include('merchant.layouts.mainheader')
    @section('breadcrumb')
        <li><a href="#">Settings</a></li>
        <li><a href="{{route('scubaya::merchant::settings::users',[Auth::id()])}}">Users</a></li>
        <li class="active"><span>Add User By ID</span></li>
    @endsection

    <section id="create_new_user_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add User By ID</h3>
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

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::settings::create_user_by_id', [Auth::id()]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="form-group">
                                <label for="is_user_active" class="control-label" data-toggle="tooltip" title="Specify User Is Active Or Not">Active*</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('is_user_active') === '1') active @elseif(is_null(old('is_user_active'))) active @endif">
                                        <input type="radio" value="1" name="is_user_active" @if(old('is_user_active') === '1') checked @elseif(is_null(old('is_user_active'))) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('is_user_active') === '0') active @endif">
                                        <input type="radio" value="0" name="is_user_active" @if(old('is_user_active') === '0') checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user_id" class="control-label" data-toggle="tooltip" title="Specify User ID">Add By User ID*</label>
                                <input type="text" name="user_id" class="form-control" value="{{ old('user_id') }}" placeholder="Enter User ID">
                            </div>

                            <div class="form-group">
                                <label for="user_access_rights" class="control-label" data-toggle="tooltip" title="Add Boats">User Access Rights*</label>
                                {{--<select name="user_access_rights[]" class="form-control selectpicker" multiple data-live-search="true">
                                    @if(count($userGroups) > 0)
                                        @foreach($userGroups as $userGroup)
                                            <option value="{{ $userGroup->id }}">{{ ucwords($userGroup->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>--}}
                                <br>
                                @if(count($userGroups) > 0)
                                    @foreach($userGroups as $userGroup)
                                        <input type="checkbox" name="user_access_rights[]" value="{{ $userGroup->id }}">  <span>{{ ucwords($userGroup->name) }}</span><br>
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
                                                    <option value="{{ $website_type.'.'.$detail->id }}">{{ ucwords($detail->name) }}</option>
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
                    <button type="submit" class="btn btn-info pull-right">Create</button>
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
            })
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