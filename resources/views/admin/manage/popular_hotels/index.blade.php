@extends('admin.layouts.app')
@section('title','Manage Popular Hotels')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Manage Popular Hotels</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">
            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Manage Popular Hotels</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="dev-table">
                        @if(count($hotels))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Merchant Email</th>
                                <th>Merchant Id</th>
                                <th>Hotel</th>
                                <th width="40%">Location</th>
                                <th>Popular</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hotels as $hotel)
                                <?php
                                $merchantDetail  =   \App\Scubaya\model\User::where('id', $hotel->merchant_primary_id)
                                                            ->first(['email', 'UID']);
                                ?>
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $merchantDetail->email }}</td>
                                    <td><a href="#">{{ $merchantDetail->UID }}</a></td>
                                    <td><a href="{{ route('scubaya::hotel::hotel_details', [$hotel->id, $hotel->name]) }}" target="_blank">{{ $hotel->name }}</a></td>
                                    <td>{{ $hotel->address }}</td>
                                    <td>
                                        <input type="checkbox" class="pop-box" name="is_hotel_popular" id="{{$hotel->id}}" @if($hotel->is_hotel_popular) checked @endif>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Hotels Available.</th>
                            </tr>
                        @endif
                    </table>
                    {{--{{$hotels->links()}}--}}
                </div>
            </div>
        </div>
    </section>

    <?php $noOfHotelsPopular    =   count(\App\Scubaya\model\Hotel::where('is_hotel_popular', 1)->get()); ?>
    <script type="text/javascript">
        var count   =  parseInt('<?php echo $noOfHotelsPopular; ?>');
        jQuery('[name=is_hotel_popular]').on('change', function(){
            var isPopular;

            if(this.checked){
                isPopular   =   1;
                count++;
            } else {
                isPopular   =   0;
                count--;
            }

            if(count == 9) {
                count--;
                this.checked    =   false;
                event.preventDefault();
                $("<div class=\"container alert alert-danger\" id=\"success\">You can only select <strong>8</strong> hotels as popular</div>").insertAfter(".admin-breadcrumbs");
                $('#success').delay(3000).fadeOut();
            } else {
                var url = '{{ route('scubaya::admin::manage::is_hotel_popular', ['--ID--']) }}';
                url     =  url.replace('--ID--', this.id);

                var token   =   '{{ csrf_token() }}';

                jQuery.ajax({
                    url:url,
                    method:'post',
                    data:{is_popular:isPopular, _token:token},
                    success:function(response){
                        console.log('Sucessfully updated!');
                    },
                    error:function(error){
                        console.log('Something went wrong!!');
                    }
                });
            }
        });
    </script>
@endsection
