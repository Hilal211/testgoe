<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.orders' ? "active" : "")}}">
        <a href="{{route('admin.orders')}}">
          <i class="fa fa-shopping-cart"></i> <span>Orders</span>
          <span class="label label-default pull-right">{{$counters['orders']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.store.list' ? "active" : "")}}">
        <a href="{{route('admin.store.list')}}">
          <i class="fa fa-cube"></i> <span>Stores</span>
          <span class="label label-default pull-right">{{$counters['stores']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.virtual-stores.index' ? "active" : "")}}">
        <a href="{{route('admin.virtual-stores.index')}}">
          <i class="fa fa-cube"></i> <span>Virtual Stores</span>
          <span class="label label-default pull-right">{{$counters['virtualStores']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.shoppers' ? "active" : "")}}">
        <a href="{{route('admin.shoppers')}}">
          <i class="fa fa-user"></i> <span>Shoppers</span>
          <span class="label label-default pull-right">{{$counters['customers']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.cats-subcats' ? "active" : "")}}">
        <a href="{{route('admin.cats-subcats')}}">
          <i class="fa fa-sitemap"></i> <span>Products</span>
          <span id="product_counter" class="label label-default pull-right">{{$counters['products']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.price.logs' ? "active" : "")}}">
        <a href="{{route('admin.price.logs')}}">
          <i class="fa fa-usd"></i> <span>Product Price Logs</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.product-request' ? "active" : "")}}">
        <a href="{{route('admin.product-request')}}">
          <i class="fa fa-sitemap"></i> <span>Product Requests</span>
          <span class="label label-default pull-right">{{$counters['requestCount']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.pickups' ? "active" : "")}}">
        <a href="{{route('admin.pickups')}}">
          <i class="fa fa-truck"></i> <span>Pickups</span>
          <span class="label label-default pull-right">{{$counters['pickupsCount']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.requested.users' ? "active" : "")}}">
        <a href="{{route('admin.requested.users')}}">
          <i class="fa fa-user"></i> <span>Service Requests</span>
          <span class="label label-default pull-right">{{$counters['serviceRequestCount']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.ratings' ? "active" : "")}}">
        <a href="{{route('admin.ratings')}}">
          <i class="fa fa-star"></i> <span>Ratings</span>
          <span class="label label-default pull-right">{{$counters['ratingsCount']}}</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.newsletter' ? "active" : "")}}">
        <a href="{{route('admin.newsletter')}}">
          <i class="fa fa-envelope"></i> <span>Newsletter</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.newsletter.subscriptions' ? "active" : "")}}">
        <a href="{{route('admin.newsletter.subscriptions')}}">
          <i class="fa fa-envelope"></i> <span>Newsletter Subscriptions</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.coupons.index' ? "active" : "")}}">
        <a href="{{route('admin.coupons.index')}}">
          <i class="fa fa-money"></i> <span>Coupons</span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.get.tax' ? "active" : "")}}">
        <a href="{{route('admin.get.tax')}}">
          <i class="fa fa-percent"></i> <span>Tax Management</span>
          <span class="label label-default pull-right"></span>
        </a>
      </li>


      <button class="dropdown-btn hilal"><i class="iconleft fa fa-bar-chart"></i>Analytics
        <i class="iconright fa fa-caret-down"></i>
      </button>
      <div class="dropdown-container">
        <ul class="sidebar-menu" style="background-color:#808080;">

          <li class="{{(Route::getCurrentRoute()->getName()=='admin.get.statistics' ? "active" : "")}}">
            <a href="{{route('admin.get.statistics')}}">
              <i class="fa fa-bar-chart"></i> <span>Analytics</span>
              <span class="label label-default pull-right"></span>
            </a>
          </li>

          <li class="{{(Route::getCurrentRoute()->getName()=='admin.get.statorder' ? "active" : "")}}">
            <a href="{{route('admin.get.statorder')}}">
              <i class="fa fa-percent"></i> <span>stat product</span>
              <span class="label label-default pull-right"></span>
            </a>
          </li>
          <li class="{{(Route::getCurrentRoute()->getName()=='admin.get.statorderstore' ? "active" : "")}}">
            <a href="{{route('admin.get.statorderstore')}}">
              <i class="fa fa-percent"></i> <span>stat store</span>
              <span class="label label-default pull-right"></span>
            </a>
          </li>
        </ul>
      </div>


      <li class="{{(Route::getCurrentRoute()->getName()=='admin.get.announcement' ? "active" : "")}}">
        <a href="{{route('admin.get.announcement')}}">
          <i class="fa fa-bullhorn"></i> <span>Announcementt</span>
          <span class="label label-default pull-right"></span>
        </a>
      </li>
      <li class="{{(Route::getCurrentRoute()->getName()=='admin.settings' ? "active" : "")}}">
        <a href="{{route('admin.settings')}}">
          <i class="fa fa-cog"></i> <span>Settings</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>


<script>
  /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
  // var dropdown = document.getElementsByClassName("dropdown-btn");
  // var i;

  // for (i = 0; i < dropdown.length; i++) {
  //   dropdown[i].addEventListener("click", function() {
  //   this.classList.toggle("active");
  //   var dropdownContent =document.getElementsByClassName("dropdown-container");
  //   if (dropdownContent.style.display === "block") {
  //   dropdownContent.style.display = "none";
  //   } else {
  //   dropdownContent.style.display = "block";
  //   }
  //   });
  // }

  var dropdown = document.getElementsByClassName("dropdown-btn");
  var i;

  for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var dropdownContent = this.nextElementSibling;
      if (dropdownContent.style.display === "block") {
        dropdownContent.style.display = "none";
      } else {
        dropdownContent.style.display = "block";
      }
    });
  }
</script>
<style>
  .hilal {
    padding: 10px;
    width: 100%;
    background-color: #666;
    border: none;
    color: #b0c7c7;
    text-align: left;
  }

  .iconleft {
    margin: 5px 8px 0px 7px;
  }
  .iconright{
    /* float: right; */
    margin-left: 111px;
  }
</style>