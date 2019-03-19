@extends('admin.layouts.app')
@section('title','Manage Popular Dive Centers')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Manage Popular Dive Centers</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">
            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Manage Popular Dive Centers</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="dev-table">
                        @if(count($diveCenters))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Merchant Email</th>
                                <th>Merchant Id</th>
                                <th>Dive Center</th>
                                <th width="40%">Location</th>
                                <th>Popular</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($diveCenters as $center)
                                <?php
                                $merchantDetail  =   \App\Scubaya\model\User::where('id', $center->merchant_key)
                                                            ->first(['email', 'UID']);
                                ?>
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $merchantDetail['email'] }}</td>
                                    <td><a href="#">{{ $merchantDetail['UID'] }}</a></td>
                                    <td><a href="{{ route('scubaya::dive_center_details', [$center->id, $center->name]) }}" target="_blank">{{ $center->name }}</a></td>
                                    <td>{{ $center->address }}</td>
                                    <td>
                                        <input type="checkbox" class="pop-box" name="is_center_popular" id="{{$center->id}}" @if($center->is_center_popular) checked @endif>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Dive Center Available.</th>
                            </tr>
                        @endif
                    </table>
                    {{--{{$diveCenters->links()}}--}}
                </div>
            </div>
        </div>
    </section>

    <?php $noOfCentersPopular    =   count(\App\Scubaya\model\ManageDiveCenter::where('status', PUBLISHED)->where('is_center_popular', 1)->get()); ?>

    <script type="text/javascript">
        var count   =  parseInt('<?php echo $noOfCentersPopular; ?>');
        jQuery('[name=is_center_popular]').on('change', function(){
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
                $("<div class=\"container alert alert-danger\" id=\"success\">You can only select <strong>8</strong> dive centers as popular</div>").insertAfter(".admin-breadcrumbs");
                $('#success').delay(3000).fadeOut();
            } else {
                var url = '{{ route('scubaya::admin::manage::is_center_popular', ['--ID--']) }}';
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
