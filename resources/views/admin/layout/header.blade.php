<header class="main-header">
  <!-- Logo -->
  <a href="{{url('/admin/dashboard')}}" class="logo">    
    <span class="logo-mini"><b>Go</b></span>
    <span class="logo-lg"><b>Goecolo</b> Admin</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ Html::image(config('theme.ASSETS').config('theme.ADMIN_IMG').'user2-160x160.jpg',"",['class'=>'user-image']) }}
            <span class="hidden-xs">{{Auth::user()->username}}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              {{ Html::image(config('theme.ASSETS').config('theme.ADMIN_IMG').'user2-160x160.jpg',"",['class'=>'img-circle']) }}
              <p>
                {{Auth::user()->username}}
              </p>
            </li>
            
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{route('pages.admin_profile')}}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a href="{{route("pages.logout")}}" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>