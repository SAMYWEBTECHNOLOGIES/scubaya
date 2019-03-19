<?php
$accountType    =   [
        SHOP        => 'Shop',
        DIVE_CENTER => 'Dive Center',
        LIVEBOARD   => 'Liveboard',
        HOTEL       => 'Hotel'
];
?>

@if(session('message'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('message')}}
    </div>
@endif

<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
        @if(count($accountDetails) > 0)
            <thead>
            <tr>
                <th>Website Type</th>
                <th>Website Name</th>
                <th>Full Name</th>
                <th>Company Name</th>
                <th>Legal Id Number</th>
                <th>Vat Number</th>
                <th>status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($accountDetails as $details)
                @foreach($details as $detail)
                    <tr>
                        <td>{{ $accountType[$detail->website_type] }}</td>
                        <td>{{ $detail->name }}</td>
                        <td>{{ $detail->first_name.' '.$detail->last_name }}</td>
                        <td>{{ $detail->company_name }}</td>
                        <td>{{ $detail->legal_id_no }}</td>
                        <td>{{ $detail->vat_no }}</td>
                        <td>
                            <span class="{{$labelStatus[$detail->status]}} status">{{ ucwords(str_replace('_', ' ', $detail->status)) }}</span>
                        </td>
                        @if($detail->status == 'pending' || $detail->status == 'in_process')
                            <td>
                               <div class="inline-flex">
                                   <button type="button" class="button-blue btn btn-primary edit-button" data-toggle="modal" data-target="#sub-account-verification-edit-form-modal-{{$detail->id}}" @if($detail->status == 'in_process') disabled @endif>
                                       <i class="fa fa-pencil"></i>
                                   </button>

                                   <form method="post" class="padding-left5" action="{{ route('scubaya::merchant::'.strtolower(str_replace(' ', '_', $accountType[$detail->website_type])).'::delete_verification', [Auth::id(), $detail->id]) }}">
                                       {{ csrf_field() }}
                                       <button type="button" class="btn btn-danger delete-sub-request" id="delete-request" @if($detail->status == 'in_process') disabled @endif>
                                           <i class="fa fa-trash"></i>
                                       </button>
                                   </form>
                               </div>
                            </td>
                        @else
                            <td></td>
                        @endif
                    </tr>

                    @if($detail->status == 'pending')
                        @include('merchant.layouts.website_verification.edit_verification_modal'
                        , ['route' => 'scubaya::merchant::'.strtolower(str_replace(' ', '_', $accountType[$detail->website_type])).'::edit_verification'
                        ,'website' => $detail
                        , 'websiteType' => $accountType[$detail->website_type]]
                        )
                    @endif
                @endforeach
            @endforeach
            </tbody>
        @else
        <tr>
            <th class="text-center"> No Details Found.</th>
        </tr>
        @endif
    </table>
</div>

<script type="text/javascript">
    jQuery('.delete-sub-request').click(function (e) {
        e.preventDefault();
        bootbox.confirm({
            title:"Delete Confirmation",
            message: "Are you sure, you want to delete this?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            size: 'small',
            callback: function (result) {
                if(result) {
                    jQuery(e.currentTarget).parent().submit();
                }
            }
        });
    });
</script>

