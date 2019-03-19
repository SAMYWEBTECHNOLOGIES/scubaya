@extends('merchant.layouts.app')
@section('title', 'Website Verification')
@section('content')
    @include('merchant.layouts.mainheader')
    <section id="create_boat_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Website Verification</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($errors->any())
                <div class="row margin-top-10">
                    <div class="col-md-4 col-md-offset-4 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form role="form" enctype="multipart/form-data" method="post" action="{{ route('scubaya::merchant::dive_center::create_boat', [Auth::id()]) }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="website_type" class="control-label" data-toggle="tooltip" title="Website Type">Website Type*</label>
                                <select name="website_type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    <option value="shop">Shop</option>
                                    <option value="dive_center">Dive Center</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="liveboard">Liveboard</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="control-label" data-toggle="tooltip" title="First Name">First Name*</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="Enter Your First Name">
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="control-label" data-toggle="tooltip" title="Last Name">Last Name*</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Enter Your Last Name">
                            </div>

                            <div class="form-group">
                                <label for="phone_no" class="control-label" data-toggle="tooltip" title="Phone Number">Phone*</label>
                                <input type="text" name="phone_no" class="form-control" value="{{ old('phone_no') }}" placeholder="Enter Your Phone No">
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label" data-toggle="tooltip" title="Email">Email*</label>
                                <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter Your email">
                            </div>

                            <div class="form-group">
                                <label for="address" class="control-label" data-toggle="tooltip" title="Address">Address*</label>
                                <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="street" class="control-label" data-toggle="tooltip" title="Street">Street*</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}" placeholder="Enter Your Street">
                            </div>

                            <div class="form-group">
                                <label for="house_number" class="control-label" data-toggle="tooltip" title="House Number">House Number*</label>
                                <input type="text" name="house_number" class="form-control" value="{{ old('house_number') }}" placeholder="Enter Your House Number">
                            </div>

                            <div class="form-group">
                                <label for="house_number_extension" class="control-label" data-toggle="tooltip" title="House Number Extension">House Number Extension*</label>
                                <input type="text" name="house_number_extension" class="form-control" value="{{ old('house_number_extension') }}" placeholder="Enter Your House Number Extension">
                            </div>

                            <div class="form-group">
                                <label for="city" class="control-label" data-toggle="tooltip" title="City">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="Enter Your City">
                            </div>

                            <div class="form-group">
                                <label for="state" class="control-label" data-toggle="tooltip" title="State">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}" placeholder="Enter Your State">
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="control-label" data-toggle="tooltip" title="Postal Code">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" placeholder="Enter Your Postal Code">
                            </div>

                            <div class="form-group">
                                <label for="country" class="control-label" data-toggle="tooltip" title="Country">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country') }}" placeholder="Enter Your Country">
                            </div>
                        </div>

                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="company_name" class="control-label" data-toggle="tooltip" title="Company Name">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="Enter Your Company Name">
                            </div>

                            <div class="form-group">
                                <label for="legal_id_number" class="control-label" data-toggle="tooltip" title="Company Name">Legal Id Number</label>
                                <input type="text" name="legal_id_number" class="form-control" value="{{ old('legal_id_number') }}" placeholder="Enter Your Legal ID Number">
                            </div>

                            <div class="form-group">
                                <label for="vat_number" class="control-label" data-toggle="tooltip" title="Company Name">VAT Number</label>
                                <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number') }}" placeholder="Enter Your VAT Number">
                            </div>

                            <h4>Upload Documents</h4>
                            <div class="form-group">
                                <label for="passport">Copy ID / Passport</label>
                                <input type="file" class="form-control" placeholder="Enter city"  name="passport" required>
                            </div>

                            <div class="form-group">
                                <label for="company_legal_doc">Company Legal Proof</label>
                                <input type="file" class="form-control" name="company_legal_doc" required>
                            </div>

                            <div class="form-group">
                                <label for="company_bank_details">Company Bank Details / Proof</label>
                                <input type="file" class="form-control" name="company_bank_details" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::dive_center::boats', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
            </form>
        </div>
    </section>
@endsection