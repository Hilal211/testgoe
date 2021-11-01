<div class="row">
    <div class="col-md-5">
        {{ Html::image($image,"",['style'=>'object-fit: scale-down;','width'=>'200','height'=>'auto']) }}
    </div>
    <div class="col-md-6 height-4">
        {{ $cartitem->description }}
    </div>
    <div class="col-md-6 height-4">
        {{ $cartitem->min_price }}
    </div>
</div>