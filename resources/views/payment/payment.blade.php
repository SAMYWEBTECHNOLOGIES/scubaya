@extends('front.layouts.app')
@section('content')
@include('front.layouts.mainheader')
<script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
<link href="{{asset('assets/css/front.css')}}">
<link href="{{asset('assets/semanticui/semantic.css')}}" rel="stylesheet">
<link href="{{asset('assets/semanticui/semantic.min.css')}}" rel="stylesheet">
<script src="{{asset('assets/semanticui/semantic.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/semanticui/semantic.min.js')}}" type="text/javascript"></script>

@php
use Jenssegers\Agent\Agent as Agent;
$Agent = new Agent();
@endphp

<style>
 .content
  {
  min-height:0px;
  }
</style>

<section class="payment_menu" style="margin-top: 60px;">
<div class="ui container">
   <div class="header" id="scu-title-payment">
      <h2 class="text-center">Payment Options</h2>
   </div>

@if($Agent->isMobile())
<div class="ui accordion">

               {{--Credit Cards section--}}
<div class="title" id="scu-acc-title">
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

            {{--PayPal section--}}
 <div class="title " id="scu-acc-title">
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

<div class="ui two column divided stackable  grid" style="margin-top: 30px;" id="scu-payment-section">
    <div class="column">
    <div class="ui raised segment">
       <div class="ui two column stackable grid">
         <div class=" column">
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
<div class="column">
<div class="ui raised card ">
<h2 style="text-align: center">Booking Summary</h2>
<div class="image" >
<img src="{{asset('assets/images/hotel.jpg')}}" />
</div>

<div class="content" style="min-height: 0px;">
<h4> <strong>Fortune Hotel </strong> </h4>
<div class="meta">
<span>Lavassa, Maharashtra</span>
</div>
</div>

<div class="content" style="min-height: 0px;">
<table>
<tbody>
<tr>
<td class="meta">Check in : 1:00 pm</td>
<td style="padding-left: 90px;" class="meta">Check out : 12:00 pm</td>
</tr>
<tr>
<td><strong>27 Nov' 2017</strong>, Mon</td>
<td style="padding-left: 90px"><strong>29 Nov' 2017</strong>, Wed</td>
</tr>
</tbody>
</table>
</div>
<hr/>

<div class="content" style="min-height: 0px;">
<p><strong>Room Features</strong> | <span style="color: #0063dc"><strong>Includes Free Breakfast</strong></span> </p>
<p class="meta">Room 1:</p>
<p>No. of peoples</p>
<hr/>

<div>
<p>Name of person</p>
<p>Contact Details | email id</p>
</div>
<hr/>
</div>
<hr/>

<div>
<table>
<tbody>
<tr>
<th>PRICE BREAKUP</th>
<th></th>
</tr>
<tr>
<td>Sub Total</td>
<td>Amount</td>
</tr>
</tbody>
</table>
</div>
<hr/>

<div class="extra content">
<table>
<tbody>
<tr>
<td class="left floated"><h4><strong>Total(in Rs.)</strong></h4></td>
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
<script>
$(document).ready(function(){
$('.menu.vertical .item').tab();
$('.ui.accordion').accordion();
});
</script>