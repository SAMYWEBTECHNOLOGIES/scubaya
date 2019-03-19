@extends('admin.layouts.app')
@section('title','Manage Popular Destinations')
@section('breadcrumb')
    <li><a href="{{route('scubaya::admin::dashboard')}}">Manage</a></li>
    <li class="active"><span>Manage Popular Destinations</span></li>
@endsection
@section('content')
    <section>
        <div class="container screen-fit">
            <div class="box box-primary margin-top-60">
                <div class="box-header">
                    <h3 class="box-title">Manage Popular Destinations</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="dev-table">
                        @if(count($destinations))
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Geographic Area</th>
                                <th>Region</th>
                                <th>Popular</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($destinations as $destination)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td><a href="{{ route('scubaya::destination::destination_details', [ $destination->id, $destination->name]) }}" target="_blank">{{ $destination->name }}</a></td>
                                    <td>{{ $destination->geographical_area }}</td>
                                    <td>{{ $destination->region }}</td>
                                    <td>
                                        <input type="checkbox" class="pop-box" name="is_destination_popular" id="{{$destination->id}}" @if($destination->is_destination_popular) checked @endif>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <th class="text-center"> No Destinations Available.</th>
                            </tr>
                        @endif
                    </table>
                    {{--{{$destination->links()}}--}}
                </div>
            </div>
        </div>
    </section>

    <?php $noOfDestinationsPopular    =   count(\App\Scubaya\model\Destinations::where('is_destination_popular', 1)->get()); ?>
    <script type="text/javascript">
        var count   =  parseInt('<?php echo $noOfDestinationsPopular; ?>');
        jQuery('[name=is_destination_popular]').on('change', function(){
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
                $("<div class=\"container alert alert-danger\" id=\"success\">You can only select <strong>8</strong> destinations as popular!!</div>").insertAfter(".admin-breadcrumbs");
                $('#success').delay(3000).fadeOut();
            } else {
                var url = '{{ route('scubaya::admin::manage::is_destination_popular', ['--ID--']) }}';
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
