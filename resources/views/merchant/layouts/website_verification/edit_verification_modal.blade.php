@if ($errors->verificationError->any() && session()->get('errorInModalId') == $website->id)
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var id = '{{@$website->id}}';
            $('#sub-account-verification-edit-form-modal-'+id).modal({show: true});
        });
    </script>
@endif

<form method="post"  action="{{ route(@$route, [Auth::id(), @$website->id, @$website->website_id]) }}" enctype="multipart/form-data">
    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="sub-account-verification-edit-form-modal-{{@$website->id}}" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h4 class="text-center blue">Complete following details to get verified.</h4>
                </div>
                @if ($errors->verificationError->any() && session()->get('errorInModalId') == @$website->id)
                    <div class="row margin-top-10">
                        <div class="col-md-8 col-md-offset-2 alert alert-danger">
                            <ul>
                                @foreach ($errors->verificationError->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="modal-body">
                    <!-- The form is placed inside the body of modal -->
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="first_name" class="control-label" data-toggle="tooltip" title="First Name">First Name*</label>
                                <input type="text" name="verification[first_name]" class="form-control" value="{{ @$website->first_name }}" placeholder="Enter Your First Name">
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="control-label" data-toggle="tooltip" title="Last Name">Last Name*</label>
                                <input type="text" name="verification[last_name]" class="form-control" value="{{ @$website->last_name }}" placeholder="Enter Your Last Name">
                            </div>

                            <div class="form-group">
                                <label for="phone_no" class="control-label" data-toggle="tooltip" title="Phone Number">Phone</label>
                                <input type="text" name="verification[phone_no]" class="form-control" value="{{ @$website->phone_no }}" placeholder="Enter Your Phone No">
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label" data-toggle="tooltip" title="Email">Email*</label>
                                <input type="text" name="verification[email]" class="form-control" value="{{ @$website->email }}" placeholder="Enter Your email">
                                <input type="hidden" name="verification[old_email]" value="{{ @$website->email }}">
                            </div>

                            <div class="form-group">
                                <label for="address" class="control-label" data-toggle="tooltip" title="Address">Address</label>
                                <input type="text" name="verification[address]" class="form-control" value="{{ @$website->address }}" placeholder="Enter Your Address">
                            </div>

                            <div class="form-group">
                                <label for="street" class="control-label" data-toggle="tooltip" title="Street">Street</label>
                                <input type="text" name="verification[street]" class="form-control" value="{{ @$website->street }}" placeholder="Enter Your Street">
                            </div>

                            <div class="form-group">
                                <label for="house_number" class="control-label" data-toggle="tooltip" title="House Number">House Number</label>
                                <input type="text" name="verification[house_number]" class="form-control" value="{{ @$website->house_no }}" placeholder="Enter Your House Number">
                            </div>

                            <div class="form-group">
                                <label for="house_number_extension" class="control-label" data-toggle="tooltip" title="House Number Extension">House Number Extension</label>
                                <input type="text" name="verification[house_number_extension]" class="form-control" value="{{ @$website->house_no_extension }}" placeholder="Enter Your House Number Extension">
                            </div>

                            <div class="form-group">
                                <label for="city" class="control-label" data-toggle="tooltip" title="City">City</label>
                                <input type="text" name="verification[city]" class="form-control" value="{{ @$website->city }}" placeholder="Enter Your City">
                            </div>

                            <div class="form-group">
                                <label for="state" class="control-label" data-toggle="tooltip" title="State">State</label>
                                <input type="text" name="verification[state]" class="form-control" value="{{ @$website->state }}" placeholder="Enter Your State">
                            </div>

                            <div class="form-group">
                                <label for="country" class="control-label" data-toggle="tooltip" title="Country">Country</label>
                                <input type="text" name="verification[country]" class="form-control" value="{{ @$website->country }}" placeholder="Enter Your Country">
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="control-label" data-toggle="tooltip" title="Postal Code">Postal Code</label>
                                <input type="text" name="verification[postal_code]" class="form-control" value="{{ @$website->zip_code }}" placeholder="Enter Your Postal Code">
                            </div>
                        </div>

                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="company_name" class="control-label" data-toggle="tooltip" title="Company Name">Company Name</label>
                                <input type="text" name="verification[company_name]" class="form-control" value="{{ @$website->company_name }}" placeholder="Enter Your Company Name">
                            </div>

                            <div class="form-group">
                                <label for="legal_id_number" class="control-label" data-toggle="tooltip" title="Company Name">Legal Id Number</label>
                                <input type="text" name="verification[legal_id_number]" class="form-control" value="{{ @$website->legal_id_no }}" placeholder="Enter Your Legal ID Number">
                            </div>

                            <div class="form-group">
                                <label for="vat_number" class="control-label" data-toggle="tooltip" title="Company Name">VAT Number</label>
                                <input type="text" name="verification[vat_number]" class="form-control" value="{{ @$website->vat_no }}" placeholder="Enter Your VAT Number">
                            </div>

                            <h4>Upload Documents</h4>
                            <div class="form-group">
                                <label for="passport">Copy ID / Passport</label>
                                <input type="file" class="form-control" placeholder="Enter city"  name="verification[passport]" >
                                @if(@$website->passport_or_id)
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ', '', $websiteType)).'-'.$website->id.'/'.$website->id.'-'.$website->passport_or_id) }}">{{ $website->passport_or_id }}</a>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="company_legal_doc">Company Legal Proof</label>
                                <input type="file" class="form-control" name="verification[company_legal_doc]" >
                                @if(@$website->legal_doc)
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ', '', $websiteType)).'-'.$website->id.'/'.$website->id.'-'.$website->legal_doc) }}">{{ $website->legal_doc }}</a>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="company_bank_details">Company Bank Details / Proof</label>
                                <input type="file" class="form-control" name="verification[company_bank_details]">
                                @if(@$website->bank_details)
                                    <a target="_blank" href="{{ asset('assets/images/scubaya/website/documents/'.lcfirst(str_replace(' ', '', $websiteType)).'-'.$website->id.'/'.$website->id.'-'.$website->bank_details) }}">{{ $website->bank_details }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ route('scubaya::merchant::settings::account_verification', [Auth::id()]) }}"><button type="button" class="btn btn-default" id="skip">Cancel</button></a>
                        <button type="submit" class="btn btn-info pull-right">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>