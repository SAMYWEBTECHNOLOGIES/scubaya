@extends('merchant.layouts.app')
@section('title','Add Tax Rate')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Settings</a></li>
    <li class="active"><span>Add Tax Rate</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    <section id="create-tax-rate-section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Tax Rate</h3>
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

            <form  method="post" action="{{route('scubaya::merchant::settings::create_tax_rate',[Auth::id()])}}">
                <div class="box-body">
                    <div class="row">
                         <div class="col-md-6 col-md-offset-3">
                             <input type="hidden" name="_token" value="{{csrf_token()}}">
                             <div class="form-group">
                                 <label>Title</label>
                                 <input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title')}}" autofocus >
                             </div>

                             <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>Country</label><br>
                                         <input type="text" class="form-control"  id="country" name="country" >
                                         <input type="hidden" class="form-control" id="country_code" placeholder="Enter Country" name="country_code" value="">
                                     </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>State</label>
                                         <input type="text" class="form-control" placeholder="State" name="state" value="{{old('state')}}">
                                     </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label>City</label>
                                         <input type="text" class="form-control" placeholder="City" name="city" value="{{old('city')}}">
                                     </div>
                                 </div>
                             </div>

                             <div class="row">
                                 <div class="col-md-6">
                                     <div class="form-group">
                                         <label>Region</label>
                                         <input type="text" class="form-control" placeholder="Region" name="region" value="{{old('region')}}">
                                     </div>
                                 </div>

                                 <div class="col-md-6">
                                     <div class="form-group">
                                         <label>Zipcode</label>
                                         <input type="text" class="form-control" placeholder="Zipcode" name="zipcode" value="{{old('zipcode')}}">
                                     </div>
                                 </div>
                             </div>

                             <div class="form-group">
                                 <label>Rate</label>
                                 <div class="input-group">
                                     <input type="text" class="form-control" placeholder="Rate" id="rate" name="rate" >
                                     <span class="input-group-addon">%</span>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::settings::tax_rates', [Auth::id()]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Save</button>
                </div>
            </form>
        </div>
    </section>

    <link rel="stylesheet" href="{{asset('assets/country-selector/build/css/countrySelect.css')}}">
    <script src="{{asset('assets/country-selector/build/js/countrySelect.min.js')}}"></script>
    <script type="text/javascript">
        jQuery("#country").countrySelect();

        $('#country').change(function() {
            jQuery('#country_code').val(jQuery('.country.highlight.active').data('country-code'));
        });
    </script>
@endsection
