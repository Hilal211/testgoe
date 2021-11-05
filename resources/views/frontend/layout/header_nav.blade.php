<nav class="navbar navbar-default navbar-main-white yamm">
    <div class="navbar-header">
        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#main-nav-collapse" area_expanded="false"><span class="sr-only">Main Menu</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="main-nav-collapse">
        <div class="">
            @if(Auth::check() && Auth::user()->hasrole('customer'))
            <div class="containerNav">
            <div class="main-header-logo-center">
                <a href="{{route('frontend.home')}}">
                    {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center-'.App::getLocale().'.png',"") }}
                </a>
            </div>
            <div>
            <ul class="nav navbar-nav navbar-center">
                <li class="dropdown yamm-fw"><a href="#">
                        <span><i class="fa fa-user" aria-hidden="true"></i></span>
                        {{trans('keywords.Welcome')}}, {{Auth::user()->FullName}}
                    </a>
                    <ul id="logged_in_menu" class="dropdown-menu">
                        <li class="yamm-content">
                            <div class="row -row-eq-height row-col-border">
                                <ul class="dropdown-menu-items-list">
                                    <li><a href="{{route("pages.profile",[App::getLocale(),\Crypt::encrypt(Auth::user()->id)])}}">{{trans('keywords.My Profile')}}</a></li>
                                    <li><a href="{{route("pages.order",[App::getLocale(),\Crypt::encrypt(Auth::user()->id)])}}">{{trans('keywords.My Orders')}}</a></li>
                                    {{-- <li><a href="{{route("pages.settings",[App::getLocale(),\Crypt::encrypt(Auth::user()->id)])}}">{{trans('keywords.Settings')}}</a>
                        </li> --}}
                        <li><a href="{{route("pages.logout")}}">{{trans('keywords.Logout')}}</a></li>
                    </ul>
        </div>
        </li>
        </ul>
        </li>
        <li class="dropdown yamm-fw"><a href="javascript:void(0)" onclick="reloadCartItem();"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                <div id="total-cart-items">Cart </div>
            </a></li>
        <li class="dropdown yamm-fw show-tooltip" title="{{trans('keywords.If you need to make suggestions please click here')}}"><a href="{{url('/contact-us')}}">
                <span><i class="fa fa-comments" aria-hidden="true"></i></span>{{ trans('keywords.Feedback') }}</a></li>
        @if(App::getLocale()=='en')
        <li class="dropdown yamm-fw"><a href="{{LaravelLocalization::getLocalizedURL('fr')}}">
                <span><i class="fa fa-language" aria-hidden="true"></i></span>Français</a></li>
        @elseif(App::getLocale()=='fr')
        <li class="dropdown yamm-fw"><a href="{{LaravelLocalization::getLocalizedURL('en')}}">
                <span><i class="fa fa-language" aria-hidden="true"></i></span>English</a></li>
        @else

        @endif
        {{-- <li class="dropdown yamm-fw"><a href="javascript:;"><span><i class="fa fa-globe" aria-hidden="true"></i></span>Canada</a></li> --}}
        </ul>
            </div></div>
        @else
        <div class="containerNav">
            <div class="main-header-logo-center">
                <a href="{{route('frontend.home')}}">
                    {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center-'.App::getLocale().'.png',"") }}
                </a>
            </div>
            <div>
            <ul class="nav navbar-nav navbar-center">
                <li class="dropdown yamm-fw"><a href="{{url('/how-it-works')}}">
                        <span><i class="fa fa-info" aria-hidden="true"></i></span>{{ trans('keywords.How it works') }}</a></li>
                <li class="dropdown yamm-fw"><a href="{{url('/buyer-register')}}">
                        <span><i class="fa fa-user" aria-hidden="true"></i></span>{{ trans('keywords.Register as Shopper') }}</a></li>
                <li class="dropdown yamm-fw"><a href="{{url('/store-owner')}}">
                        <span><i class="fa fa-cube" aria-hidden="true"></i></span>{{ trans('keywords.Register as Store Owner') }}</a></li>
                <li class="dropdown yamm-fw"><a href="#nav-login-dialog" data-effect="mfp-move-from-top" class="popup-text">
                        <span><i class="fa fa-unlock" aria-hidden="true"></i></span>{{ trans('keywords.Login') }}</a>
                </li>
                {{-- <li class="dropdown yamm-fw hide"><a href="#"><i class="fa fa-shopping-cart"></i></a>
                </li> --}}
                <li class="dropdown yamm-fw"><a href="javascript:void(0)" onclick="reloadCartItem();"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                        <div id="total-cart-items">Cart </div>
                    </a></li>
                <li class="dropdown yamm-fw show-tooltip" title="{{trans('keywords.If you need to make suggestions please click here')}}"><a href="{{url('/contact-us')}}">
                        <span><i class="fa fa-comments" aria-hidden="true"></i></span>{{ trans('keywords.Feedback') }}</a></li>
                @if(App::getLocale()=='en')
                <li class="dropdown yamm-fw"><a href="{{LaravelLocalization::getLocalizedURL('fr')}}">
                        <span><i class="fa fa-language" aria-hidden="true"></i></span>Français</a></li>
                @elseif(App::getLocale()=='fr')
                <li class="dropdown yamm-fw"><a href="{{LaravelLocalization::getLocalizedURL('en')}}">
                        <span><i class="fa fa-language" aria-hidden="true"></i></span>English</a></li>
                @else

                @endif
                {{-- <li class="dropdown yamm-fw"><a href="javascript:;"><span><i class="fa fa-globe" aria-hidden="true"></i></span>Canada</a></li> --}}
            </ul>
            </div>
        </div>
        @endif
    </div>
    @include('frontend.layout.login_modal')
    @include('frontend.layout.newsletter_subscription_modal')
    </div>
</nav>