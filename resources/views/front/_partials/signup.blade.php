<div class="ui mini modal scu-signup">
    <div class="header text-center">
        Create account
    </div>
    <div class="content">
        <form class="ui form" id="signup-form" method="post" action="{{route('scubaya::register::registration',['redirect'  =>  urlencode(url()->current())])}}">
            {{csrf_field()}}
            <!-- for showing errors-->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li >{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="field">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" placeholder="" id="first_name" name="first_name" value="{{old('first_name')}}" required>
            </div>

            <div class="field">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" placeholder="" id="last_name" name="last_name" value="{{old('last_name')}}" required>
            </div>

            <div class="field">
                <label for="sign_up_email">Email</label>
                <input type="email" class="form-control" placeholder="" id="sign_up_email" name="email" value="{{old('email')}}" required>
            </div>

            <div class="field">
                <label for="sign_up_password">Password</label>
                <input type="password" class="form-control" placeholder="" id="sign_up_password" name="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <p class="text-center">Already have an account ? <a href="#" class="scu-signin-btn">Login</a></p>

            <div class="text-center">
                <input type="submit" class="ui blue button" name="save">
            </div>

            <div class="text-center">By creating an account you agree with the <br />
                <a href="{{ route('scubaya::toc') }}">Terms and Condition</a> of Scubaya.com
            </div>

            <h4 class="text-center">I am a Merchant and want to add business<br/>
            <a href="{{route('scubaya::register::create_account')}}">Create Merchant Account</a></h4>
        </form>
    </div>
</div>
