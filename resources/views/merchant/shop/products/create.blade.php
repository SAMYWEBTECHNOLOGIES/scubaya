@extends('merchant.layouts.app')
@section('title', 'New Product')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::products',[Auth::id(),$shopId])}}">Products</a></li>
    <li class="active"><span>Add Product</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')

    <section id="create_rental_product_section" class="padding-20">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">New Product</h3>
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

            <form role="form" method="post" enctype="multipart/form-data" action="{{ route('scubaya::merchant::shop::create_product', [Auth::id(), $shopId]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="product_title" class="control-label">Title</label>
                                <input type="text" name="product_title" class="form-control" placeholder="Enter Title" value="{{old('product_title')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_sku" class="control-label">SKU</label>
                                <input type="text" name="product_sku" class="form-control" placeholder="Enter SKU" value="{{old('product_sku')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_weight" class="control-label">Weight(KG)</label>
                                <input type="text" name="product_weight" class="form-control" placeholder="Enter Weight" value="{{old('product_weight')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_status" class="control-label">Status</label>
                                <select name="product_status" class="form-control">
                                     <option value="" disabled selected>-- Select Status --</option>
                                     <option value='1' @if(old('product_status') === '1') selected @endif>Enabled</option>
                                     <option value='0' @if(old('product_status') === '0') selected @endif>Disabled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_tax" class="control-label">Tax Class</label>
                                <select name="product_tax" class="form-control">
                                    <option value="" disabled selected>-- Select Tax Class --</option>
                                    @if($taxClass && count($taxClass))
                                        @foreach($taxClass as $class)
                                            <option value="{{$class['rate']}}" @if(old('product_tax') == ($class['rate'])) selected @endif>{{$class['rate'].'%'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{--<div class="form-group">
                                <label for="product_visibility" class="control-label">Visibility</label>
                                <select name="product_visibility" class="form-control">
                                    <option value="">-- Select Visibility --</option>
                                    @if(count($shops) > 0)
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->website_id }}" @if($shop->website_id == old('product_visibility')) selected @endif>{{ ucwords($shop->name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>--}}

                            <div class="form-group">
                                <label for="product_manufacturer" class="control-label">Manufacturer</label>
                                <input type="text" name="product_manufacturer" class="form-control" placeholder="Enter Manufacturer" value="{{old('product_manufacturer')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_color" class="control-label">Color</label>
                                <div class="input-group colorpicker-component colorpicker-element" id="color-selector">
                                    <input type="text" name="product_color" class="form-control"  value="@if(old('product_color')){{old('product_color')}} @else {{ '#000000' }} @endif">
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="product_available_from" class="control-label">Available From</label>
                                <input type="text" name="product_available_from" class="form-control datepicker"  value="{{old('product_available_from')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_available_till" class="control-label">Available Till</label>
                                <input type="text" name="product_available_till" class="form-control datepicker"  value="{{old('product_available_till')}}">
                            </div>
                        </div>

                        <div class="col-md-4 col-md-offset-1">
                            <div class="form-group">
                                <label for="product_price" class="control-label">Price</label>
                                <input type="text" name="product_price" class="form-control" placeholder="Enter Price" value="{{old('product_price')}}">
                            </div>

                            <div class="form-group">
                                <label for="product_included_in_course" class="control-label">Included In Course</label></br>
                                <div class="btn-group" id="status" data-toggle="buttons">
                                    <label class="btn btn-default btn-on btn-sm @if(old('product_included_in_course') === '1') active @elseif(is_null(old('product_included_in_course'))) active @endif">
                                        <input type="radio" value="1" name="product_included_in_course" @if(old('product_included_in_course') === '1') checked @elseif(is_null(old('product_included_in_course'))) checked @endif>Yes</label>

                                    <label class="btn btn-default btn-off btn-sm @if(old('product_included_in_course') === '0') active @endif">
                                        <input type="radio" value="0" name="product_included_in_course" @if(old('product_included_in_course') === '0') checked @endif>No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="no_of_products_available" class="control-label">How Many Products Available</label>
                                <select name="no_of_products_available" class="form-control">
                                    <?php for($i = 1; $i <= config('scubaya.no_of_products_in_course'); $i++){ ?>
                                    <option value="{{ $i }}" @if($i == old('no_of_products_available')) selected @endif>{{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="control-label">Product Type</label>
                                <select name="product_type" class="form-control">
                                    <option value="{{ RENTAL_PRODUCT }}" @if(old('product_type') == RENTAL_PRODUCT) selected @endif>Rental</option>
                                    <option value="{{ SELL_PRODUCT }}" @if(old('product_type') == SELL_PRODUCT) selected @endif>Sell</option>
                                </select>
                            </div>

                            <?php
                            $parentCategories  =   \App\Scubaya\model\ProductCategories::where('merchant_key', $authId)
                                                                                        ->where('parent_id', 0)
                                                                                        ->get();
                            ?>
                            <div class="form-group">
                                <label for="product_category" class="control-label">Category</label>
                                <select name="product_category" class="form-control">
                                    @if(count($parentCategories))
                                        @foreach($parentCategories as $category)
                                            <?php $subCategories  = \App\Scubaya\model\ProductCategories::where('merchant_key', $authId)
                                                                                                        ->where('parent_id', $category->id)
                                                                                                        ->get();
                                            ?>
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @if(count($subCategories))
                                                @foreach($subCategories as $subCategory)
                                                    <option value="{{ $subCategory->id }}">{{ ' - '. $subCategory->name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sub_accounts_to_show_product" class="control-label">Select sub account to show products</label>
                                <select data-actions-box="true" data-selected-text-format="count > 2" class="form-control selectpicker show-tick" multiple name="sub_accounts[]" data-size="5">
                                    @if(count($subAccounts))
                                        @foreach($subAccounts as $website_type => $website_details)
                                            <optgroup label="{{ ucwords($website_type) }}">
                                                @foreach($website_details as $detail)
                                                    <option value="{{ $website_type.'.'.$detail->id }}">{{ ucwords($detail->name) }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_short_description" class="control-label">Short Description</label>
                                <textarea name="product_short_description" class="form-control">{{old('product_short_description')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="product_description" class="control-label">Description</label>
                                <textarea name="product_description" class="form-control">{{ old('product_description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="product_image" class="control-label"><i class="fa fa-upload" aria-hidden="true"></i> Upload Image</label>
                                <input type="file" name="product_image" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('scubaya::merchant::shop::products', [Auth::id(), $shopId]) }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
            </form>
        </div>
    </section>
    <script type="text/javascript">
        jQuery('.datepicker').datepicker({
            format: 'mm-dd-yyyy'
        });

        jQuery('#color-selector').colorpicker();
    </script>
@endsection