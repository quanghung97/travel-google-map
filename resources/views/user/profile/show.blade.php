@extends('user.layouts.app_user')
@section('css')


  <link rel="stylesheet" href="{{ URL::asset('cart_interaction/css/style.css')}}">

@endsection
@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ url('/player/football-bet') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <div class="container">
                          <br>
                          <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            {{-- <ol class="carousel-indicators">
                              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                              <li data-target="#myCarousel" data-slide-to="1"></li>
                              <li data-target="#myCarousel" data-slide-to="2"></li>
                              <li data-target="#myCarousel" data-slide-to="3"></li>
                            </ol> --}}

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">

                              <div class="item active">
                                <img src="{{ URL::asset('MatchSlide/banner.jpg') }}" alt="Chania" style="width: 100%; height:300px">
                                <div class="carousel-caption" style="bottom: initial; top: 50%; transform: translateY(-50%)">
                                  <p style="font-size:50px; text-shadow: 4px 4px 2px rgba(150, 150, 150, 1);">{{ $match->team1->name }} vs {{ $match->team2->name }}</p>
                                  <p style="font-size:15px; ">Thời gian bắt đầu trận đấu: {{ $match->time_begin }}</p>
                                  <p style="font-size:15px;">Thời gian ngừng cược: {{ $match->time_out }}</p>
                                </div>
                              </div>
{{--
                              <div class="item">
                                <img src="{{ URL::asset('MatchSlide/banner.jpg') }}" alt="Chania" style="width: 100%; height:300px">
                                <div class="carousel-caption" style="bottom: initial; top: 50%; transform: translateY(-50%)">
                                  <h3>Chania</h3>
                                  <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                </div>
                              </div>

                              <div class="item">
                                <img src="{{ URL::asset('MatchSlide/banner.jpg') }}" alt="Chania" style="width: 100%; height:300px">
                                <div class="carousel-caption" style="bottom: initial; top: 50%; transform: translateY(-50%)">
                                  <h3>Chania</h3>
                                  <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                </div>
                              </div>

                              <div class="item">
                                <img src="{{ URL::asset('MatchSlide/banner.jpg') }}" alt="Chania" style="width: 100%; height:300px">
                                <div class="carousel-caption" style="bottom: initial; top: 50%; transform: translateY(-50%)">
                                  <h3>Chania</h3>
                                  <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                </div>
                              </div> --}}

                            </div>

                            <!-- Left and right controls -->
                            {{-- <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                              <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                              <span class="sr-only">Next</span>
                            </a> --}}
                          </div>
                        </div>
                        <br/>
                        <br/>
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($match, [
                            'method' => 'PATCH',
                            'url' => ['/player/football-bet', $match->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>

                                      <th>
                                        <main>
                                          Tỉ lệ cược chấp: x <span style="color: #f44141">{{ $match->handicap_bet->t1_rate }}</span>
                                          <br>
                                          {{ $match->team1->name }} chấp {{ $match->team2->name }}: <span style="color: #f44141">{{ $match->handicap_bet->t1_handicap }}</span> bàn
                                        </main>
                                      </th>
                                      <td>
                                        <main>
                                            <div class="col-xs-4 col-xs-offset-4">
                                              <label for="price">Số tiền bạn muốn đặt</label>
                                              {!! Form::number('t1_t2_price', null, ('' == 'required') ? [ 'id' => 't1_t2_price','class' => 'form-control input-sm', 'required' => 'required','pattern'=>'\d*','min'=>'0'] : ['class' => 'form-control','pattern'=>'\d*','min'=>'0']) !!}
                                              {!! $errors->first('t1_t2_price', '<p class="help-block">:message</p>') !!}
                                            </div>
                                            <div class="">
                                              <a href="#0" id="t1_t2_add" class="identity cd-add-to-cart btn btn-sm">Add to Bet</a>
                                            </div>
                                        </main>
                                      </td>
                                    </tr>
                                    <tr>
                                      <th>
                                        <main>
                                          Tỉ lệ cược chấp: x <span style="color: #f44141">{{ $match->handicap_bet->t2_rate }}</span>
                                          <br>
                                            {{ $match->team2->name }} chấp {{ $match->team1->name }}: <span style="color: #f44141">{{ $match->handicap_bet->t2_handicap }}</span> bàn
                                        </main>
                                      </th>
                                      <td>

                                          <main>
                                              <div class="col-xs-4 col-xs-offset-4">
                                                <label for="price">Số tiền bạn muốn đặt</label>
                                                {!! Form::number('t2_t1_price', null, ('' == 'required') ? [ 'id' => 't2_t1_price','class' => 'form-control input-sm', 'required' => 'required','pattern'=>'\d*','min'=>'0'] : ['class' => 'form-control','pattern'=>'\d*','min'=>'0']) !!}
                                                {!! $errors->first('t2_t1_price', '<p class="help-block">:message</p>') !!}
                                              </div>
                                              <div class="">
                                                <a href="#0" id="t2_t1_add" class="cd-add-to-cart btn btn-sm">Add to Bet</a>
                                              </div>
                                          </main>

                                      </td>
                                    </tr>
                                    <tr>

                                      <th>
                                        <main>
                                          <h3>Cược cửa trên</h3>

                                          Tỉ lệ cược O/U: x <span style="color: #f44141">{{ $match->o_u_bet->rate }}</span>
                                          <br>
                                            Tổng số bàn thắng: <span style="color: #f44141">{{ $match->o_u_bet->total }}</span> bàn

                                        </main>
                                      </th>
                                      <td>

                                          <main>
                                              <div class="col-xs-4 col-xs-offset-4">
                                                <label for="price">Số tiền bạn muốn đặt</label>
                                                {!! Form::number('over_door', null, ('' == 'required') ? [ 'id' => 'over_door_price','class' => 'form-control input-sm', 'required' => 'required','pattern'=>'\d*','min'=>'0'] : ['class' => 'form-control','pattern'=>'\d*','min'=>'0']) !!}
                                                {!! $errors->first('over_door', '<p class="help-block">:message</p>') !!}
                                              </div>
                                              <div class="">
                                                <a href="#0" id="over_door" class="cd-add-to-cart btn btn-sm">Add to Bet</a>
                                              </div>
                                          </main>

                                      </td>
                                    </tr>
                                    <tr>

                                      <th>
                                        <main>
                                          <h3>Cược cửa dưới</h3>

                                          Tỉ lệ cược O/U: x <span style="color: #f44141">{{ $match->o_u_bet->rate }}</span>
                                          <br>
                                            Tổng số bàn thắng: <span style="color: #f44141">{{ $match->o_u_bet->total }}</span> bàn

                                        </main>
                                      </th>
                                      <td>

                                          <main>
                                              <div class="col-xs-4 col-xs-offset-4">
                                                <label for="price">Số tiền bạn muốn đặt</label>
                                                {!! Form::number('under_door', null, ('' == 'required') ? [ 'id' => 'under_door_price','class' => 'form-control input-sm', 'required' => 'required','pattern'=>'\d*','min'=>'0'] : ['class' => 'form-control','pattern'=>'\d*','min'=>'0']) !!}
                                                {!! $errors->first('under_door', '<p class="help-block">:message</p>') !!}
                                              </div>
                                              <div class="">
                                                <a href="#0" id="under_door" class="cd-add-to-cart btn btn-sm">Add to Bet</a>
                                              </div>
                                          </main>

                                      </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="">

                          <div class="cd-cart-container empty">
                            <a href="#0" class="cd-cart-trigger" style="background-color: #f5f5f5;">
                              Cart
                              <ul class="count"> <!-- cart items count -->
                                <li>0</li>
                                <li>0</li>
                              </ul> <!-- .count -->
                            </a>

                            <div class="cd-cart">
                              <div class="wrapper">
                                <header>
                                  <h2>Bet Check Board</h2>
                                  {{-- <span class="undo">Item removed. <a href="#0">Undo</a></span> --}}
                                </header>

                                <div class="body">
                                  <ul>
                                  <!-- other products added to the cart -->
                                  </ul>
                                </div>

                                <div id="footer">
                                  {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Đặt cược', ['class' => 'checkout btn', 'style' => 'margin-bottom: 0px ;background: #2A3F54']) !!}
                                </div>
                              </div>
                            </div> <!-- .cd-cart -->
                          </div> <!-- cd-cart-container -->

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

</script>
<script>
$( document ).ready(function() {
jQuery(document).ready(function($){
  var cartWrapper = $('.cd-cart-container');
  //product id - you don't need a counter in your real project but you can use your real product id
  var productId = 0;

  if( cartWrapper.length > 0 ) {
    //store jQuery objects
    var cartBody = cartWrapper.find('.body')
    var cartList = cartBody.find('ul').eq(0);
    var cartTotal = cartWrapper.find('.checkout').find('span');
    var cartTrigger = cartWrapper.children('.cd-cart-trigger');
    var cartCount = cartTrigger.children('.count')
    //var addToCartBtn = $('.cd-add-to-cart');
    var addToCartBtn1 = $('#t1_t2_add');
    var addToCartBtn2 = $('#t2_t1_add');
    var addToCartBtn3 = $('#over_door');
    var addToCartBtn4 = $('#under_door');
    //var get price for each input

    var undo = cartWrapper.find('.undo');
    var undoTimeoutId;

    //add bet to cart1
    addToCartBtn1.one('click', function(event){
      event.preventDefault();
      addToCart1($(this));
    });

    //add bet to cart2
    addToCartBtn2.one('click', function(event){
      event.preventDefault();
      addToCart2($(this));
    });

    //add bet to cart3
    addToCartBtn3.one('click', function(event){
      event.preventDefault();
      addToCart3($(this));
    });

    //add bet to cart4
    addToCartBtn4.one('click', function(event){
      event.preventDefault();
      addToCart4($(this));
    });

    //open/close cart
    cartTrigger.on('click', function(event){
      event.preventDefault();
      toggleCart();
    });

    //close cart when clicking on the .cd-cart-container::before (bg layer)
    cartWrapper.on('click', function(event){
      if( $(event.target).is($(this)) ) toggleCart(true);
    });

    //delete an item from the cart
    cartList.on('click', '.delete-item', function(event){
      event.preventDefault();
      removeProduct($(event.target).parents('.product'));
    });

    //update item quantity
    cartList.on('change', 'select', function(event){
      quickUpdateCart();
    });

    //reinsert item deleted from the cart
    undo.on('click', 'a', function(event){
      clearInterval(undoTimeoutId);
      event.preventDefault();
      cartList.find('.deleted').addClass('undo-deleted').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
        $(this).off('webkitAnimationEnd oanimationend msAnimationEnd animationend').removeClass('deleted undo-deleted').removeAttr('style');
        quickUpdateCart();
      });
      undo.removeClass('visible');
    });
  }

  function toggleCart(bool) {
    var cartIsOpen = ( typeof bool === 'undefined' ) ? cartWrapper.hasClass('cart-open') : bool;

    if( cartIsOpen ) {
      cartWrapper.removeClass('cart-open');
      //reset undo
      clearInterval(undoTimeoutId);
      undo.removeClass('visible');
      cartList.find('.deleted').remove();

      setTimeout(function(){
        cartBody.scrollTop(0);
        //check if cart empty to hide it
        if( Number(cartCount.find('li').eq(0).text()) == 0) cartWrapper.addClass('empty');
      }, 500);
    } else {
      cartWrapper.addClass('cart-open');
    }
  }

  function addToCart1(trigger) {
    var cartIsEmpty = cartWrapper.hasClass('empty');
    //update cart product list
    addProduct1();
    //update number of items
    updateCartCount(cartIsEmpty);
    //update total price
    updateCartTotal(trigger.data('price'), true);
    //show cart
    cartWrapper.removeClass('empty');
  }

  function addProduct1() {
      var productAdded = $('<li class="product"><div class="product-details"><h3><a href="#0">{{ $match->team1->name }} chấp {{ $match->team2->name }}: <span style="color: #f44141">{{ $match->handicap_bet->t1_rate }}</span> bàn</a></h3><span class="price">Rate x {{ $match->handicap_bet->t1_handicap }}</span></div></li>');
      cartList.prepend(productAdded);
  };

  function addToCart2(trigger) {
    var cartIsEmpty = cartWrapper.hasClass('empty');
    //update cart product list
    addProduct2();
    //update number of items
    updateCartCount(cartIsEmpty);
    //update total price
    updateCartTotal(trigger.data('price'), true);
    //show cart
    cartWrapper.removeClass('empty');
  }

  function addProduct2() {
      var productAdded = $('<li class="product"><div class="product-details"><h3><a href="#0">{{ $match->team2->name }} chấp {{ $match->team1->name }}: <span style="color: #f44141">{{ $match->handicap_bet->t2_rate }}</span> bàn</a></h3><span class="price">Rate x {{ $match->handicap_bet->t2_handicap }}</span></div></li>');
      cartList.prepend(productAdded);
  };

  function addToCart3(trigger) {
    var cartIsEmpty = cartWrapper.hasClass('empty');
    //update cart product list
    addProduct3();
    //update number of items
    updateCartCount(cartIsEmpty);
    //update total price
    updateCartTotal(trigger.data('price'), true);
    //show cart
    cartWrapper.removeClass('empty');
  }

  function addProduct3() {
      var productAdded = $('<li class="product"><div class="product-details"><h3><a href="#0">Cửa trên với tổng bàn: <span style="color: #f44141">{{ $match->o_u_bet->total }}</span> </a></h3><span class="price">Rate x {{ $match->o_u_bet->rate }}</span></div></li>');
      cartList.prepend(productAdded);
  };

  function addToCart4(trigger) {
    var cartIsEmpty = cartWrapper.hasClass('empty');
    //update cart product list
    addProduct4();
    //update number of items
    updateCartCount(cartIsEmpty);
    //update total price
    updateCartTotal(trigger.data('price'), true);
    //show cart
    cartWrapper.removeClass('empty');
  }

  function addProduct4() {
    var productAdded = $('<li class="product"><div class="product-details"><h3><a href="#0">Cửa dưới với tổng bàn: <span style="color: #f44141">{{ $match->o_u_bet->total }}</span> </a></h3><span class="price">Rate x {{ $match->o_u_bet->rate }}</span></div></li>');
      cartList.prepend(productAdded);
  };

  function removeProduct(product) {
    clearInterval(undoTimeoutId);
    cartList.find('.deleted').remove();

    var topPosition = product.offset().top - cartBody.children('ul').offset().top ,
      productQuantity = Number(product.find('.quantity').find('select').val()),
      productTotPrice = Number(product.find('.price').text().replace('$', '')) * productQuantity;

    product.css('top', topPosition+'px').addClass('deleted');

    //update items count + total price
    updateCartTotal(productTotPrice, false);
    updateCartCount(true, -productQuantity);
    undo.addClass('visible');

    //wait 8sec before completely remove the item
    undoTimeoutId = setTimeout(function(){
      undo.removeClass('visible');
      cartList.find('.deleted').remove();
    }, 8000);
  }

  function quickUpdateCart() {
    var quantity = 0;
    var price = 0;

    cartList.children('li:not(.deleted)').each(function(){
      var singleQuantity = Number($(this).find('select').val());
      quantity = quantity + singleQuantity;
      price = price + singleQuantity*Number($(this).find('.price').text().replace('$', ''));
    });

    cartTotal.text(price.toFixed(2));
    cartCount.find('li').eq(0).text(quantity);
    cartCount.find('li').eq(1).text(quantity+1);
  }

  function updateCartCount(emptyCart, quantity) {
    if( typeof quantity === 'undefined' ) {
      var actual = Number(cartCount.find('li').eq(0).text()) + 1;
      var next = actual + 1;

      if( emptyCart ) {
        cartCount.find('li').eq(0).text(actual);
        cartCount.find('li').eq(1).text(next);
      } else {
        cartCount.addClass('update-count');

        setTimeout(function() {
          cartCount.find('li').eq(0).text(actual);
        }, 150);

        setTimeout(function() {
          cartCount.removeClass('update-count');
        }, 200);

        setTimeout(function() {
          cartCount.find('li').eq(1).text(next);
        }, 230);
      }
    } else {
      var actual = Number(cartCount.find('li').eq(0).text()) + quantity;
      var next = actual + 1;

      cartCount.find('li').eq(0).text(actual);
      cartCount.find('li').eq(1).text(next);
    }
  }

  function updateCartTotal(price, bool) {
    bool ? cartTotal.text( (Number(cartTotal.text()) + Number(price)).toFixed(2) )  : cartTotal.text( (Number(cartTotal.text()) - Number(price)).toFixed(2) );
  }
});
});


</script> <!-- Resource jQuery -->

@endsection
