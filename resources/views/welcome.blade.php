
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
      <!-- /.navbar-collapse -->

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
         <h1 class="navbar-brand">Football Bet!</h1>

      </div>
    </nav>
  </div>
</header>
<section class="slider">
  <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
    <!-- Indicators -->

    <div class="carousel-inner">






      <div class="item active"> <img data-src="indx/images/slider//1.jpg" alt="First slide" src="{{URL::asset('indx/images/slider//1.jpg')}}">
        <div class="container">
          <div class="carousel-caption">
            <h1>BEST ONLINE BETTING HERE</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium sint delectus numquam deserunt corrupti consectetur as</p>
            @if (Route::has('login'))
              @auth
                <p><a class="btn btn-success" href="{{ route('dashboard') }}" role="button">Go to Bet!</a></p>
              @else
                <p><a class="btn btn-success" href="{{ route('login') }}" role="button">Login/Register</a></p>
              @endauth

            @endif
          </div>
        </div>
      </div>



      <div class="item "> <img data-src="indx/images/slider//2.jpg" alt="First slide" src="{{URL::asset('indx/images/slider//2.jpg')}}">
        <div class="container">
          <div class="carousel-caption">
            <h1>LIVE ONLINE BETTING GAME</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium sint delectus numquam deserunt corrupti consectetur as</p>
            @if (Route::has('login'))
              @auth
                <p><a class="btn btn-success" href="{{ route('dashboard') }}" role="button">Go to Bet!</a></p>
              @else
                <p><a class="btn btn-success" href="{{ route('login') }}" role="button">Login/Register</a></p>
              @endauth

            @endif
          </div>
        </div>
      </div>



    </div>


    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon carousel-control-left"></span></a> <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon carousel-control-right"></span></a> </div>
</section>
<!--end of sldier section-->
<section class="main__middle__container green_bg">
  <div class="container">
    <div class="row">
      <h2 class="text-center">ALL ABOUT THE WEBSITES AND GAMES ARE GOES HERE..</h2>
      <p class="text-center">Live Online Betting, Live Cricket Betting, Live Football Betting, Live Online Game's Betting, Live Ball Trade Game, Live Head & Tail And Many More..</p>

    </div>
  </div>
</section>
<section class="main__middle__container">
  <div class="container">



    <div class="row text-center three-blocks">

      <div class="col-md-4"> <img src="{{ URL::asset('indx/icons/1.png') }}" alt="image" class="img-rounded img-responsive">
        <h3>HEADS AND TAILS</h3>
        <p>Live Head & Tails Game.</p>
        <img src="{{ URL::asset('indx/images/1.jpg') }}" alt="image" class="img-rounded img-responsive">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta, quod debitis nihil numquam. Sapiente, ut.</p>
        @if (Route::has('login'))
          @auth
            <p><a class="btn btn-lg btn-silver" href="{{ route('dashboard') }}" role="button">Play Now</a></p>
          @else
            <p><a class="btn btn-lg btn-silver" href="{{ route('login') }}" role="button">Play Now</a></p>
          @endauth

        @endif
      </div>
      <div class="col-md-4"> <img src="{{ URL::asset('indx/icons/2.png') }}" alt="image" class="img-rounded img-responsive">
        <h3>SEVEN TRADE BALL</h3>
        <p>9 Ball's Thousand's Bidder</p>
        <img src="{{ URL::asset('indx/images/2.jpg') }}" alt="image" class="img-rounded img-responsive">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta, quod debitis nihil numquam. Sapiente, ut.</p>
        @if (Route::has('login'))
          @auth
            <p><a class="btn btn-lg btn-silver" href="{{ route('dashboard') }}" role="button">Play Now</a></p>
          @else
            <p><a class="btn btn-lg btn-silver" href="{{ route('login') }}" role="button">Play Now</a></p>
          @endauth

        @endif
      </div>
      <div class="col-md-4"> <img src="{{ URL::asset('indx/icons/3.png') }}" alt="image" class="img-rounded img-responsive">
        <h3>GAMES BETTING</h3>
        <p>Live Cricket Betting, Live Soccer Betting, And Other's....</p>
        <img src="{{ URL::asset('indx/images/3.jpg') }}" alt="image" class="img-rounded img-responsive">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta, quod debitis nihil numquam. Sapiente, ut.</p>
        @if (Route::has('login'))
          @auth
            <p><a class="btn btn-lg btn-silver" href="{{ route('dashboard') }}" role="button">Play Now</a></p>
          @else
            <p><a class="btn btn-lg btn-silver" href="{{ route('login') }}" role="button">Play Now</a></p>
          @endauth

        @endif
      </div>


    </div>


  </div>
</section>


<footer>
  <div class="container">

    <p class="text-center">&copy; Copyright Football Bet. All Rights Reserved.</p>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="{{ URL::asset('indx/js/jquery.min.js') }}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ URL::asset('indx/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">

$('.carousel').carousel({
  interval: 3500, // in milliseconds
  pause: 'none' // set to 'true' to pause slider on mouse hover
})
</script>
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
