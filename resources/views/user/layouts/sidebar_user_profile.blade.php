
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>General</h3>
      <ul class="nav side-menu">
        
        <li><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i> Home </a></li>
  
        <li><a href="{{url('user/profile/'.Auth::user()->id)}}"><i class="fa fa-bar-chart-o"></i>Cập nhật hồ sơ thông tin cá nhân</a></li>
        
        <li><a href="#"><i class="fa fa-bar-chart-o"></i>Những chuyến đi của tôi</a></li>
        
        <li><a href="#"><i class="fa fa-bar-chart-o"></i>Những chuyến đi tôi tham gia</a></li>
        
        <li><a href="#"><i class="fa fa-bar-chart-o"></i>Những chuyến đi tôi theo dõi</a></li>

      </ul>
    </div>
    
  
  </div>
  <!-- /sidebar menu -->
  