@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Dashboard</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$stores}}</h3>
            <p>Stores</p>
          </div>
          <div class="icon">
            <i class="fa fa-cube"></i>
          </div>
          <a href="{{route('admin.store.list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$customers}}</h3>
            <p>Shoppers</p>
          </div>
          <div class="icon">
            <i class="fa fa-user"></i>
          </div>
          <a href="{{route('admin.shoppers')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{$product}}</h3>
            <p>Products</p>
          </div>
          <div class="icon">
            <i class="fa fa-sitemap"></i>
          </div>
          <a href="{{route('admin.cats-subcats')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{$order}}</h3>
            <p>Orders</p>
          </div>
          <div class="icon">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <a href="{{route('admin.orders')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Stores</div>
          <div class="panel-body">
            <div id="map"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@stop
@section('left_sidebar')

@stop
@section('custom_js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY">
</script>
<script>
  function SaveMainCategory(form) {
    var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
        var Id = $(form).find('input[name="id"]').val();
        if (Id == '0') {
          $('#CatsHolder').find('.categories-group').find('.panel:first').after(res.CategoryHtml);
          //window.location.reload();
        } else {
          var CategoryHolder = $('#main_category_' + Id);
          CategoryHolder.after(res.CategoryHtml);
          CategoryHolder.remove();
        }
        $(form).parents('.modal').modal('hide');

        var SubList = $('#ProductModal').find('select[name="cat_id"]');
        $(SubList).select2('destroy');
        $(SubList).html('');
        $.each(res.catsoptions, function(index, catsoptions) {
          var HTML = "<option value='" + index + "'>" + catsoptions + "</option>";
          $(SubList).append(HTML);
        });

        $(SubList).select2({
          placeholder: "Select",
          allowClear: true,
        });
      },
      error: function(jqXHR, exception) {
        var Response = jqXHR.responseText,
          ErrorBlock = $(form).find('.form-errors'),
          Response = $.parseJSON(Response);
        DisplayErrorMessages(Response, ErrorBlock, 'ul');
      }
    }
    $(form).ajaxSubmit(options);
    return false;
  }
</script>
<script>
  var locations = {
    !!$pins!!
  };
  var centerPin = new google.maps.LatLng(locations[0][1], locations[0][2]);
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: centerPin,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var infowindow = new google.maps.InfoWindow();

  var marker, i;

  for (i = 0; i < locations.length; i++) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map: map
    });

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
      }
    })(marker, i));
  }
  google.maps.event.addDomListener(window, "resize", function() {
    var center = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(center);
  });
</script>

@stop