<div style="background: rgb(51, 122, 183);">
    <header class="smaller">
        <div class="white">
            <h3 id="logo">
                Websites
            </h3>
        </div>
    </header>
</div>
<div class="container screen-fit">
    @if(Session::has('success'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif

    <section>
        <div class="nav-tabs-custom" id="tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#shops" data-toggle="tab" aria-expanded="true">Shops</a></li>
                <li><a href="#hotels" data-toggle="tab" aria-expanded="true">Hotels</a></li>
                <li><a href="#centers" data-toggle="tab" aria-expanded="true">Dive Centers</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active margin-bottom-10 padding-20" id="shops">
                    <!-- / box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            @if(count($websites['shops']))
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $shopCount = 1; @endphp
                                @foreach($websites['shops'] as $website)
                                    <tr>
                                        <td>{{ $shopCount++ }}</td>
                                        <td>{{ ucwords($website->name) }}</td>
                                        <td>{{ $website->address }}</td>
                                        <td>
                                            <select class="form-control" name="shop-status" id="shop-status">
                                                <option value="{{ PUBLISHED }}" @if($website->status == PUBLISHED) selected @endif>Published</option>
                                                <option value="{{ UNPUBLISHED }}" @if($website->status == UNPUBLISHED) selected @endif>Unpublished</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No shop Found.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="tab-pane margin-bottom-10 padding-20" id="hotels">

                    <!-- / box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            @if(count($websites['hotels']))
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $hotelCount = 1; @endphp
                                @foreach($websites['hotels'] as $website)
                                    <tr>
                                        <td>{{ $hotelCount++ }}</td>
                                        <td>{{ ucwords($website->name) }}</td>
                                        <td>{{ $website->address }}</td>
                                        <td>
                                            <select class="form-control" name="hotel-status" id="hotel-status">
                                                <option value="{{ PUBLISHED }}" @if($website->status == PUBLISHED) selected @endif>Published</option>
                                                <option value="{{ UNPUBLISHED }}" @if($website->status == UNPUBLISHED) selected @endif>Unpublished</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No hotel Found.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="tab-pane margin-bottom-10 padding-20" id="centers">

                    <!-- / box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            @if(count($websites['centers']))
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $centerCount = 1; @endphp
                                @foreach($websites['centers'] as $website)
                                    <tr>
                                        <td>{{ $centerCount++ }}</td>
                                        <td>{{ ucwords($website->name) }}</td>
                                        <td>{{ $website->address }}</td>
                                        <td>
                                            <select class="form-control" name="center-status" id="center-status">
                                                <option value="{{ PUBLISHED }}" @if($website->status == PUBLISHED) selected @endif>Published</option>
                                                <option value="{{ UNPUBLISHED }}" @if($website->status == UNPUBLISHED) selected @endif>Unpublished</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <th class="text-center"> No dive center Found.</th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
