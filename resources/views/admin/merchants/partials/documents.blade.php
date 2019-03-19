<div style="background: rgb(51, 122, 183);">
    <header class="smaller">
        <div class="white">
            <h3>
                Documents
            </h3>
        </div>
    </header>
</div>
<div class="container screen-fit">
    @if(Session::has('success'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif

    @php
        $status         =   '';
        $passport       =   '';
        $legalDoc       =   '';
        $bankDetails    =   '';

        $labelStatus        =   [
            MERCHANT_STATUS_PENDING     =>  'label label-warning',
            MERCHANT_STATUS_IN_PROCESS  =>  'label label-info',
            MERCHANT_STATUS_APPROVED    =>  'label label-success',
            MERCHANT_STATUS_REJECTED    =>  'label label-danger'
        ];

        $accountType    =   [
            SHOP            => 'Shop',
            DIVE_CENTER     => 'Dive Center',
            LIVEBOARD       => 'Liveboard',
            HOTEL           => 'Hotel'
        ];

        $mimes  =   [
            'jpg'   =>  'JPG',
            'png'   =>  'PNG',
            'jpeg'  =>  'JPEG',
            'pdf'   =>  'PDF'
        ];
    @endphp
    <section>
        <div class="nav-tabs-custom" id="tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#main_account" data-toggle="tab" aria-expanded="true">Main Account</a></li>
                {{--<li><a href="#sub_account" data-toggle="tab" aria-expanded="true">Sub Account</a></li>--}}
            </ul>

            <div class="tab-content">
                <div class="tab-pane active margin-bottom-10 padding-20" id="main_account">
                    <!-- / box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            @if(($mainAccountDocuments))
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Passport</th>
                                    <th>Company legal Document</th>
                                    <th>Company Bank Details</th>
                                    {{--<th>status</th>
                                    <th></th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                {{--@foreach($mainAccountDocuments as $m)--}}
                                    @php
                                        $merchantId =   \App\Scubaya\model\User::where('id', $mainAccountDocuments['merchant_primary_id'])->value('UID');

                                        $Passport   =   json_decode($mainAccountDocuments['passport']);
                                        $LegalDoc   =   json_decode($mainAccountDocuments['company_legal_doc']);
                                        $BankDetail =   json_decode($mainAccountDocuments['company_bank_details']);

                                        $passportMimeType   =   explode('.', $Passport->passport);
                                        $legalDocMimeType   =   explode('.', $LegalDoc->legal_doc);
                                        $bankDetailMimeType =   explode('.', $BankDetail->bank_detail);
                                    @endphp
                                    <tr>
                                        <td>{{ $merchantId }}</td>
                                        <td>
                                            <a data-toggle="modal" data-target="#passportModal">{{ $Passport->passport }}</a>
                                            |
                                            <span class="margin-top-10 status {{ $labelStatus[$Passport->status] }}">{{ ucwords($Passport->status) }}</span>
                                        </td>
                                        <td>
                                            <a data-toggle="modal" data-target="#legalDocModal">{{ $LegalDoc->legal_doc }}</a>
                                            |
                                            <span class="margin-top-10 status {{ $labelStatus[$LegalDoc->status] }}">{{ ucwords($LegalDoc->status) }}</span>
                                        </td>
                                        <td>
                                            <a data-toggle="modal" data-target="#bankDetailModal">{{ $BankDetail->bank_detail }}</a>
                                            |
                                            <span class="margin-top-10 status {{ $labelStatus[$BankDetail->status] }}">{{ ucwords($BankDetail->status) }}</span>
                                        </td>
                                    </tr>

                                    <!-- passport modal -->
                                    <div class="modal fade" id="passportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Passport Verification</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @if($passportMimeType[1] == 'pdf')
                                                        @php $passport  =  asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$Passport->passport);  @endphp
                                                        <div id="passportDialog" style="height: 300px;border: 1px solid #d0d0d0;"></div>
                                                    @else
                                                        <img src="{{ asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$Passport->passport) }}" height="300" alt="{{ $Passport->passport }}">
                                                    @endif

                                                    <form name="passport_verification" method="post" action="{{ route('scubaya::admin::merchants::update_main_account_status', [$mainAccountDocuments->id]) }}">
                                                        {{ csrf_field() }}
                                                        <div class="row margin-top-30">
                                                            <label class="control-label col-md-3">Status</label>
                                                            <div class="col-md-8">
                                                                <select name="passport[status]" class="form-control" id="passport_status">
                                                                    <option value="pending" @if($Passport->status == 'pending') selected @endif>Pending</option>
                                                                    <option value="approved" @if($Passport->status == 'approved') selected @endif>Approved</option>
                                                                    <option value="rejected" @if($Passport->status == 'rejected') selected @endif>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10 @if($Passport->status != 'rejected') hidden @endif">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Why its rejected?</label>
                                                                <div class="col-md-8">
                                                                    <textarea name="passport[rejection_reason]" class="form-control">{{ $Passport->rejection_reason }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Show in merchant</label>
                                                                <div class="col-md-8">
                                                                    <input type="checkbox" id="show-passport" name="passport[show_passport]" @if($Passport->show_in_merchant == 1) checked @endif value="1">
                                                                    <input type="hidden" id="show-passport-hidden" name="passport[show_passport]" value="0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- company legal documents modal -->
                                    <div class="modal fade" id="legalDocModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Legal Documents Verification</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @if($legalDocMimeType[1] == 'pdf')
                                                        @php $legalDoc  =  asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$LegalDoc->legal_doc);  @endphp
                                                        <div id="legalDocDialog" style="height: 300px;"> </div>
                                                    @else
                                                        <img src="{{ asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$LegalDoc->legal_doc) }}" height="300" alt="{{ $LegalDoc->legal_doc }}">
                                                    @endif

                                                    <form name="legal_doc_verification" method="post" action="{{ route('scubaya::admin::merchants::update_main_account_status', [$mainAccountDocuments->id]) }}">
                                                        {{ csrf_field() }}
                                                        <div class="row margin-top-30">
                                                            <label class="control-label col-md-3">Status</label>
                                                            <div class="col-md-8">
                                                                <select name="legalDoc[status]" class="form-control" id="legal_doc_status">
                                                                    <option value="pending" @if($LegalDoc->status == 'pending') selected @endif>Pending</option>
                                                                    <option value="approved" @if($LegalDoc->status == 'approved') selected @endif>Approved</option>
                                                                    <option value="rejected" @if($LegalDoc->status == 'rejected') selected @endif>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10 @if($LegalDoc->status != 'rejected') hidden @endif">
                                                            <label class="control-label col-md-3">Why its rejected?</label>
                                                            <div class="col-md-8">
                                                                <textarea name="legalDoc[rejection_reason]" class="form-control">{{ $LegalDoc->rejection_reason }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <label class="control-label col-md-3">Show in merchant</label>
                                                            <div class="col-md-8">
                                                                <input type="checkbox" id="show-legal-doc" name="legalDoc[show_legal_doc]" @if($LegalDoc->show_in_merchant == 1) checked @endif value="1">
                                                                <input type="hidden" id="show-legal-doc-hidden"  name="legalDoc[show_legal_doc]" value="0">
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- company bank details modal -->
                                    <div class="modal fade" id="bankDetailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Bank Details Verification</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @if($bankDetailMimeType[1] == 'pdf')
                                                        @php $bankDetails  =  asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$BankDetail->bank_detail);  @endphp
                                                        <div id="bankDetailDialog" style="height: 300px;"></div>
                                                    @else
                                                        <img src="{{ asset('assets/images/scubaya/merchant/'.$merchantId.'-req'.$mainAccountDocuments->merchant_detail_id.'/'.$BankDetail->bank_detail) }}" height="300" alt="{{ $BankDetail->bank_detail }}">
                                                    @endif

                                                    <form name="bank_detail_verification" method="post" action="{{ route('scubaya::admin::merchants::update_main_account_status', [$mainAccountDocuments->id]) }}">
                                                        {{ csrf_field() }}
                                                        <div class="row margin-top-30">
                                                            <label class="control-label col-md-3">Status</label>
                                                            <div class="col-md-8">
                                                                <select name="bankDetail[status]" class="form-control" id="bank_detail_status">
                                                                    <option value="pending" @if($BankDetail->status == 'pending')  selected @endif>Pending</option>
                                                                    <option value="approved" @if($BankDetail->status == 'approved')  selected @endif>Approved</option>
                                                                    <option value="rejected" @if($BankDetail->status == 'rejected')  selected @endif>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10 @if(empty($BankDetail) || $BankDetail->status != 'rejected') hidden @endif">
                                                            <label class="control-label col-md-3">Why its rejected?</label>
                                                            <div class="col-md-8">
                                                                <textarea name="bankDetail[rejection_reason]" class="form-control">{{ $BankDetail->rejection_reason }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <label class="control-label col-md-3">Show in merchant</label>
                                                            <div class="col-md-8">
                                                                <input type="checkbox" id="show-bank-detail" name="bankDetail[show_bank_detail]" @if($BankDetail->show_in_merchant == 1) checked @endif value="1">
                                                                <input type="hidden" id="show-bank-detail-hidden" name="bankDetail[show_bank_detail]" value="0">
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-10">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{--@endforeach--}}
                                </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No Request Found.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <!-- / box-body -->
            </div>
            {{--<div class="tab-pane margin-bottom-10 padding-20" id="sub_account">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        @if(count($accountDetails))
                            <thead>
                                <tr>
                                    <th>Website Type</th>
                                    <th>Website Name</th>
                                    <th>Passport</th>
                                    <th>Legal Documents</th>
                                    <th>Bank Details</th>
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
                                        <td><a download="{{$detail->passport}}"  href="data:@if(explode('.',$detail->passport)[1] != 'pdf') {{'image'}}@else{{'file'}}@endif/{{$mimes[explode('.',$detail->passport)[1]]}};base64,{{base64_encode(file_get_contents(asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ','',$accountType[$detail->website_type])).'-'.$detail->id.'/'.$detail->id.'-'.$detail->passport))) }}"> {{ $detail->passport }} </a></td>
                                        <td><a download="{{$detail->legal_doc}}"     href="data:@if(explode('.',$detail->legal_doc)[1] != 'pdf'){{'image'}}@else{{'file'}}@endif/{{$mimes[explode('.',$detail->legal_doc)[1]]}};base64,{{base64_encode(file_get_contents(asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ','',$accountType[$detail->website_type])).'-'.$detail->id.'/'.$detail->id.'-'.$detail->legal_doc))) }}">{{ $detail->legal_doc }}</a></td>
                                        <td><a download="{{$detail->bank_details}}" href="data:@if(explode('.',$detail->bank_details)[1] != 'pdf'){{'image'}}@else{{'file'}}@endif/{{$mimes[explode('.',$detail->bank_details)[1]]}};base64,{{base64_encode(file_get_contents(asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ','',$accountType[$detail->website_type])).'-'.$detail->id.'/'.$detail->id.'-'.$detail->bank_details))) }}">{{ $detail->bank_details }}</a></td>
                                        <td>
                                            <span class="{{$labelStatus[$detail->status]}} status">{{ ucwords(str_replace('_', ' ', $detail->status)) }}</span>
                                        </td>
                                        <td>
                                            @if($detail->status != 'rejected')
                                                <select class="change_status_website form-control" data-id="{{$detail->document_id}}">
                                                    <option value="{{MERCHANT_STATUS_PENDING}}" @if($detail->status ==MERCHANT_STATUS_PENDING )selected @endif>{{ucfirst(MERCHANT_STATUS_PENDING )  }}</option>
                                                    <option value="{{MERCHANT_STATUS_APPROVED }}" @if($detail->status ==MERCHANT_STATUS_APPROVED )selected @endif>{{ucfirst(MERCHANT_STATUS_APPROVED ) }}</option>
                                                    <option value="{{MERCHANT_STATUS_REJECTED}}" @if($detail->status ==MERCHANT_STATUS_REJECTED )selected @endif>{{ucfirst(MERCHANT_STATUS_REJECTED)}}  </option>
                                                    <option value="{{MERCHANT_STATUS_IN_PROCESS}}" @if($detail->status ==MERCHANT_STATUS_IN_PROCESS )selected @endif>{{ucfirst(MERCHANT_STATUS_IN_PROCESS)}}</option>
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
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
            </div>--}}
            </div>
        </div>
    </section>
</div>

<script src="{{ asset('plugins/pdfObject/pdfobject.js') }}" ></script>
<script type="text/javascript">
    var passport        =   '{{ $passport }}' ;
    var legalDoc        =   '{{ $legalDoc }}' ;
    var bankDetail      =   '{{ $bankDetails }}' ;

    var labelStatus     =    {
        '{{MERCHANT_STATUS_PENDING}}'   : 'label label-warning',
        '{{MERCHANT_STATUS_IN_PROCESS}}': 'label label-info',
        '{{MERCHANT_STATUS_APPROVED}}'  : 'label label-success',
        '{{MERCHANT_STATUS_REJECTED}}'  : 'label label-danger'
    };

    jQuery(document).ready(function (scubaya) {

        if(passport) {
            PDFObject.embed(passport, $("#passportDialog"));
        }

        if(legalDoc) {
            PDFObject.embed(legalDoc, $("#legalDocDialog"));
        }

        if(bankDetail) {
            PDFObject.embed(bankDetail, $("#bankDetailDialog"));
        }

        scubaya('#show-passport').click(function () {
            if(scubaya('#show-passport').is(":checked")) {
                scubaya('#show-passport-hidden').attr('disabled', 'disabled');
            } else {
                scubaya('#show-passport-hidden').removeAttr('disabled', 'disabled');
            }
        });

        scubaya('#show-legal-doc').click(function () {
            if(scubaya('#show-legal-doc').is(":checked")) {
                scubaya('#show-legal-doc-hidden').attr('disabled', 'disabled');
            } else {
                scubaya('#show-legal-doc-hidden').removeAttr('disabled', 'disabled');
            }
        });

        scubaya('#show-bank-detail').click(function () {
            if(scubaya('#show-bank-detail').is(":checked")) {
                scubaya('#show-bank-detail-hidden').attr('disabled', 'disabled');
            } else {
                scubaya('#show-bank-detail-hidden').removeAttr('disabled', 'disabled');
            }
        });

        scubaya('.change_status_website').change(function (e) {
            let value           =   scubaya(this).val();
            let document_id     =   scubaya(this).data('id');
            let token           =   "{{ csrf_token() }}";
            let url             =   "{{route('scubaya::admin::merchants::website_account_status')}}";

            if(value == 'rejected') {
                $.post( url, {value:value,isActive:0,document_id:document_id,_token:token }, function( status )
                {
                    if(status){
                        scubaya(e.currentTarget).parent().prev().find('span').html(value.charAt(0).toUpperCase()+ value.slice(1)).removeClass().addClass(labelStatus[value]);
                        scubaya(e.currentTarget).parent().find('select').remove();
                    }
                });
            } else {
                $.post( url,{value:value,isActive:1,document_id:document_id,_token:token }, function( status )
                {
                    if(status){
                        scubaya(e.currentTarget).parent().prev().find('span').html(value.charAt(0).toUpperCase()+ value.slice(1)).removeClass().addClass(labelStatus[value]);
                    }
                });
            }
        });

        scubaya('#passport_status').change(function () {
            if(scubaya(this).val() == 'rejected') {
                //scubaya('#passport_status').parent().parent().next().removeClass('hidden');
                scubaya('#passport_status').parent().parent().next().removeClass('hidden');
            } else {
                scubaya('#passport_status').parent().parent().next().addClass('hidden');
            }
        });

        scubaya('#legal_doc_status').change(function () {
            if(scubaya(this).val() == 'rejected') {
                scubaya('#legal_doc_status').parent().parent().next().removeClass('hidden');
            } else {
                scubaya('#legal_doc_status').parent().parent().next().addClass('hidden');
            }
        });

        scubaya('#bank_detail_status').change(function () {
            if(scubaya(this).val() == 'rejected') {
                scubaya('#bank_detail_status').parent().parent().next().removeClass('hidden');
            } else {
                scubaya('#bank_detail_status').parent().parent().next().addClass('hidden');
            }
        });
    });
</script>
