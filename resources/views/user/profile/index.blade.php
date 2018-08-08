@extends('user.layouts.app_user') @section('css')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@endsection @section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <br/>
                    <br/> @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                        <strong>{{ $err }}</strong>
                        <br/> @endforeach
                    </div>
                    @endif
                    <div class="clearfix"></div>
                    <div class="row">

                            {!! Form::model($user, [
                                'method' => 'PATCH',
                                'url' => ['user/userProfile/profile/'.$user->id],
                                'class' => 'form-horizontal',
                                'files' => true
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="col-md-4">

                                <aside class="profile-card">

                                    <header>
                                        <!-- Hình của bạn -->
                                        @if($user->g_avatar_url)
                                        <img src="{{asset($user->g_avatar_url)}}" id="logo-img" onclick="document.getElementById('add-new-logo').click();"> @else
                                        <img src="{{asset('avatar/defaut_avt.jpg')}}" id="logo-img" onclick="document.getElementById('add-new-logo').click();"> @endif

                                        <input class="form-control" type="file" style="display: none" id="add-new-logo" name="file" accept="image/*" onchange="addNewLogo(this)" />

                                        <!-- Tên của bạn -->
                                        <h1>- {{$user->name}} -</h1>

                                        <!-- Công việc hay nghề của bạn -->
                                        <h2>- Web Developer -</h2>

                                    </header>

                                    <body>

                                        <div class="profile-bio">

                                            <p>Chào mừng các bạn</p>
                                            <p>Tôi là một nhà phát triển web. Tôi chủ yếu làm việc với PHP, HTML, CSS, JS và
                                                WordPress.
                                                <br />Tôi cũng làm việc tốt với Photoshop, Corel Draw, After Effects và vài thứ
                                                khác.</p>

                                        </div>

                                    </body>

                                    <!-- Thêm thông tin của bạn -->


                                    <!-- Liên kết mạng xã hội -->
                                    <ul class="profile-social-links">

                                        <!-- twitter - el clásico  -->
                                        <li>
                                            <a href="https://twitter.com/">
                                                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/social-twitter.svg">
                                            </a>
                                        </li>

                                        <!-- envato – use this one to link to your marketplace profile -->
                                        <li>
                                            <a href="https://facebook.com">
                                                <img src="http://farm5.staticflickr.com/4221/34651248062_73641b1a80_o.png">
                                            </a>
                                        </li>

                                        <!-- Tài khoản Google +-->
                                        <li>
                                            <a href="https://plus.google.com/u/{{$user->g_id}}/">
                                                <img src="http://farm5.staticflickr.com/4186/34004419773_ed97040ef5_o.png">
                                            </a>
                                        </li>

                                        <!--Bạn có thể thêm hoặc xóa mạng xã hội tại đây-->

                                    </ul>

                                </aside>
                                <!-- Chỉ có vậy thôi ! -->


                            </div>
                            <div class="col-md-6" style="padding: 10px; margin: 10px">
                                <div class="container">

                                    <div>
                                        <label>Tên Người Dùng</label>
                                        <input type="text" class="form-control" name="name" aria-describedby="basic-addon1" value="{{ $user->name }}" @cannot('updateProfile', $user) readonly @endcannot>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Địa Chỉ Email</label>
                                        <input type="email" class="form-control" name="email" aria-describedby="basic-addon1" value="{{ $user->email }}" readonly>
                                    </div>
                                    <br>
                                    @can('updateProfile', $user)
                                    <div class="form-group">
                                        <p>
                                            <label>Bạn có muốn thay đổi mật khẩu?</label>
                                        </p>
                                        <p>
                                            <label class="radio-inline">
                                                <input name="change_password" id="yes" class="radio-change" value="1" type="radio">
                                                <span for="yes">Có</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input name="change_password" id="no" class="radio-change" value="0" type="radio" checked="">
                                                <span for="no">Không</span>
                                            </label>
                                        </p>
                                        <input class="form-control input-width disabled-field" type="password" name="password" placeholder="Nhập mật khẩu" disabled=""
                                        />
                                    </div>

                                    <div class="form-group">
                                        <p>
                                            <label>Xác nhận Mật khẩu</label>
                                        </p>
                                        <input class="form-control input-width disabled-field" type="password" name="password_again" placeholder="Nhập lại mật khẩu"
                                            disabled="" />
                                    </div>
                                    <br>
                               
                                    <button type="submit" class="btn btn-primary">Update
                                    </button>
                                @endcan
                                {!! Form::close() !!}

                        </div>

                        </div>
                    </div>
                </div>
            </div>




        </div>
    </div>
</div>
@endsection @section('js')
<script>
    $('input:radio[name="change_password"]').on('change', function () {
        if (this.checked && this.value == 0)
            $('.disabled-field').attr('disabled', true);
        else
            $('.disabled-field').attr('disabled', false);
    });
</script>
<script src="{{asset('js/changeImage.js')}}"></script>
@endsection
