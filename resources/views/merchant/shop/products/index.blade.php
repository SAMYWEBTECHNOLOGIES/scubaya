@extends('merchant.layouts.app')
@section('title', 'Products')
@section('breadcrumb')
    <li><a href="#">Shop</a></li>
    <li><a href="{{route('scubaya::merchant::shop::shops',[Auth::id()])}}">Manage Shop</a></li>
    <li class="active"><span>Products</span></li>
@endsection

@section('content')
    @include('merchant.layouts.mainheader')
    <?php
    $productType    =   [
        RENTAL_PRODUCT  =>  'Rental',
        SELL_PRODUCT    =>  'Sell'
    ];
    ?>

    <section id="products_section" class="padding-20">
        <div>
            <a href="{{ route('scubaya::merchant::shop::create_product', [Auth::id(), $shopId]) }}">
                <button type="button" class="pull-right button-blue btn btn-primary">
                    + New
                </button>
            </a>
        </div>

        <div class="box box-primary margin-top-60">
            <div class="box-header with-border">
                <h3 class="box-title">Products</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    @if(count($products) > 0)
                        <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>SKU</th>
                            <th>Weight (KG)</th>
                            <th>Price</th>
                            <th>Tax (%)</th>
                            <th>Type</th>
                            <th>Included In Course</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>
                                    @if($product->product_image)
                                        <img src="{{ asset('assets/images/scubaya/shop/products/'.$product->merchant_key.'/'.$product->id.'-'.$product->product_image) }}" width="100" height="50" alt="Product Image">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->weight }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->tax or '-'}}</td>
                                <td><strong>{{ $productType[$product->product_type] }}</strong></td>
                                <td>
                                    <button type="button" id="incl-in-course{{$product->id}}" onclick="isInclInCourse(this)" class="btn btn-toggle @if($product->incl_in_course == 1) active @endif" data-toggle="button" aria-pressed="@if($product->incl_in_course == 1) true @else false @endif">
                                        <div class="handle"></div>
                                    </button>
                                </td>
                                <td>
                                @if($product->product_status)
                                    <span class="label label-success status">Enabled</span>
                                @else
                                    <span class="label label-danger status">Disabled</span>
                                @endif
                                </td>
                                <td>
                                    <div class="inline-flex">
                                        <a href="{{ route('scubaya::merchant::shop::edit_product', [Auth::id(), $product->shop_id, $product->id]) }}">
                                            <button type="button" class="button-blue btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>

                                        <form class="padding-left5" method="post" action="{{ route('scubaya::merchant::shop::delete_product', [Auth::id(), $product->shop_id, $product->id]) }}">
                                            {{ csrf_field() }}
                                            <button type="button" class="btn btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <tr>
                            <th class="text-center"> No Product Available.</th>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="pagination">
            {{$products->links()}}
        </div>
    </section>

    @include('merchant.layouts.delete_script')

    <script type="text/javascript">
        function isInclInCourse(data)
        {
            var id        = (data.id).replace ( /[^\d.]/g, '' );
            var pId    = parseInt(id, 10);
            var isActive  = jQuery('#'+data.id).attr('aria-pressed');

            jQuery.ajax({
                url:"{{route('scubaya::merchant::shop::included_in_course', [Auth::id()])}}",
                method:'get',
                data:{
                    pId:pId,
                    isIncl:(isActive.trim() == 'true') ? 0 : 1
                }
            });
        }
    </script>
@endsection