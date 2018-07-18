
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
      <li><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i> Home </a>

      </li>
      @role('admin')
      <li><a href="{{ url('admin/admin/user') }}"><i class="fa fa-edit"></i> Manager Users </a>

      </li>
      @endrole
      @hasanyrole('admin|sub_admin')

      <li><a href="{{ url('admin/match') }}"><i class="fa fa-desktop"></i> Manager Matches </a>

      </li>

      <li><a><i class="fa fa-clone"></i>Manager Posts <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
          <li><a href="fixed_footer.html">Fixed Footer</a></li>
        </ul>
      </li>
      @endhasanyrole
      @role('admin')
      <li><a><i class="fa fa-retweet"></i>Set Up Rate Bets <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('/admin/admin/handicap-bet') }}">Handicap Bet</a></li>
          <li><a href="{{ url('/admin/admin/o-u-bet') }}">O/U Bet</a></li>
          <li><a href="{{ url('/admin/admin/true-result-bet') }}">True Result Bet</a></li>
        </ul>
      </li>
      <li><a href="{{ url('admin/admin/team') }}"><i class="fa fa-sitemap"></i> Manager Teams </a>

      </li>
      <li><a><i class="fa fa-table"></i> Roles and Permissions <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('admin/admin/role') }}">Role</a></li>
          <li><a href="{{ url('admin/admin/permission') }}">Permission</a></li>
        </ul>
      </li>
      @endrole

    </ul>
  </div>


</div>
<!-- /sidebar menu -->
