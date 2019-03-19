@extends('admin.layouts.app')
@section('title','Manage admins')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Manage Admins</span></li>
@endsection
@section('content')
    @if(count($admins))
        @component('admin.layouts.components.table_view',['title'=>'Manage Admins','placeholder'=> 'Filter admins','target'=>'#add-admin-form','button'=>'Add Admin','show'=>false])

        <table class="table table-hover" id="dev-table">
            <thead>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Email</th>
                <th>Block</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>

            @foreach($admins as $admin)
                <tr>
                    <td>{{$sno++}}</td>
                    <td>{{$admin->name}}</td>
                    <td>{{$admin->email}}</td>
                    @if(!($admin->email == 'mail@scubaya.com'))
                        <td><input data-id="{{$admin->id}}" @if($admin->block)checked @endif type="checkbox" name="my-checkbox"></td>
                        <td>
                            <form method="post" action="{{route('scubaya::admin::delete_admin',[$admin->id])}}">
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-danger delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    @else
                        <td>No Permission</td>
                        <td>No Permission</td>
                    @endif

                </tr>
            @endforeach
        </table>
        {{$admins->links()}}
        @endcomponent
        @else
            <section class="merchant_dashboard">
                <h2 class="text-center">No Admins,Click on button to Add Admin.</h2>
               <div class="container text-right"> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-admin-form">Add Admin</button></div>
            </section>
        @endif
    {{--Add admin model--}}
    <div class="modal fade" id="add-admin-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Add New Admin</h4>
                </div>
                <div class="modal-body">
                    <div class="top-margin">
                        <div class="login-box">
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

                            <div class="login-box-body">
                                <h1 class="login-box-msg text-center">Scubaya.com</h1>
                                <h4 class="login-box-msg text-center">Add a new Admin</h4>
                                <form id="add_admin" method="post" action="{{route('scubaya::admin::add_admin')}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" id="name" name="name" value="{{old('name')}}" autofocus required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Title" id="title" name="title" value="{{old('title')}}" autofocus required>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="{{old('email')}}" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" required>
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
        <!-- /.modal-dialog -->
       {{--model to delete popoup , right now not using it--}}
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger"><span class="fa fa-warning-sign"></span> Are you sure you want to delete this Admin?</div>

                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-success" ><span class="fa fa-ok-sign"></span> Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-remove"></span> No</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <link href="{{asset('plugins/bootstrap-switch/bootstrap-switch.min.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>

    <script type="text/javascript">
        /*block and unblock switch*/
        $("[name='my-checkbox']").bootstrapSwitch('size','mini');

        /*by ajax we block n unblock admin*/
        $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
            let id      =   $(this).attr('data-id');

            var token   = "{{ csrf_token() }}";
            var url     = "{{ route("scubaya::admin::block_admin")}}";

            $.post(url,{AdminId:id,state:state,_token:token}, function( id ){});
        });
        /*to show errors on modal*/
        @if($errors->any())
            $(function() {
                $('#add-admin-form').modal('show');
            });
        @endif

        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        $( "#add_admin" ).validate({
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
            }
        });

    </script>
@endsection