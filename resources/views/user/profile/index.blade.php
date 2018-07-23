@extends('user.layouts.app_user_profile')
@section('css')
<style>
.card_avt {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: 10px;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: cadetblue;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

button:hover, a:hover {
  opacity: 0.7;
}   

/* .upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.btn {
  border: 2px solid gray;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
} */

</style>
@endsection
@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <br/>
                        <br/>
                        @if(count($errors) > 0)
					<div class="alert alert-danger">
						@foreach($errors->all() as $err)
						<strong>{{ $err }}</strong><br/>                          
						@endforeach
					</div>
					@endif

					@if(session('message'))
					<div class="alert alert-success">
						<strong>{{ session('message') }}</strong>
					</div>
					@endif
                    <div class="clearfix"></div>
                    <div class="row">
                        <form action="{{url('user/profile/'.$user->id)}}" method="POST">
                            <div class="card_avt col-md-6">
                                    {{-- <img src="{{asset('avatar/defaut_avt.jpg')}}" alt="{{$user->name}}" style="width:100%">
                                    <div style="margin: 24px 0;">
                                        <a href="#"><i class="fa fa-dribbble"></i></a> 
                                        <a href="#"><i class="fa fa-twitter"></i></a>  
                                        <a href="#"><i class="fa fa-linkedin"></i></a>  
                                        <a href="#"><i class="fa fa-facebook"></i></a> 
                                    </div> --}}
                                    
                                    <form id="img-upload-form" method="POST" accept-charset="utf-8" onsubmit="return submitImageForm(this)">
                                            <img id="logo-img" onclick="document.getElementById('add-new-logo').click();" src="{{$user->g_avatar_url}}" alt="Cập nhật ảnh đại diện"/>
                                            <input type="file" style="display: none" id="add-new-logo" name="file" accept="image/*" onchange="addNewLogo(this)"/>
                                    </form>
                                    
                                    {{-- <div class="upload-btn-wrapper">
                                            <button class="btn">Upload a file</button>
                                            <input type="file" name="myfile" />
                                    </div> --}}
                            </div> 
                            <div class="col-md-6">
                                            {{ csrf_field() }}
                                            <div>
                                                <label>Tên Người Dùng</label>
                                                <input type="text" class="form-control" name="name" aria-describedby="basic-addon1" value="{{ $user->name }}">
                                            </div>
                                            <br>
                                            <div>
                                                <label>Địa Chỉ Email</label>
                                                <input type="email" class="form-control" name="email" aria-describedby="basic-addon1" value="{{ $user->email }}" 
                                                readonly
                                                >
                                            </div>
                                            <br>	
                                            <div class="form-group">
                                                <p><label>Bạn có muốn thay đổi mật khẩu?</label></p>
                                                <p>
                                                    <label class="radio-inline">
                                                        <input name="change_password" id="yes" class="radio-change" value="1"
                                                        type="radio"><span for="yes">Có</span>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="change_password" id="no" class="radio-change" value="0"
                                                        type="radio" checked=""><span for="no">Không</span>
                                                    </label>
                                                </p>
                                                <input class="form-control input-width disabled-field" type="password" name="password" placeholder="Nhập mật khẩu" disabled="" />
                                            </div>
                    
                                            <div class="form-group">
                                                <p><label>Xác nhận Mật khẩu</label></p>
                                                <input class="form-control input-width disabled-field" type="password" name="password_again" placeholder="Nhập lại mật khẩu" disabled="" />
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Update
                                            </button>
                    
                        </form>
                            </div>
                        </div>
                    </div>
                </div>

                


            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $('input:radio[name="change_password"]').on('change',function(){
		if(this.checked && this.value == 0)
			$('.disabled-field').attr('disabled',true);
		else
			$('.disabled-field').attr('disabled',false);
    });
    
    var addNewLogo = function(input){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //Hiển thị ảnh vừa mới upload lên
                $('#logo-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            //submit form để upload ảnh
            $('#img-upload-form').submit();
        }
    }

    var submitImageForm = function(form){
        toggleLoading(); //show loading mask
        $.ajax({
            url: "/user/upload", //api upload phía server
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                toggleLoading();            
                alert('thành công');
            },
            error: function (data) {
            toggleLoading();
            }
    });
    return false;
}

</script>
@endsection

