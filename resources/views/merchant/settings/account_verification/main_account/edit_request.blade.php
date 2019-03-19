@if ($errors->updateRequestErrors->any())
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var id = '{{@$m->merchant_detail_id}}';
            $('#verification-edit-form-modal-'+id).modal({show: true});
        });
    </script>
@endif

<div class="modal fade bs-example-modal-lg" tabindex="-1" id="verification-edit-form-modal-{{@$m->merchant_detail_id}}" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="model-header">
                <h3 class="text-center blue">Please complete your sign up.</h3>
            </div>
            @if ($errors->updateRequestErrors->any())
                <div class="row margin-top-10">
                    <div class="col-md-8 col-md-offset-2 alert alert-danger">
                        <ul>
                            @foreach ($errors->updateRequestErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="modal-body">
                <!-- The form is placed inside the body of modal -->
                <form id="verification_form" enctype="multipart/form-data" name="verification_form" method="post" action="{{ route('scubaya::merchant::settings::update_account_details', [Auth::id(), @$m->merchant_detail_id]) }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_type">Company Type</label>
                                <input type="text" class="form-control" placeholder="Enter company type"  name="company_type" @if(@$m->company_type) value="{{ $m->company_type }}" @endif required>
                            </div>

                            <div class="form-group">
                                <label for="company_id">Company Id</label>
                                <input type="text" class="form-control" placeholder="Enter your company id" name="company_id" @if(@$m->company_id) value="{{ $m->company_id }}" @endif required>
                            </div>

                            <div class="form-group">
                                <label for="representative_full_name">Full Name Of Representative</label>
                                <input type="text" class="form-control" placeholder="Enter full name of your representative"  required name="representative_full_name" @if(@$m->full_name) value="{{ $m->full_name }}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="merchant_dob">Date Of Birth</label>
                                <input type="text" class="form-control datepicker" placeholder="Select date of birth"  required name="merchant_dob" @if(@$m->dob) value="{{ $m->dob }}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="street">Street</label>
                                <input type="text" class="form-control" placeholder="Enter street"  name="street" required @if(@$m->address) value="{{ $m->address }}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="merchant_postal_code">Postal Code</label>
                                <input type="text" class="form-control" placeholder="Enter postal code"  required name="merchant_postal_code" @if(@$m->postal_code) value="{{ $m->postal_code }}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="merchant_city">City</label>
                                <input type="text" class="form-control" placeholder="Enter city"  required name="merchant_city" @if(@$m->city) value="{{ $m->city }}" @endif>
                            </div>
                        </div>

                        @php
                            $Passport   =   json_decode($m->passport_or_id);
                            $LegalDoc   =   json_decode($m->company_legal_doc);
                            $BankDetail =   json_decode($m->company_bank_details);

                            $mUID       =   \App\Scubaya\model\User::where('id', $m->merchant_primary_id)->value('UID');
                        @endphp

                        <div class="col-md-6">
                            <h4>Upload legal documents</h4>
                            <div class="form-group">
                                <label for="passport">Copy ID / Passport</label>
                                <input type="file" class="form-control" placeholder="Enter city" name="passport" @if(! isset($Passport)) required @endif>
                                @if(isset($Passport))
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/merchant/'.$mUID.'-req'.$m->merchant_detail_id.'/'.$Passport->passport) }}">{{ $Passport->passport }}</a>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="company_legal_doc">Company Legal Proof</label>
                                <input type="file" class="form-control" name="company_legal_doc" @if(! isset($LegalDoc)) required @endif>
                                @if(isset($LegalDoc))
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/merchant/'.$mUID.'-req'.$m->merchant_detail_id.'/'.$LegalDoc->legal_doc) }}">{{ $LegalDoc->legal_doc }}</a>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="company_bank_details">Company Bank Details / Proof</label>
                                <input type="file" class="form-control" name="company_bank_details" @if(! isset($BankDetail)) required @endif>
                                @if(isset($BankDetail))
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/merchant/'.$mUID.'-req'.$m->merchant_detail_id.'/'.$BankDetail->bank_detail) }}">{{ $BankDetail->bank_detail}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="verification_submit">Update</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

