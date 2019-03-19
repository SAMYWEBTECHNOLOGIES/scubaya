<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
    <link href="{{asset('assets/semanticui/semantic.css')}}" rel="stylesheet">
    <link href="{{asset('assets/semanticui/semantic.min.css')}}" rel="stylesheet">
    <script src="{{asset('assets/semanticui/semantic.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/semanticui/semantic.min.js')}}" type="text/javascript"></script>
    @php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
    @endphp

</head>

<body>
<section class="payment_menu">

    <div class="ui container">
        <div class="header">
            <h2 style="margin-top: 30px; text-align: center">Payment Options</h2>
        </div>

        @if($Agent->isMobile())
            <div class="ui accordion">
                {{--credit card payment option--}}
                <div class="title">
                    <i class="credit card icon"></i>
                    Credit Cards
                </div>
                <div class="content">
                    <form class="ui form transition hidden">

                        <div class="field">
                            <label>CARD NUMBER</label>
                            <input type="number" name="card_number" placeholder="Enter card number here">
                        </div>

                        <div class="field">
                            <label>NAME ON THE CARD</label>
                            <input type="text" name="card_name" placeholder="Enter name here">
                        </div>

                        <div class="field">
                            <label>EXPIRY DATE</label>
                            <input type="date" class="form-control date-picker" data-date-format="yyyy/mm/dd" id="from" name="from[1]" />
                        </div>

                        <div class="field">
                            <label>CVV code</label>
                            <input type="password" id="cvv_code" maxlength="3"/>
                        </div>


                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox">
                                <label>I agree to the Terms and Conditions</label>
                            </div>
                        </div>

                        <div class="total">
                            <p>Total Amount</p>
                        </div>

                        <button class="ui red button right floated " type="submit"><i class="payment icon"></i>Make payment</button>

                    </form>
                </div>

                {{--paypal payment option--}}
                <div class="title">
                    <i class="paypal icon"></i>
                    PayPal
                </div>

                <div class="content">
                    <form class="ui form ">
                        <h1><i class="paypal icon"><span style="color:#001a35">Pay</span><span style="color: #0055aa">Pal</span></i></h1>

                        <button class="ui red button right floated" type="submit"><i class="payment icon"></i>Make payment</button>
                    </form>

                </div>

            </div>
        @else
            <div class="ui two column divided stackable centered grid  " style="margin-left: 100px;margin-top: 30px">
                <div class="    column">
                    <div class="ui raised segment">
                        <div class="ui two column stackable grid">
                            <div class=" column" style="">
                                <div class="ui secondary vertical menu">
                                    <a class="active item" data-tab="credit"><i class="credit card icon"></i>Credit/Debit cards</a>
                                    <a class="item" data-tab="paypal"><i class="paypal icon"></i>PayPal</a>
                                </div>
                            </div>
                            <div class=" column">
                                <div class="ui tab active " data-tab="credit">
                                    <form class="ui form">

                                        <div class="field">
                                            <label>CARD NUMBER</label>
                                            <input type="number" name="card_number" placeholder="Enter card number here">
                                        </div>

                                        <div class="field">
                                            <label>NAME ON THE CARD</label>
                                            <input type="text" name="card_name" placeholder="Enter name here">
                                        </div>

                                        <div class="field">
                                            <label>EXPIRY DATE</label>
                                            <input type="date" class="form-control date-picker" data-date-format="yyyy/mm/dd" id="from" name="from[1]" />
                                        </div>

                                        <div class="field">
                                            <label>CVV code</label>
                                            <input type="password" id="cvv_code" maxlength="3"/>
                                        </div>


                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox">
                                                <label>I agree to the Terms and Conditions</label>
                                            </div>
                                        </div>

                                        <div class="total">
                                            <p>Total Amount</p>
                                        </div>

                                        <button class="ui red button right floated " type="submit"><i class="payment icon"></i>Make payment</button>

                                    </form>
                                </div>


                                <div class="ui tab" data-tab="paypal">

                                    <form class="ui form ">
                                        <h1><i class="paypal icon"><span style="color:#001a35">Pay</span><span style="color: #0055aa">Pal</span></i></h1>
                                        <div class="btn" style="">
                                            <button class="ui red button right floated" type="submit"><i class="payment icon"></i>Make payment</button>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class=" column">

                    <div class="ui raised card ">
                        <h3 style="text-align: center">Booking Summary</h3>
                        <div class="image" >
                            <img src="{{asset('assets/images/hotel.jpg')}}" style=""/>
                        </div>

                        <div class="content">
                            <h3> <strong>Fortune Hotel </strong> </h3>
                            <div class="meta">
                                <span>Lavassa, Maharashtra</span>
                            </div>
                            {{--<hr style="opacity: .3"/>--}}
                        </div>

                        <div class="content">
                            <table>
                                <tbody>
                                <tr>
                                    <td style="opacity: .3">Check in : 1:00 pm</td>
                                    <td style="padding-left: 90px; opacity: .3">Check out : 12:00 pm</td>
                                </tr>

                                <tr>
                                    <td><strong>27 Nov' 2017</strong>, Mon</td>
                                    <td style="padding-left: 90px"><strong>29 Nov' 2017</strong>, Wed</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr style="opacity: .3"/>

                        <div class="content">
                            <p><strong>Room Features</strong> | <span style="color: #0063dc"><strong>Includes Free Breakfast</strong></span> </p>
                            <p style="opacity: .3">Room 1:</p>
                            <p>No. of peoples</p>

                            <hr style="opacity: .3"/>

                            <div>
                                <p>Name of person</p>
                                <p>Contact Details | email id</p>
                            </div>
                            <hr style="opacity: .3">
                        </div>
                        <hr style="opacity: .3"/>

                        <div>
                            <table>
                                <tbody>
                                <tr>
                                    <th>PRICE BREAKUP</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>Sub Total</td>
                                    <td style="">Amount</td>
                                </tr>
                                </tbody>
                            </table>


                        </div>
                        <hr/>

                        <div class="extra content" style="">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="left floated"><h3><strong>Total(in Rs.)</strong></h3></td>
                                    <td class="right floated"> Amount</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>

</body>
</html>

<script type="text/javascript">
    jQuery(document).ready(function(scubaya){
        scubaya('.menu.vertical .item').tab();
        scubaya('.ui.accordion').accordion();
    });
</script>