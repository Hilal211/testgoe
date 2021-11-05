<div class="gap gap-small"></div>
<div class="row" data-gutter="15">
    
    {{-- <div class="col-md-3">
        <ul class="dropdown-menu dropdown-menu-category dropdown-menu-category-hold dropdown-menu-category-sm">
            <li class="active-default">
                <i class="fa fa-cart-plus dropdown-menu-category-icon"></i>
                <span class="info-tab">{{trans('keywords.Start shopping here')}}</span>
                <br>
                <small>{{trans('keywords.Click the item to add it to cart')}}</small>
            </li>
        </ul>
    </div> --}}
    <div class="col-md-12">
        <ul class="dropdown-menu dropdown-menu-category dropdown-menu-category-hold dropdown-menu-category-sm">
            <li class="active-default">
                <i class="fa fa-map-marker dropdown-menu-category-icon"></i>
                {{-- {{trans('keywords.Postal / Zip Code')}} <small>{{trans('keywords.Click')}} <a href="javascript:;" onclick="ResetCookie()" class="zipLink">{{trans('keywords.here')}}</a> {{trans('keywords.to change zip')}}</small> --}}
                @if(Cookie::get('zip'))
                    <span class="descLocation">{{trans('keywords.We will search stores within 3 miles of your saved Postal / Zip code')}}
                    <strong class='display_zip_code'>{{Cookie::get('zip')}}.</strong> 
                @else
                    <span class="descLocation">{{trans('keywords.We will search stores within 3 miles of your saved Postal / Zip code.')}}
                    <strong class='display_zip_code'>{{trans("keywords.You haven't saved your Postal / Zip code yet.")}}</strong>
                @endif
                {{trans('keywords.Please click')}}

                <a href="javascript:;" onclick="ResetCookie()" class="zipLink">{{trans('keywords.here')}}</a>

                {{trans('keywords.if you want to change Postal / Zip code.')}} </span>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="main-header-input-center">
            <input type="text" class="search-input autocomplete" placeholder="{{trans('keywords.Search the Entire Store...')}}" spellcheck="true"/><a class="main-header-input-center-btn search-btn" href="javascript:;"><i class="fa fa-search"></i></a>
        </div>
    </div>
</div>