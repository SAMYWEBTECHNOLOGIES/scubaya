@extends('front.layouts.master')
@section('page-title')
    Dive-centers
@endsection
@section('content')

    @include('front._partials.header')
    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
    @endphp
    <div id="destination-context">
        <section id="destination-section">
            <div class="ui container" >
                <h1 class="text-center">Destinations</h1>

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
                    <div class=" card" v-for="item in namefilter">
                        <div class="image">
                            <image v-bind:src="image_path + '/' + '/' + item.id + '-' + item.image" v-bind:alt="item.name"/>
                        </div>

                        <div  class="content" v-bind:data-info="item.name" >
                            <div class="header">
                                @{{ toTitleCase(item.name) }}
                            </div>
                            <div class="meta">
                                @{{ item.location }}
                            </div>
                        </div>

                        <div class="extra content">
                            <a class="ui blue button right floated" v-bind:href="link.replace('__destination_id__/__destination_name__', item.id+'/'+removeSpaceInUrl(item.name))">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui container" v-else>
                <h4 class="text-center paddingTop20">There is no destination !</h4>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/front/js/vue.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sub-header').sticky({
                context: '#destination-context'
            });
        });

        new Vue({
            el: '#destination-section',
            data: function () {
                return {
                    name_search         :   '{{isset($_GET['name'])?$_GET['name']:''}}',
                    country_search      :   '{{isset($_GET['country'])? str_replace('-', ' ', $_GET['country']):''}}',
                    items               :    JSON.parse('{!! $destinations!!}'),
                    image_path          :   '{{asset('assets/images/scubaya/destination/')}}',
                    link                :   '{{ route('scubaya::destination::destination_details',['__destination_id__','__destination_name__']) }}'
                }
            },
            methods: {
                scubayaSynatx: function (html) {
                    var txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                },
                removeSpaceInUrl: function(itemName){
                    return itemName.split(' ').join('-');
                },
                toTitleCase: function(str){
                    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()});
                }
            },
            computed: {
                namefilter: function () {
                    var self = this;
                    return this.items.filter(function (destination) {
                        var a    =  destination.name.toLowerCase().indexOf(self.name_search.toLowerCase()) >= 0;
                        var b    =   destination.country.toLowerCase().indexOf(self.country_search.toLowerCase()) >= 0;
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