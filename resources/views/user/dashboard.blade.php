@extends('user.layouts.app')
@section('title','Dashboard')
@section('contentheader')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> User</a></li>
        <li class="active">Dashboard</li>
    </ol>
@endsection
@section('content')
    <section class="content margin-20">
        <div class="row user-dashboard-box">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class ="box-header with-border">
                        <h3 class="box-title">Latest Log Dives</h3>
                    </div>
                    <div class="box-body">
                        <div class ="row">
                            <div class="col-md-4">
                                <div class="box-with-shadow logged-dives">
                                    <p>{{count($diveLogs)}}
                                        @if($diveLogPercentage > 0)
                                            <i class="fa fa-arrow-up float-right"></i>
                                        @else
                                            <i class="fa fa-arrow-down float-right"></i>
                                        @endif
                                    </p>
                                    <p>Total Logged Dives</p>
                                    <p>{{number_format($diveLogPercentage,2)}} %</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box-with-shadow average-depth">
                                    <p>{{number_format($averageDepth,2)}} mtr
                                        @if($depthPercentage > 0)
                                            <i class="fa fa-arrow-up float-right"></i>
                                        @else
                                            <i class="fa fa-arrow-down float-right"></i>
                                        @endif
                                    </p>
                                    <p>Average Depth</p>
                                    <p>{{number_format($depthPercentage,2)}} %</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box-with-shadow average-dive-time">
                                    <p>{{number_format($averageDiveTime,2)}} Minutes
                                        @if($totalTimePercentage > 0)
                                            <i class="fa fa-arrow-up float-right"></i>
                                        @else
                                            <i class="fa fa-arrow-down float-right"></i>
                                        @endif
                                    </p>
                                    <p>Average Dive Time</p>
                                    <p>{{number_format($totalTimePercentage,2)}} %</p>
                                </div>
                            </div>
                        </div>
                        <p class ="cool-statistics">Cool Statistics</p>
                        <div class="row latest-dive-sites">
                            <div class ="col-md-6">
                                <p>Latest Dive Sites</p>
                            </div>
                            <div class ="col-md-6">
                                <input type="text" class="form-control search-dive-site" id ="search-dive-site"  onkeyup="diveSiteFilter()"  placeholder="Search dive site..">
                                <i class='fa fa-search search-icon-user-dashboard'></i>
                            </div>
                        </div>
                        <ul id="all-dive-site" class="list-group">
                            @if($diveSiteData)
                                @foreach($diveSiteData as $diveSite)
                                    <li class="list-group-item align-middle">
                                        <div class ="row">
                                            <div class="col-md-6">
                                                <img src="{{asset('assets/images/scubaya/dive_sites/'.$diveSite->id.'-'.$diveSite->image)}}" class="img-circle" width="60px" height="60px">
                                                <a href="#">{{ucwords($diveSite->name)}}</a>
                                            </div>

                                            <div class="col-md-6 country-dive-info">
                                                <span><i class="fa fa-globe"></i></span>
                                                <span class="country-dive-site ">{{ucwords($diveSite->country)}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class ="box-header with-border">
                        <h3 class="box-title">Recent Activities</h3>
                    </div>
                    <div class="box-body">
                        <ul class = "list-group recent-activities">
                            <li class = "list-group-item align-middle">
                                <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="img-circle" width="30px" height="30px" />
                                <span style ="font-size: 13px;">test user added you as a buddy</span>
                                <span style="font-size: 10px;color: darkgray"> 1 Hour Ago</span>
                            </li>
                            <li class = "list-group-item align-middle">
                                <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="img-circle" width="30px" height="30px" />
                                <span style ="font-size: 12px;">test user added you as a buddy</span>
                                <span style="font-size: 10px;color: darkgray"> 1 Hour Ago</span>
                            </li>
                            <li class = "list-group-item align-middle">
                                <img src="{{asset('assets/images/user2-160x160.jpg')}}" class="img-circle" width="30px" height="30px" />
                                <span style ="font-size: 12px;">test user added you as a buddy</span>
                                <span style="font-size: 10px;color: darkgray"> 1 Hour Ago</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function diveSiteFilter() {
        var input, filter, allDiveSite, filterName, a, i;

        input       = document.getElementById("search-dive-site");
        filter      = input.value.toUpperCase();
        allDiveSite = document.getElementById("all-dive-site");
        filterName  = allDiveSite.getElementsByTagName("li");

        for (i = 0; i < filterName.length; i++) {

            a = filterName[i].getElementsByTagName("a")[0];

            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                filterName[i].style.display = "";
            } else {
                filterName[i].style.display = "none"; 
            }
        }
    }

</script>
