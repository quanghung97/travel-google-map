<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
      <ul class="nav side-menu">
        <li>
          <a>
            <i class="fa fa-home"></i> Home
            <span class="fa fa-chevron-down"></span>
          </a>
          <ul class="nav child_menu">
            <li>
              <a>
                <i class="fa fa-bar-chart-o"></i> Top 10 kế hoạch hot nhất</a>
            </li>
            <li>
              <a href="{{url('user/home/newest')}}">
                <i class="fa fa-bar-chart-o"></i> 10 kế hoạch mới</a>
            </li>
            <li>
              <a href="{{url('user/home/newestmem')}}">
                <i class="fa fa-bar-chart-o"></i> 10 thành viên mới</a>
            </li>
          </ul>
        </li>

        <!-- <li>
          <a href="{{url('user/userProfile/profile/'.Auth::user()->id)}}">
            <i class="fa fa-bar-chart-o"></i>Thông tin cá nhân</a>
        </li> -->
  
        <li>
          <a href="{{url('user/trip')}}">
            <i class="fa fa-bar-chart-o"></i>Những chuyến đi của tôi</a>
        </li>
  
        <li>
          <a href="#">
            <i class="fa fa-bar-chart-o"></i>Những chuyến đi tôi tham gia</a>
        </li>
  
        <li>
          <a href="#">
            <i class="fa fa-bar-chart-o"></i>Những chuyến đi tôi theo dõi</a>
        </li>

      </ul>
  </div>


</div>
<!-- /sidebar menu -->