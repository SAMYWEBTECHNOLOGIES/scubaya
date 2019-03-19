@extends('front.layouts.master')
@section('page-title')
    Dive Centers
@endsection
@section('content')
    @include('front._partials.header')
    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
    @endphp
    <div id="dive-center-context">
        <section id="dive-section">
            <div class="ui container" >
                <h1 class="text-center">Dive-Centers</h1>
                @if(isset($_GET['query']))
                    <h2>Search for {{$_GET['query']}}</h2>
                @endif
                <form class="ui form">
                    <h4 class="ui dividing header">Filters</h4>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" v-model="name_search" name="" placeholder="Type Dive-center Name">
                        </div>
                        <div class="field">
                            <input type="text" v-model="country_search" name="" placeholder="Type Country">
                        </div>
                        @if($Agent->isMobile())
                            <hr/>
                        @endif
                    </div>
                </form>
            </div>

            <div class="ui container" v-if="items.length">
                <div class="ui link three stackable cards">
                    <div class="card" v-for="item in namefilter">
                        <div class="image" v-if="item.image">
                            <image v-bind:src="image_path + '/' + item.merchant_key + '/' + item.id + '-' + item.image" v-bind:alt="item.name"/>
                        </div>

                        <div class="image" v-else>
                            <image src="{{ asset('assets/images/default.png') }}" alt="Scubaya - Your diving buddy"/>
                        </div>

                        <div  class="content" v-bind:data-info="item.name" >
                            <div class="header">
                                @{{ toTitleCase(item.name) }}
                            </div>
                            <div class="meta">
                                @{{ item.location_address }}
                            </div>
                        </div>

                        <div class="extra content">
                            <a class="ui blue button right floated" v-bind:href="link.replace('__diveCenter_id__/__diveCenter_name__',item.id+'/'+removeSpaceinUrl(item.name))">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui container" v-else>
                <h4 class="text-center paddingTop20">There is no dive center !</h4>
            </div>
        </section>
    </div>
@endsection
@section('script-extra')
    <script src="{{ asset('assets/front/js/vue.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#dive-center-context'
            });
        });

        var app = new Vue({
            el: '#dive-section',
            data: function () {
                return {
                    name_search         :   '{{isset($_GET['name'])?$_GET['name']:''}}',
                    country_search      :   '{{isset($_GET['country'])? str_replace('-', ' ', $_GET['country']):''}}',
                    items               :    JSON.parse('{!! $diveCenters!!}'),
                    image_path          :   '{{asset('assets/images/scubaya/dive_center/')}}',
                    link                :   '{{ route('scubaya::dive_center_details',['__diveCenter_id__','__diveCenter_name__']) }}'
                }
            },
            methods: {
                scubayaSynatx: function (html) {
                    var txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                },
                removeSpaceinUrl: function(itemName){
                    return itemName.split(' ').join('-');
                },
                toTitleCase: function(str){
                    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()});
                }
            },
            computed: {
                namefilter: function () {
                    var self = this;
                    return this.items.filter(function (diveCenters) {
                        var a    =  diveCenters.name.toLowerCase().indexOf(self.name_search.toLowerCase()) >= 0;
                        var b    =   diveCenters.country.toLowerCase().indexOf(self.country_search.toLowerCase()) >= 0;
                        if(a==1 && b==1){
                            return true;
                        } else {
                            return false;
                        }
                    });
                }
            }

        });
    </script>
@endsection







