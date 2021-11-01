<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="{{(Route::getCurrentRoute()->getName()=='store.orders' ? "active" : "")}}">
        <a href="{{route('store.orders')}}">
          <i class="fa fa-shopping-cart"></i> <span>Orders</span>
          <span class="label label-default pull-right">{{$counters['orders']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='store_owner.products' ? "active" : "")}}">
        <a href="{{ url('/store/' . Auth::user()->StoreDetails->id . '/products')}}">
          <i class="fa fa-sitemap"></i> <span>Products</span>
        </a>
      </li>
      @if(Auth::user()->StoreDetails->is_virtual == 0)
        <li class="{{(Route::getCurrentRoute()->getName()=='store.new_product' ? "active" : "")}}">
          <a href="{{ route('store.new_product',['storeid'=>Auth::user()->StoreDetails->id]) }}">
            <i class="fa fa-plus"></i> <span>Request Product</span>
            <span class="label label-default pull-right">{{$counters['requestCount']}}</span>
          </a>
        </li>
      @endif
      <li class="{{(Route::getCurrentRoute()->getName()=='store.rating' ? "active" : "")}}">
        <a href="{{ route('store.rating') }}">
          <i class="fa fa-star"></i> <span>Ratings</span>
          <span class="label label-default pull-right">{{$counters['ratingCount']}}</span>
        </a>
      </li>
      @if(Auth::user()->StoreDetails->homedelievery=='1')
      <li class="{{(Route::getCurrentRoute()->getName()=='store.settings' ? "active" : "")}}">
        <a href="{{ route('store.settings',['storeid'=>Auth::user()->StoreDetails->id]) }}">
          <i class="fa fa-cog"></i> <span>Settings</span>
        </a>
      </li>
      @endif
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>