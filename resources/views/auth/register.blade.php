<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register |</title>

    <!-- Bootstrap -->
    <link href="{{ URL::asset('gentelella_master/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('gentelella_master/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ URL::asset('gentelella_master/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ URL::asset('gentelella_master/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('gentelella_master/build/css/custom.min.css') }}" rel="stylesheet">
  </head>

  <body class="login">
    <div>

        
  <body class="login">
        <div>
    
          <div class="login_wrapper">
            <div class="animate form login_form">
              <section class="login_content">
                <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                  @csrf
                  <h1>{{ __('Register') }}</h1>
                  <div>
                        <input id="Username" type="text" placeholder="Username" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                      </div>
                      <br>
                  <div>
                    <input id="email" type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                  <br>
                  <div>
                    <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  </div>
                  <br>
                  <div>
                    <div>
                        <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirm" required>
                        @if ($errors->has('password_confirm'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password_confirm') }}</strong>
                        </span>
                    @endif
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="btn btn-danger submit">
                        
                            {{ __('Register') }}
                        </button>
                    </div>
    
    
                  </div>
    
                  <div class="clearfix"></div>
    
                  <div class="separator">
                    <p class="change_link">Are you a member?
                      <a style="color:crimson; font-size:18px;" href="{{route('login')}}" class="to_login">Login</a>
                    </p>
    
                    <div class="clearfix"></div>
                    <br />
    
                    <div>
                      <h1><i class="fa fa-paw"></i> Travelling!</h1>
                      <p>©2018 All Rights Reserved. Travelling! . Privacy and Terms</p>
                    </div>
                  </div>
                </form>
              </section>
            </div>
          </div>
        </div>
      </body>
</html>