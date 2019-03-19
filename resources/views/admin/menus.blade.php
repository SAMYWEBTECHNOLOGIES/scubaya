@extends('admin.layouts.app')
@section('title','Manage Menus')
@section('content')

    @component('admin.layouts.components.table_view',['title' => 'Manage Menus','placeholder'=> 'Filter Menus','target'=>'#add-menu-form','button'=>'Add Menu','show'=>false])
        <table class="table table-hover" id="dev-table">
            @if(count($data))
            <thead>
            <tr>
                <th>S.no</th>
                <th>Menu Name</th>
                <th>Parent</th>
                <th>Link</th>
                <th>Groups</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
            </thead>
            <tbody>

            @foreach($data as $menu)
                <tr>
                    <td>{{$sno++}}</td>
                    <td>{{$menu->name}}</td>
                    <td>{{$menu->parent_id ? \App\Scubaya\model\Menu::where('id',$menu->parent_id)->pluck('name')->first():'(Main)'}}</td>
                    <td>{{$menu->link}}</td>
                    <td>{{count(json_decode($menu->group_ids)) ? count(json_decode($menu->group_ids))>1 ? count(json_decode($menu->group_ids)).' groups selected':count(json_decode($menu->group_ids)).' group selected': 'None'}}</td>
                    <td>
                        <form method="post" action="{{route('scubaya::admin::delete_menu',[$menu->id])}}">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary edit-menu" data-info='{"id":"{{$menu->id}}","name":"{{$menu->name}}","parent_id":"{{$menu->parent_id}}","link":"{{$menu->link}}","group_ids":{{$menu->group_ids?$menu->group_ids:'[]'}}}' data-toggle="modal" data-target="#edit-menu-modal">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <th class="text-center"> No Menus Available.</th>
                </tr>
            @endif
            {{$data->links()}}
        </table>
    @endcomponent


    {{--modal to create menu--}}
    <div class="modal fade create-menu" id="add-menu-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Menu or Submenu</h4>
                </div>
                <div class="modal-body">
                    <div class="top-margin">
                        <div class="login-box">
                            @if ($errors->create->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        @foreach ($errors->create->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="login-box-body">
                                <h4 class="login-box-msg text-center">Add a new Menu or Submenu</h4>
                                <form  method="post" action="{{route('scubaya::admin::create_menu')}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Menu Name" id="menu_name" value="{{old('menu_name')}}" name="menu_name" autofocus required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Link(optional)" id="link" value="{{old('link')}}" name="link">
                                    </div>

                                    <div class="form-group">
                                        <div class="">
                                            <select class="form-control selectpicker show-tick" title="Select the menus to show..." id="menu" name="menu" data-size="5">
                                                <option selected value="0">(Main)</option>
                                                @foreach($menuData as $all)

                                                    <?php
                                                        $id   =   (array)$all->id;
                                                        $check  =   \App\Scubaya\model\Menu::whereIn('parent_id',$id)->pluck('id');
                                                        $count  =   1;
                                                        $dash   =   '-';
                                                    ?>
                                                    <option data-id="{{$all->id}}" value="{{$all->id}}">{{$all->name.'(main)'}}</option>

                                                    @while(count($check))
                                                        <?php

                                                        $count++;
                                                        $check      =   \App\Scubaya\model\Menu::whereIn('parent_id',$check)->pluck('id');
                                                        ?>
                                                    @endwhile

                                                    @for($i = 0;$i<$count;$i++)
                                                        <?php $submenus  =   \App\Scubaya\model\Menu::whereIn('parent_id',$id); ?>
                                                            @foreach($submenus->get() as $submenu)
                                                                <option data-parent="{{$submenu->parent_id}}" value="{{$submenu->id}}">{{$dash.$submenu->name}}</option>
                                                            @endforeach
                                                        <?php
                                                            $id   =   $submenus->pluck('id');
                                                            $dash =   $dash.'-';
                                                        ?>
                                                    @endfor

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control selectpicker show-tick" multiple data-actions-box="true" data-selected-text-format="count" data-size="5" title="Select the groups..." id="group" name="group[]">
                                            <option value="0">(Main)</option>
                                            @foreach($groups as $group)

                                                <?php
                                                    $id     =   (array)$group->id;
                                                    $check  =   \App\Scubaya\model\Group::whereIn('parent_id',$id)->pluck('id');
                                                    $count  =   1;
                                                    $dash   =   '-';
                                                ?>
                                                <option data-id="{{$group->id}}" value="{{$group->id}}">{{$group->name.'(main)'}}</option>

                                                @while(count($check))
                                                    <?php
                                                    $count++;
                                                    $check      =   \App\Scubaya\model\Group::whereIn('parent_id',$check)->pluck('id');
                                                    ?>
                                                @endwhile

                                                @for($i = 0;$i<$count;$i++)
                                                    <?php $children  =   \App\Scubaya\model\Group::whereIn('parent_id',$id); ?>
                                                    @foreach($children->get() as $child)
                                                        <option data-parent="{{$child->parent_id}}" value="{{$child->id}}">{{$dash.$child->name}}</option>
                                                    @endforeach
                                                    <?php
                                                    $id   =   $children->pluck('id');
                                                    $dash =   $dash.'-';
                                                    ?>
                                                @endfor

                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"><span class="fa fa-ok-sign"></span> Add</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    {{--modal to edit menu--}}
    <div class="modal fade bs-example-modal-md edit-menu-modal" tabindex="-1" id="edit-menu-modal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h3 class="text-center blue">Edit Menu</h3>
                </div>
                <div class="modal-body">
                    @if ($errors->edit->any())
                        <div class="alert alert-danger">
                            <ul>
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                @foreach ($errors->edit->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- The form is placed inside the body of modal -->
                    <form class="form-horizontal" id="edit_menu" enctype="multipart/form-data" name="edit_menu" method="post" action="{{ route('scubaya::admin::edit_menu') }}">
                        {{ csrf_field() }}
                        <div class="row">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="menu_name">Menu Name</label>
                                    <div class="col-sm-8"><input autofocus type="text" class="form-control" name="menu_name" required></div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="menu_link">Link</label>
                                    <div class="col-sm-8"><input type="text" class="form-control" name="menu_link" required></div>
                                </div>
                                {{--parent menu--}}
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="edit_menu_options">Parent Menu</label>
                                    <div class="col-sm-4 ">
                                        <select class="form-control selectpicker show-tick"  data-size="5" id="edit_menu_options" name="edit_menu_options">
                                            <option selected value="0">(Main)</option>
                                        </select>
                                    </div>
                                </div>
                                {{--multiple groups that are selected--}}
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="edit_group_options">Groups</label>
                                    <div class="col-sm-4">
                                        <select class="form-control selectpicker show-tick" multiple data-actions-box="true" data-selected-text-format="count" data-size="5" title="Select the groups..." id="edit_group_options" name="edit_group_options[]" data-size="5">
                                            <option value="0">(Main)</option>

                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="">
                        </div>
                        <div class="row" style="margin-left: 143px">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.edit-menu').on('click',function(){
            var allData  =   $(this).data('info');

            $('.edit-menu-modal input[name=menu_name]').val(allData.name);
            $('.edit-menu-modal input[name=menu_link]').val(allData.link);
            $('.edit-menu-modal input[name=id]').val(allData.id);

            if(parseInt(allData.parent_id)){
                $('.edit-menu-modal #edit_menu_options').selectpicker('val', allData.parent_id);
//                $('.edit-group-modal #edit_group_options').selectpicker('val', allData.parent_id);
            }else{
                $('.edit-menu-modal #edit_menu_options option:contains("'+allData.name+'(main)")').prop('selected', true);
            }
            if(allData.group_ids.length >0){
                $('.edit-menu-modal #edit_group_options').selectpicker('val', allData.group_ids);
            }else{
                $('.edit-menu-modal #edit_group_options').selectpicker('deselectAll');
            }
        });

        $(document).ready(function(){
            var getDataIds = [];
            $('.create-menu #menu option').each(function(){
                getDataIds.push($(this).data('id'));
            });

            getDataIds.join('|');

            var dataIds =   cleanArray(getDataIds);

            let options =   $('.create-menu #menu option').map(function(option){

                let id  =   $(this).data('id');

                if(typeof id != 'undefined'){
                    return '<option value="'+$(this).val()+'">'+$(this).text()+'<option>';
                }
            }).get();

            $('.create-menu #menu option').map(function(){
                let id  =   $(this).data('parent');
                let val =  parseInt($(this).val());

                if(typeof id != 'undefined'){
                    dataIds.splice(dataIds.indexOf(id)+1,0,val);
                    options.splice(dataIds.indexOf(id)+1,0,'<option value="'+$(this).val()+'">'+$(this).text()+'<option>');
                }
            });

            $('.create-menu #menu option').not(':selected').remove();
            $.each(options, function (i,option) {
                $('.create-menu #menu').append(option);
            });
            $('.create-menu #menu option').filter(function() {
                return !this.value || $.trim(this.value).length == 0;
            }).remove();


            $.each(options, function (i,option) {
                $('.edit-menu-modal #edit_menu_options').append(option);
            });
            $('.edit-menu-modal #edit_menu_options option').filter(function() {
                return !this.value || $.trim(this.value).length == 0;
            }).remove();

            /*to refresh the currently added options*/
            $('.selectpicker').selectpicker('refresh');

        });

        function cleanArray(actual) {
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                if (actual[i]) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }
        /*get the focus on selected*/
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        @if($errors->create->any())
            $(function() {
                $('#add-menu-form').modal('show');
            });
        @endif

        @if($errors->edit->any())
            $(function() {
                $('#edit-menu-modal').modal('show');
            });
        @endif
    </script>

@endsection