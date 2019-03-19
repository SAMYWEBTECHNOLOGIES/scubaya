
@if(session('message'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('message')}}
    </div>
@endif

@php
    $rejectedDocuments  =   array();
@endphp

<div>
    <button type="button" id="new_request" class=" pull-right button-blue btn btn-primary" data-toggle="modal" data-target="#verification-form-modal">
        New Request
    </button>
</div>

<div class=" margin-top-60">
    <!-- / box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            @if(count($merchantDetails) > 0)
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Id</th>
                    <th>Company Type</th>
                    <th>Company Id</th>
                    <th>Full Name</th>
                    <th>Date Of Birth</th>
                    <th>Street</th>
                    <th>Postal Code</th>
                    <th>City</th>
                    <th>status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php $sno   =   1; @endphp
                @foreach($merchantDetails as $m)
                    <tr>
                        <td>{{ $sno }}</td>
                        <td>{{ \App\Scubaya\model\User::getUID($m->merchant_primary_id) }}</td>
                        <td>{{ $m->company_type }}</td>
                        <td>{{ $m->company_id }}</td>
                        <td>{{ $m->full_name }}</td>
                        <td>{{ $m->dob }}</td>
                        <td>{{ $m->address }}</td>
                        <td>{{ $m->postal_code }}</td>
                        <td>{{ $m->city }}</td>
                        @php
                            /*$status   =   \Illuminate\Support\Facades\DB::table('merchant_details')
                                        ->join('merchants_x_merchants_documents','merchant_details.id','merchants_x_merchants_documents.merchant_detail_id')
                                        ->select('merchants_x_merchants_documents.status')
                                        ->where('merchants_x_merchants_documents.merchant_primary_id', $authId)
                                        ->value('status');*/

                            $status =   $m->status;

                            //if($m->status == MERCHANT_STATUS_REJECTED) {
                                $documents  =   \App\Scubaya\model\MerchantDocumentsMapper::where('merchant_detail_id', $m->id)->first();

                                $passport   =   json_decode($documents->passport_or_id);
                                if($passport->status == 'rejected' && $passport->show_in_merchant == 1) {
                                    $rejectedDocuments[$sno]['passport']    =   $passport->rejection_reason;
                                }

                                $legalDoc   =   json_decode($documents->company_legal_doc);
                                if($legalDoc->status == 'rejected' && $legalDoc->show_in_merchant == 1) {
                                    $rejectedDocuments[$sno]['legal_doc']   =   $legalDoc->rejection_reason;
                                }

                                $bankDetail =   json_decode($documents->company_bank_details);
                                if($bankDetail->status == 'rejected' && $bankDetail->show_in_merchant == 1) {
                                    $rejectedDocuments[$sno]['bank_detail']   =   $bankDetail->rejection_reason;
                                }
                            //}
                        @endphp
                        <td>
                            <span class="{{@$labelStatus[$m->status]}} status">{{ ucwords(str_replace('_', ' ', $m->status)) }}</span>
                        </td>
                        @if($m->status == MERCHANT_STATUS_PENDING || $m->status == MERCHANT_STATUS_IN_PROCESS)
                            <td>
                                <div class="inline-flex">
                                    <button type="button" class="button-blue btn btn-primary edit-button" data-toggle="modal" data-target="#verification-edit-form-modal-{{$m->merchant_detail_id}}" @if($m->status == MERCHANT_STATUS_IN_PROCESS) disabled @endif>
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::settings::delete_account_details', [Auth::id(), $m->merchant_detail_id]) }}">
                                        {{ csrf_field() }}
                                        <button type="button" class="btn btn-danger delete-main-request" id="delete-main-request" @if($m->status == MERCHANT_STATUS_IN_PROCESS) disabled @endif>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                    @php $sno++; @endphp
                @endforeach
                </tbody>
            @else
                <tr>
                    <th class="text-center"> No Request Found.</th>
                </tr>
            @endif
        </table>

        @if(count($rejectedDocuments))
            @foreach($rejectedDocuments as $key => $value)
            <div class="doc-rejection-reason-section alert alert-danger margin-top-40" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p>Your <strong>@if(count($value) > 1) documents @else document @endif  for request {{ $key }}, @if(count($value) > 1) are @else is @endif rejected</strong> due to the following reason :</p>
                <ul>
                    @if(array_key_exists('passport', $value))
                        <li><strong>Passport:</strong> {{ $value['passport'] }}</li>
                    @endif

                    @if(array_key_exists('legal_doc', $value))
                        <li><strong>Legal Documents:</strong> {{ $value['legal_doc'] }}</li>
                    @endif

                    @if(array_key_exists('bank_detail', $value))
                        <li><strong>Bank Details:</strong> {{ $value['bank_detail'] }}</li>
                    @endif
                </ul>
            </div>
            @endforeach
        @endif
    </div>
    <!-- / box-body -->
</div>

@if($status == MERCHANT_STATUS_IN_PROCESS || $status == MERCHANT_STATUS_APPROVED)
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#new_request').prop('disabled', true);
        });
    </script>
@endif

<!-- / new request model -->
@include('merchant.settings.account_verification.main_account.new_request', ['merchantDetails' => $merchantDetails, 'status' => $status])

<!-- / edit request model -->
@if(!empty($merchantDetails))
    @foreach($merchantDetails as $m)
        @if($m->status == MERCHANT_STATUS_PENDING)
            @include('merchant.settings.account_verification.main_account.edit_request')
        @endif
    @endforeach
@endif

<script type="text/javascript">
    jQuery('.delete-main-request').click(function (e) {
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