{{-- $cats --}}

<ul id="ProductMenu"  class="dropdown-menu dropdown-menu-category dropdown-menu-category-hold dropdown-menu-category-sm">
@foreach($cats as $cat)
    @if(count($cat->sub_cats)>0)
    <li>
    <a href="{{route('frontend.generic.cat',[App::getLocale(),$cat->slug,""])}}" class="{{($cat->slug==@$selected_cat ?  'active' : '')}}">
    {{ Html::image(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).Functions::GetImageName($cat->icon,'-16x16'),"",['class'=>'dropdown-menu-category-image']) }}
    @if(\App::getLocale()=='en')
        {{-- {{(strlen($cat->category_name) > 30 ? substr($cat->category_name,0,30).'...' : $cat->category_name)}} --}}
        {{(strlen($cat->category_name) > 25) ? substr($cat->category_name,0,25).'...' : $cat->category_name}}
    @else
        {{-- {{(strlen($cat->fr_category_name) > 30 ? substr($cat->fr_category_name,0,30).'...' : $cat->fr_category_name)}} --}}
        {{(strlen($cat->fr_category_name) > 25) ? substr($cat->fr_category_name,0,25).'...' : $cat->fr_category_name}}
    @endif
    </a>
        <div class="dropdown-menu-category-section">
            <div class="dropdown-menu-category-section-inner">
            @if($cat->bg_image!='')
                <div class="dropdown-menu-category-section-content cat-background-img" style="background:url('{{url(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$cat->bg_image)}}')">
            @else
                <div class="dropdown-menu-category-section-content cat-background-img">
            @endif    
                    <div class="row">
                    @foreach($cat->sub_cats as $subCat)
                        @if(count($subCat->products)>0)
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
                                    @if($subCat->products_count>8)
                                        <a href="{{route('frontend.generic.cat',[App::getLocale(),$cat->slug,$subCat->slug])}}" class="pull-right m-top-5">Load More</a>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
               {{--  
                @if($cat->bg_image!='')
                    {{ Html::image(,"",'') }}
                    <img class="dropdown-menu-category-section-theme-img" src="http://Goecolo.com/beta/public/assets/frontend/img/banner/banner1.jpg" alt="Image Alternative text" title="Image Title" style="right: -10px;">   
                @endif  --}}
            </div>
        </div>
    </li>
    @endif
@endforeach
<li>