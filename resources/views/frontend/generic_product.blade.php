@extends('frontend.layout.home_default')
@section('content')
<div class="col-md-12">
	@include('common.message')
</div>
<div class="col-md-3">
	@include('frontend.layout.menu',['cats'=>$cats,'selected_cat'=>Route::current()->getParameter('cat')])
</div>
<div class="col-md-9">
	<div class="col-md-12">
		<div class="row product-borerded-group">
			@foreach($selectedCat as $cat)
				@foreach($cat->sub_cats as $subCat)
					<div class="col-md-12 sub-category-section">
						<h5 class="dropdown-menu-category-title">{{\App::getLocale()=='en' ? $subCat->category_name : $subCat->fr_category_name}}</h5>
						<ul class="dropdown-menu-category-list">
							@foreach($subCat->products as $pro)
								<li>
									<a href="javascript:void(0);" onclick="addToCart({{$pro->id}});">
										{{ Html::image(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).Functions::GetImageName($pro->image,'-32x32'),"",['width'=>'32','height'=>'32']) }}
										<span class="show-tooltip" title="{{$pro->productnamewithprice}}">
											{{Functions::getShortenText($pro->productnamewithprice,'13')}}
										</span>
									</a>
								</li>
							@endforeach
						</ul>
					</div>
				@endforeach
			@endforeach
	    </div>
	</div>
</div> 
@include('common.zip_modal')
<div class="gap gap-small"></div>
@stop

@section('page_custom_js')
<SCRIPT type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY"></SCRIPT>
@stop