@if(Auth::check() && Auth::user()->hasrole('admin'))
<div class="panel-group categories-group sub-categories-group" id="accordion">
<div class="panel">
  <div class="panel-heading text-center">
    <input type="text" name="search" class="form-control" id="search_input" placeholder="Search Product" onkeyup="searchProduct(event, this,'{{$cat_id}}')" value="{{$keyword}}">
  </div>
</div>
  @foreach($cats as $cat)
  @if(count($cat->sub_cats)>0)
  <div id="{{str_slug($cat->category_name,'_')}}" class="panel">
    <div class="panel-body no-padding">
      @foreach($cat->sub_cats as $subcat)
      <div id="sub_category_{{$subcat->id}}" class="">
        <div class="panel-heading">
          <h4 class="panel-title text-sub-cat">
            <span class="title-container">{{$subcat->category_name}}</span> <span class="fr-title-container display-none">{{$subcat->fr_category_name}}</span> ({{count($subcat->products)}})
            <div class="panel-actions pull-right">
              <a class="action-btn" href="javascript:;" onclick="SetAddNew(this)" data-subcat="{{$subcat->id}}" data-parent-id="{{$cat->id}}"><i class="fa fa-plus"></i></a>
              <a class="action-btn" href="javascript:;" onclick="SetEdit(this)" data-id="{{$subcat->id}}" data-parent-id="{{$cat->id}}"><i class="fa fa-pencil"></i></a>
              <a class="action-btn" href="javascript:;" onclick="GetDelete(this,'sub')" data-id="{{$subcat->id}}"><i class="fa fa-remove"></i></a>
            </div>
          </h4>
        </div>
        <div class="panel-body no-padding">
          <ul class="list-group action-list">
            @foreach($subcat->products as $product)
              <li id="product_{{$product->id}}" class="list-group-item">
                <div class="row">
                  <div class="col-md-8">
                    {{ Html::image(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).Functions::GetImageName($product->image,'-16x16'),"",['class'=>'user-image']) }} 
                    <span class="text">{{$product->product_name}}</span>
                  </div>
                  <div class="col-md-2">
                    <label class="label {{ ($product->status=="1")? 'label-success' : 'label-danger' }}">
                      {{ ($product->status=="1") ? 'Active' : 'Inactive' }}
                    </label>
                  </div>
                  <div class="col-md-2">
                      <div class="tools">
                        <a class="btn-grey tool-btn" href="javascript:;" onclick="SetProductEdit(this)" data-id="{{$product->id}}" data-parent-id="{{$product->subcat_id}}"><i class="fa fa-pencil"></i></a>
                        <a class="btn-grey tool-btn" href="javascript:;" onclick="GetProductDelete(this)" data-id="{{$product->id}}"><i class="fa fa-remove"></i></a>
                      </div>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @else
  <div id="{{str_slug($cat->category_name,'_')}}" class="panel">
    <div class="panel-heading">
      <h4 class="panel-title text-center">No Records Found</h4>
    </div>
  </div>
  @endif
  @endforeach
</div>
@else
<div class="panel-group categories-group sub-categories-group" id="accordion">
  <div class="panel">
    <div class="panel-heading text-center">
      <input type="text" name="search" class="form-control" id="search_input" placeholder="Search Product" onkeyup="searchProduct(event, this,'{{$cat_id}}')" value="{{$keyword}}">
    </div>
  </div>
  @if(count($cats)>0)
  @foreach($cats as $key=>$cat)
  <div class="panel">
    <div class="panel-body no-padding">
      @foreach($cat as $subKey=>$subVal)
      <div class="">
        <div class="panel-heading">
          <h4 class="panel-title text-sub-cat">
            <span class="title-container">{{$subKey}}</span> ({{count($subVal)}})
          </h4>
        </div>
        <div class="panel-body no-padding">
          <ul class="list-group action-list">
            <li class="list-group-item">
              <div class="row">
                <div class="col-md-4"><b>Product Title</b></div>
                <div class="col-md-2"><b>Qty</b></div>
                <div class="col-md-3"><b>Price / Unit</b></div>
                <div class="col-md-2"><b>Status</b></div>
                <div class="col-md-1"></div>
              </div>
            </li>
            @foreach($subVal as $product)
            <li id="product_{{$product->id}}" class="list-group-item">
              <div class="row">
                <div class="col-md-4">
                  {{ Html::image(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).Functions::GetImageName($product->image,'-16x16'),"",['class'=>'user-image']) }}
                  <span class="title-container">{{$product->product_name}}</span>
                </div>
                <div  class="col-md-2">
                  {{$product->inventory}}
                </div>
                <div class="col-md-3">
                  {{ Functions::GetPrice($product->price) }} / {{$product->short_unit}}
                </div>
                <div class="col-md-2">
                  <label class="label pull-right {{ ($product->status=="1")? 'label-success' : 'label-danger' }}">
                    {{ ($product->status=="1") ? 'Active' : 'Inactive' }}
                  </label>
                </div>
                <div class="col-md-1 tools">
                  @if(@$store_bank)
                    @if($store_bank->status=='1')
                      <a class="btn-grey" href="javascript:;" onclick="SetEdit(this)" data-id="{{$product->id}}" data-store="{{($product->store_id) ? $product->store_id : $product->p}}"><i class="fa fa-pencil"></i></a>
                    @endif
                  @endif
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endforeach
  @else
  <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title text-center">No Products Found</h4>
    </div>
  </div>
  @endif
</div>
@endif