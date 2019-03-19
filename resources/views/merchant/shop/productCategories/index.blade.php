@extends('merchant.layouts.app')
@section('title','Product Categories')
@section('breadcrumb')
    <li><a href="#">Manage</a></li>
    <li class="active"><span>Product Categories</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="courses_section" class="padding-20">
        <div>
            <button type="button" class="pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#add-group-form">
                + New
            </button>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Product Categories</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($productCategories))
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Active</th>
                                <th>Category Name</th>
                                <th>Is Sub_category From</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($productCategories as $group)
                            <tr>
                                <td>{{$sno}}</td>
                                <td>{{$group->active}}</td>
                                <td>{{$group->name}}</td>
                                <td>{{$group->parent_id ? \App\Scubaya\model\ProductCategories::where('merchant_key', $authId)->where('id',$group->parent_id)->pluck('name')->first():'(Main)'}}</td>
                                {{-- <td>
                                     <form method="post" action="{{route('scubaya::admin::delete_group',[$group->id])}}">
                                         {{csrf_field()}}
                                         <button type="submit" class="btn btn-danger delete">
                                             <i class="fa fa-trash"></i>
                                         </button>
                                     </form>
                                 </td>--}}
                                <td>
                                    <button type="button" class="btn btn-primary edit-group" data-info='{"id":"{{$group->id}}","name":"{{$group->name}}","parent_id":"{{$group->parent_id}}","menu_ids":{{$group->menu_ids?$group->menu_ids:'[]'}},"name":"{{$group->name}}"}' data-toggle="modal" data-target="#edit-group-modal">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <th class="text-center"> No product category Available.</th>
                            </tr>
                    @endif
                </table>
                {{$productCategories->links()}}
            </div>
        </div>
    </section>

    {{--modal to create group--}}
    <div class="modal fade create-group" id="add-group-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Add New Product Category</h4>
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
                                <h4 class="login-box-msg text-center">Add a new Product Category</h4>
                                <form  method="post" action="{{route('scubaya::merchant::shop::add_category',[\Illuminate\Support\Facades\Auth::id()])}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Category Name" id="category_name" value="{{old('category_name')}}" name="category_name" autofocus required>
                                    </div>

                                    <div class="form-group">
                                        <div class="">
                                            <select class="form-control selectpicker show-tick" id="category_group" name="category_group" data-size="5">
                                                <option  value="0">(Main)</option>
                                                @if(count($productCategories))
                                                    @foreach($productCategories as $category)
                                                            <?php $subCategories  = \App\Scubaya\model\ProductCategories::where('merchant_key', $authId)
                                                                                                                        ->where('parent_id', $category->id)
                                                                                                                        ->get();
                                                            ?>
                                                            @if($category->parent_id == 0)
                                                            <option data-id="{{$category->id}}" value="{{$category->id}}">{{$category->name.'(main)'}}</option>
                                                            @endif

                                                            @if(count($subCategories))
                                                                @foreach($subCategories as $subCategory)
                                                                    <option  disabled data-parent="{{$subCategory->parent_id}}" value="{{ $subCategory->id }}">{{ ' - '. $subCategory->name }}</option>
                                                                @endforeach
                                                            @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
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

   {{-- --}}{{--modal to edit group--}}{{--
    <div class="modal fade bs-example-modal-md edit-group-modal" tabindex="-1" id="edit-group-modal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h3 class="text-center blue">Edit Group Details</h3>
                </div>
                <div class="modal-body">
                    <!-- The form is placed inside the body of modal -->
                    <form class="form-horizontal" id="edit_group" enctype="multipart/form-data" name="edit_group" method="post" action="{{ route('scubaya::admin::edit_group') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="category_name">Group Name</label>
                                    <div class="col-sm-8"><input autofocus type="text" class="form-control" name="category_name" required></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-5 col-md-offset-3">
                                        <select class="form-control selectpicker show-tick" data-size="5" id="edit_group_options" name="edit_group_options">
                                            <option selected value="0">(Main)</option>
                                            --}}{{--options via jquery organised--}}{{--
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="menus">Menus</label>
                                    <div class="col-sm-5 ">
                                        <select data-actions-box="true" data-selected-text-format="count > 2" class="form-control selectpicker show-tick" multiple id="menus" name="menus[]" data-size="5">
                                            @foreach($menus as $menu)
                                                <optgroup label="{{$menu->title}}">
                                                    @foreach(\Illuminate\Support\Facades\DB::table('merchant_menus')->where('parent_id',$menu->id)->get() as $submenu)
                                                        <option value="{{$submenu->id}}">{{$submenu->title}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <input type="hidden" name="old_category_name" value="">
                                <input type="hidden" name="id" value="">
                            </div>
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
    </div>--}}
    <script type="text/javascript">

        /*edit the group via model*/
        $('.edit-group').on('click',function(){
            var allData  =   $(this).data('info');
            console.log(allData);
            $('.edit-group-modal input[name=category_name]').val(allData.name);
            $('.edit-group-modal input[name=old_category_name]').val(allData.name);
            $('.edit-group-modal input[name=id]').val(allData.id);
            if(parseInt(allData.parent_id)){
                $('.edit-group-modal #edit_group_options').selectpicker('val', allData.parent_id);
            }else{
                $('.edit-group-modal #edit_group_options option:contains("'+allData.name+'(main)")').prop('selected', true);
            }
            $('.edit-group-modal #menus').selectpicker('val', allData.menu_ids);
            console.log(allData.menu_ids);

        });

        /*get the focus on selected*/
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        @if($errors->create->any())
        $(function() {
            $('#add-group-form').modal('show');
        });
        @endif


    </script>

@endsection