@php
    $dynamic_footer_menus   =   \App\Scubaya\model\DynamicContent::where('active',1)->get();
@endphp
<footer>
    <div class="ui container">
        <div class="ui center aligned grid cm-section">
            <div class="column">
                <h2>Scubaya.com</h2>
                <span class="footer-border-bottom"></span>
                <div class="cm-button">
                    <button class="ui inverted button">CHAT</button>
                    <button class="ui inverted button">MAIL</button>
                </div>
            </div>
        </div>
        <div class="ui stackable grid">
            <div class="four wide column">
                <h2>
                    <img src="{{ asset('assets/images/logo/Scubaya-text-logo-original-white.png')}}" width="75%"/>
                </h2>
                <div class="floating ui green label">BETA</div>
                <p class="footer-desc">We share the Ocean</p>
                <ul class="scu-social-icons">
                    <li><a href=" https://www.facebook.com/scubayacom/" target="_blank"><i class="big facebook icon"></i></a></li>
                    <li><a href="https://twitter.com/scubayacom" target="_blank"><i class="big twitter icon"></i></a></li>
                    <li><a href="https://www.instagram.com/scubayacom/" target="_blank"><i class="big instagram icon"></i></a></li>
                </ul>
            </div>
            <div class="three wide column border-left">
                <h5>Consumer</h5>
                <ul>
                    <li><a href="{{ route('scubaya::home') }}">Home</a></li>
                    <li><a href="#">Login</a></li>
                    <li><a href="#">Dive Logs</a></li>
                    <li><a href="#">Customer Service</a></li>
                    <li><a href="#">Feedback and Complaints</a></li>
                </ul>
            </div>
            <div class="three wide column border-left">
                <h5>Business</h5>
                <ul>
                    <li>Home</li>
                    <li>Login</li>
                    <li>Merchant Support</li>
                </ul>
            </div>
            <div class="three wide column border-left">
                <h5>About Scubaya.com</h5>
                <ul>
                    @foreach($dynamic_footer_menus  as $dynamic_footer_menu)
                        <li><a href="{{ route('scubaya::dynamic_content::dynamic_page',[$dynamic_footer_menu->slug]) }}">{{$dynamic_footer_menu->name}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="three wide column border-left">
                <h5>Scubaya.com Partners</h5>
            </div>
            <div class="sixteen wide column">
				<p class="scu-copyright-text text-center">Copyright &copy; 2017 Scubaya B.V. Headquarters: Amsterdam, the Netherlands. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>