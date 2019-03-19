@extends('front.layouts.app')
@section('title', 'Diver Registration')
@section('content')
    @include('front.layouts.mainheader')
    <section id="diver_registration2" class="margin-top-60">
      <div class="container">
       <div class="panel panel-primary">
           <div class="" style="margin-top: 20px;">
               <h3 class="panel-title text-center">{{strtoupper('Diver ')}} <span class="blue">{{strtoupper('Registration')}}</span></h3>
           </div>
            <form method="post" action="{{route('scubaya::register::diver::register_page_2')}}">
                   {{csrf_field()}}
                   <div class="row">
                        <div class="col-md-12 col-sm-12">
                               <div class="form-group col-md-4">
                                    <label for="logged_dives">No of logged Dives</label>
                                    <input class="form-control"  placeholder="{{'Enter the logged dives' }}" id="logged_dives"  type="text" name="logged_dives" >
                               </div>

                               <div class="from-group col-md-4 ">
                                    <label for="last_dive">Last Dive</label>
                                    <input class="form-control datepicker" data-date-format="dd/mm/yyyy" name="last_dive" id="last_dive" value="{{$_GET['dob'] or ''}}" placeholder="{{'Enter the last dive'}}">
                               </div>

                                <div class="form-group col-md-4">
                                    <label for="select">Never dived?</label></br>
                                    <input name="select" type="checkbox" value="1" > I never Dived
                                </div>
                        </div>
                   </div>

                    <div class="row">
                        <div class=" col-md-12 col-sm-12 ">
                            <h4 class="form-title">Affiliations</h4>
                            <table class="table table-bordered table-hover" id="tableAddRow" name="affiliations[]">
                                <thead>
                                    <tr>
                                        <th>Affiliation</th>
                                        <th>Level</th>
                                        <th>Specialities</th>
                                        <th style="width:10px"><span class="fa fa-plus addBtn" id="addBtn_0"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tr_0">
                                        <td>
                                            <select class="form-control" name="affiliation[]">
                                                <option value="1">Affliation 1</option>
                                                <option value="2">Affliation 2</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="level[]">
                                                <option value="1">level 1</option>
                                                <option value="2">level 2</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="speciality[]">
                                                <option value="1">speciality 1</option>
                                                <option value="2">speciality 2</option>
                                            </select>
                                        </td>
                                        <td><span class="fa fa-minus addBtnRemove" id="addBtnRemove_0"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h4 class="form-title">Select Country where you Dived</h4>
                    <div class="row">
                        <div class=" col-md-12 col-sm-12 ">
                            <div id="map" style="height: 440px; border: 1px solid #AAA;"></div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px">
                        <div class="col-md-12" >
                            <div class="form-group col-md-offset-11">
                                <input type="submit" class="btn btn-primary" name="diver_registration_submit" id="dive_center_submit" value="SUBMIT">
                            </div>
                        </div>
                    </div>
            </form>
        </div>
      </div>
    </section>

    @include('front.layouts.diver_script')

@endsection