@extends('front.layouts.master')
@section('page-title')
    Checkout
@endsection
@section('content')
    @include('front._partials.header')

    <div class="ui  container segment course-checkout">
        <div class="ui grid">
            <div class="wide column course-background" style="background-image: url('{{asset('assets/images/scubaya/dive_center/courses/'.$merchant_key.'/'.$courseId.'-'.$courses->image)}}')">
            </div>
        </div>

        <div class="ui grid">
            <div class="five wide column">
                <div class="segment">
                    <h1 class="ui header">{{$courses->course_name}}</h1>
                </div>
            </div>

            <?php $pricing    =   (array)json_decode($courses->course_pricing); ?>

            <div class="five wide column">
               {{--nothing here as of now, will introduce if needed--}}
            </div>

            <div class="eight wide column">
                <h3>Included</h3>
                <div class="ui bulleted list">
                    @if(empty($excluded))
                        {{'nothing in this list'}}
                    @else
                    @foreach ($included as $item)
                        <div class="item">{{$item->title}}</div>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="eight wide column">
                <h3>Not Included</h3>
                <div class="ui bulleted list">
                    @if(empty($excluded))
                        {{'nothing in this list'}}
                    @else
                        @foreach ($excluded as $item)
                            <div class="item">{{$item->title}}</div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
            <form class="ui form" method="post" action="{{route('scubaya::courses_checkout::add_to_courses_cart')}}" id="course_add_to_cart">
                <input type="hidden" name="course_id" value="{{$courseId}}">
                <div class="fields">
                    <div class="four wide field">
                        <h3>Check In</h3>
                        <div class="ui calendar datepicker" id="checkin">
                            <div class="ui input right icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="check_in" placeholder="Check In Time" value="{{old('check_in')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="four wide field">
                        <h3>Check Out</h3>
                        <div class="ui calendar datepicker" id="checkout">
                            <div class="ui input right icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="check_out" placeholder="Check Out Time" value="{{old('check_out')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="four wide field">
                        <h3>Number of divers</h3>
                        <div class="example">
                            <div class="ui input">
                                <a class="ui delete-user label"><i class="minus icon"></i></a>
                                    <input type="text" value="1" disabled="">
                                <a class="ui add-user label"><i class="plus icon"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="sixteen wide field">
                        <div class="ui existing segment diver_list">
                            <div id="dlist">
                                <h2>Diver One</h2>
                                <div class="ui form">
                                    <div class="three fields">
                                        <div class="field">
                                            <label for="diver_first_name">First name</label>
                                            <input type="text" name="details[1][first_name]" value="" id="diver_first_name">
                                        </div>
                                        <div class="field">
                                            <label>Birth date</label>
                                            <div class="ui calendar datepicker" id="birthdate">
                                                <div class="ui input right icon">
                                                    <i class="calendar icon"></i>
                                                    <input type="text" name="details[1][birthdate]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label for="user_id">User ID</label>
                                            <input type="text" name="details[1][user_id]" id="user_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="text-center">{{@$exchangeRate[$merchant_key]['symbol']}}{{number_format(($pricing['price']) * $exchangeRate[$merchant_key]['rate'])}}</h2>
                <div class="text-center">
                    <button class="ui primary button" type="submit"><i class="shop icon"></i>Add To Cart</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
@section('script-extra')
    <script type="text/javascript">

        $('#checkin').calendar({
            type: 'date'
        });

        $('#checkout').calendar({
            type: 'date'
        });

        $('#birthdate').calendar({
            type: 'date'
        });

        $(".add-user,.delete-user").click(function (e) {
            let counter = $(this).parent().parent().find('input').val() ;

            if ($(this).hasClass('delete-user')) {
                if(counter >1){
                    $(this).parent().find('input').val(function (i, oldval) {
                        return parseInt(oldval, 10) - 1;
                    });

                    $('.diver_list div:last').parent().parent().parent().remove();
                }
            }

            if ($(this).hasClass('add-user')) {
                if(counter <8){
                    counter++;

                    $(this).parent().find('input').val(function (i, oldval) {
                        return parseInt(oldval, 10) + 1;
                    });

                    let num_to_words    =   {
                        1   :   'One',
                        2   :   'Two',
                        3   :   'Three',
                        4   :   'Four',
                        5   :   'Five',
                        6   :   'Six',
                        7   :   'Seven',
                        8   :   'Eight'
                    };

                    $('.diver_list').append('<div id="'+ num_to_words[counter] + '">\n' +
                        '<h2>Diver ' + num_to_words[counter] + '</h2>\n' +
                        '<div class="ui form">\n' +
                        '<div class="three fields">\n' +
                        '<div class="field">\n' +
                        '<label for="diver_first_name">First name</label>\n' +
                        ' <input type="text" name="details['+counter+'][first_name]" id="diver_first_name">\n' +
                        '</div>\n' +
                        '<div class="field">\n' +
                        '<label>Birth date</label>\n' +
                        '<div class="ui calendar datepicker" id="birthdate'+num_to_words[counter]+'">\n' +
                        '<div class="ui input right icon">\n' +
                        '<i class="calendar icon"></i>\n' +
                        '<input type="text" name="details['+counter+'][birthdate]">\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class="field">\n' +
                        '<label for="user_id">User ID</label>\n' +
                        '<input type="text" name="details['+counter+'][user_id]" id="user_id">\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>');

                    $('#birthdate'+num_to_words[counter]).calendar({
                        type: 'date'
                    });
                }
            }
        });
        /*jquery validation*/
        $( "#course_add_to_cart" ).validate({
            rules: {
                check_in:{
                    required: true
                },
                check_out:{
                    required: true
                }
            },
            messages:{
                check_in:{
                    required:"Enter your check in date"
                },
                check_out:{
                    required:"Enter your check out date"
                }
            },
            errorPlacement: function (error, element) {
                    error.insertAfter($(element).parent('div')).css('color','red');
            }
        });
    </script>
@endsection