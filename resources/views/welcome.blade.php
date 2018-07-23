
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<title> Football-Bet</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="{{ URL::asset('indx/css/bootstrap.min.css') }}" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<link href="{{ URL::asset('indx/css/style.css') }}" rel="stylesheet">




  <link rel="shortcut icon" href="images/fav.png" type="image/png">

<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
</head>
<body>
<header class="main__header">
  <div class="container">
    <nav class="navbar navbar-default">

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">Home</a></li>
          <li><a href="contact">contact us</a></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li>
            @if (Route::has('login'))
              @auth
                 <a href="{{ route('dashboard') }}">Go to Bet!</a>
              @else
                 <a href="{{ route('login') }}">Login/Register</a>
              @endauth

            @endif

          </li>

        </ul>
      </div>
    </nav>
  </div>
</header>
</body>

</html>


            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
