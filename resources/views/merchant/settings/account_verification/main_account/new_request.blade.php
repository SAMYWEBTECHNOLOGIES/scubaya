<script type="text/javascript">
    @if ($errors->saveRequestErrors->any())
        jQuery(document).ready(function($) {
            $('#verification-form-modal').modal({show: true});
        });
    @endif
</script>

<div aria-hidden="true" class="modal fade bs-example-modal-lg" tabindex="-1" id="verification-form-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    @if(count($merchantDetails) > 0 && $status == MERCHANT_STATUS_PENDING)
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h3 class="text-center blue">@lang('merchant_settings.warning')</h3>
                </div>
                <div class="modal-body">
                    <p class="text-center">@lang('merchant_settings.to_generate_new_request')</p>
                </div>
            </div>
        </div>
    @else
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="model-header">
                    <h3 class="text-center blue">@lang('merchant_settings.complete_sign_up')</h3>
                </div>
                @if ($errors->saveRequestErrors->any())
                    <div class="row margin-top-10">
                        <div class="col-md-8 col-md-offset-2 alert alert-danger">
                            <ul>
                                @foreach ($errors->saveRequestErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="modal-body">
                    <!-- The form is placed inside the body of modal -->
                    <form id="verification_form" enctype="multipart/form-data" name="verification_form" method="post" action="{{ route('scubaya::merchant::settings::save_account_details', [Auth::id()]) }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                {{--<div class="form-group">
                                    <label for="">@lang('merchant_settings.who_are_you')</label></br>
                                    <input type="checkbox"  name="merchant_category[]" value="dive_center" @if(is_array(old('merchant_category')) && in_array('dive_center', old('merchant_category'))) checked @endif> @lang('merchant_settings.dive_center')
                                    <input type="checkbox"  name="merchant_category[]" value="liveboard" @if(is_array(old('merchant_category')) && in_array('liveboard', old('merchant_category'))) checked @endif> @lang('merchant_settings.liveboard')
                                    <input type="checkbox"  name="merchant_category[]" value="accommodation" @if(is_array(old('merchant_category')) && in_array('accommodation', old('merchant_category'))) checked @endif> @lang('merchant_settings.hotel_accommodation')
                                    </br>
                                    <span id="merchant_category_error"></span>
                                </div>--}}

                                <div class="form-group">
                                    <label for="company_type">@lang('merchant_settings.company_type')</label>
                                    <input type="text" class="form-control" placeholder="Enter company type"  name="company_type" required value="{{ old('company_type') }}">
                                </div>

                                <div class="form-group">
                                    <label for="company_id">@lang('merchant_settings.company_id')</label>
                                    <input type="text" class="form-control" placeholder="Enter your company id" name="company_id" required value="{{ old('company_id') }}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_full_name">@lang('merchant_settings.full_name_representative')</label>
                                    <input type="text" class="form-control" placeholder="Enter full name of your representative"  name="representative_full_name" required value="{{ old('representative_full_name') }}">
                                </div>

                                <div class="form-group">
                                    <label for="merchant_dob">@lang('merchant_settings.dob')</label>
                                    <input type="text" readonly class="form-control datepicker" placeholder="Select date of birth"  name="merchant_dob" required value="{{ old('merchant_dob') }}">
                                </div>

                                <div class="form-group">
                                    <label for="street">@lang('merchant_settings.street')</label>
                                    <input type="text" class="form-control" placeholder="Enter street"  name="street" required value="{{ old('street') }}">
                                </div>

                                <div class="form-group">
                                    <label for="merchant_postal_code">@lang('merchant_settings.postal_code')</label>
                                    <input type="text" class="form-control" placeholder="Enter postal code"  name="merchant_postal_code" required value="{{ old('merchant_postal_code') }}">
                                </div>

                                <div class="form-group">
                                    <label for="merchant_city">@lang('merchant_settings.city')</label>
                                    <input type="text" class="form-control" placeholder="Enter city"  name="merchant_city" required value="{{ old('merchant_city') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4>@lang('merchant_settings.upload_documents')</h4>
                                <div class="form-group">
                                    <label for="passport">@lang('merchant_settings.copyid_passport')</label>
                                    <input type="file" class="form-control" placeholder="Enter city"  name="passport" required>
                                    {{--@if($merchant->passport) @endif--}}
                                </div>

                                <div class="form-group">
                                    <label for="company_legal_doc">@lang('merchant_settings.company_legal_proof')</label>
                                    <input type="file" class="form-control" name="company_legal_doc" required>
                                </div>

                                <div class="form-group">
                                    <label for="company_bank_details">@lang('merchant_settings.company_bank_details')</label>
                                    <input type="file" class="form-control" name="company_bank_details" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="verification_submit">@lang('merchant_settings.submit')</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('merchant_settings.cancel')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
