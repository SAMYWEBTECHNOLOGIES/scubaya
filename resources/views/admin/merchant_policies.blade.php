@extends('admin.layouts.app')
@section('title','Merchant Policy')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Merchants</a></li>
    <li class="active"><span>Policies</span></li>
@endsection
@section('content')
        @component('admin.layouts.components.table_view',['title' => 'Merchant Policy','placeholder'=> 'Filter Policy','target'=>'#add-policy-form','button'=>'Add Policy','show'=>true])
            <table class="table table-hover" id="dev-table">
                @if(count($data))
                <thead>
                    <tr>
                    <th>S.no</th>
                    <th>Policy Name</th>
                    <th>Published</th>
                    <th>Association</th>
                    <th></th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
            <?php   $sno        =   1;

            ?>
            @foreach($data as $policy)
                <tr>
                    <td>{{$sno++}}</td>
                    <td>{{$policy->name}}</td>
                    <td>{!!$policy->published?'<span class="fa fa-check"></span>':'<span class="fa fa-remove"></span>'!!}</td>
                    <td>{{ucfirst(str_replace('_',' ',$policy->merchant))}}</td>
                    <td>
                        <form method="post" action="{{route('scubaya::admin::delete_merchant_policy',[$policy->id])}}">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-danger delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary edit-policy" data-info='{"id":"{{$policy->id}}","name":"{{$policy->name}}","published":"{{$policy->published}}","merchant":"{{$policy->merchant}}"}' data-toggle="modal" data-target="#edit-policy-modal">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <th class="text-center"> No Policies Available.</th>
                </tr>
            @endif
        </table>
    @endcomponent


    {{--modal to create policy--}}
    <div class="modal fade create-policy" id="add-policy-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Merchant Policies</h4>
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
                                <h4 class="login-box-msg text-center">Add Policy</h4>
                                <form  method="post" action="{{route('scubaya::admin::create_merchant_policy')}}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Policy Name" id="policy_name" value="{{old('policy_name')}}" name="policy_name" autofocus required>
                                    </div>

                                    <select class="form-control selectpicker show-tick" id="merchant_select" name="merchant_select" data-size="5">
                                        <option value="dive_center">Dive Center</option>
                                        <option value="hotel">Hotel</option>
                                        <option value="liveaboard">Live Aboard</option>
                                    </select>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label for="published"><input type="checkbox" name="published" value="1">Published</label>
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

    {{--modal to edit policy--}}
    <div class="modal fade bs-example-modal-md edit-policy-modal" tabindex="-1" id="edit-policy-modal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h3 class="text-center blue">Edit Policy</h3>
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
                        <form class="form-horizontal" id="edit_policy" enctype="multipart/form-data" name="edit_policy" method="post" action="{{ route('scubaya::admin::edit_merchant_policy') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label" for="policy_name">Policy Name</label>
                                    <div class="col-sm-8"><input autofocus type="text" class="form-control" name="policy_name" required></div>
                                </div>
                            </div>
                            <div class="row">
                                <label  class="col-sm-3 control-label" for="merchant_select">Merchant</label>
                                <div class="form-group col-sm-4">
                                    <select class="form-control selectpicker show-tick " id="merchant_select" name="merchant_select">
                                    <option value="{{DIVE_CENTER}}">{{ucfirst(str_replace('_',' ',DIVE_CENTER))}}</option>
                                    <option value="{{LIVEBOARD}}">{{ucfirst(LIVEBOARD)}}</option>
                                </select>
                                </div>
                                <input type="hidden" name="id" value="">
                            </div>
                            <div class="row">
                               <div class="col-md-offset-3">
                                <div class="form-group">
                                    <div class="checkbox ">
                                        <label for="published"><input type="checkbox" name="published" value="1">Published</label>
                                    </div>
                                </div>
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
    </div>

    <script>
        /*edit modal of policy*/
        $('.edit-policy').on('click',function(){
            var allData  =   $(this).data('info');

            $('.edit-policy-modal input[name=policy_name]').val(allData.name);

            $('.edit-policy-modal input[name=id]').val(allData.id);
            $('.edit-policy-modal #merchant_select').selectpicker('val',allData.merchant);

            if(parseInt(allData.published) ){
                $('.edit-policy-modal input[name=published]').prop('checked', true);
            }


        });

        /*get the focus on selected*/
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        @if($errors->create->any())
        $(function() {
            $('#add-policy-form').modal('show');
        });
        @endif

        @if($errors->edit->any())
        $(function() {
            $('#edit-policy-modal').modal('show');
        });
        @endif
    </script>
@endsection