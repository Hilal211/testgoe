<header class="main-header">
  <!-- Logo -->
  <a href="{{url('/store/dashboard')}}" class="logo">    
    <span class="logo-mini"><b>Go</b></span>
    <span class="logo-lg"><b>Goecolo</b> Store</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    @if(Auth::user()->StoreDetails->is_virtual!='0')
      <a href="{{route('store.admin.login')}}" class="back-button">
        Back to Admin
      </a>
    @endif
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ Html::image(Functions::UploadsPath(config('theme.STORE_UPLOAD')).Functions::GetImageName((Auth::user()->StoreDetails->image ? Auth::user()->StoreDetails->image : "default.jpg"),'-45x40'),'',['class'=>'user-image']) }}
            @if(Auth::user()->StoreDetails->is_virtual=='0')
              <span class="hidden-xs">{{Auth::user()->username}}</span>
            @else
              <span class="hidden-xs">{{Auth::user()->StoreDetails->storename}}</span>
            @endif
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              {{ Html::image(Functions::UploadsPath(config('theme.STORE_UPLOAD')).Functions::GetImageName((Auth::user()->StoreDetails->image ? Auth::user()->StoreDetails->image : "default.jpg"),'-45x40'),"",['class'=>'img-circle']) }}
              @if(Auth::user()->StoreDetails->is_virtual=='0')
              <p>{{Auth::user()->username}}</p>
              @else
              <p>{{Auth::user()->StoreDetails->storename}}</p>
              @endif
              <p>Goecolo Commision - {{Auth::user()->StoreDetails->commision}}%</p>
            </li>
            
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{route("store.profile",\Crypt::encrypt(Auth::user()->id))}}" class="btn btn-default btn-flat">Profile</a>
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