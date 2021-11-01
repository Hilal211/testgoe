<li id="product_{{$product->id}}" class="list-group-item">
    <div class="row">
        <div class="col-md-8">
            <span class="title-container">{{$product->product_name}}</span>
        </div>
        <div class="col-md-1 btn-group pull-right text-right">
            <a class="btn btn-xs" href="javascript:;" onclick="SetEdit(this)" data-id="{{$product->id}}" data-parent-id="{{$product->subcat_id}}"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-xs" href="javascript:;" onclick="GetDelete(this)" data-id="{{$product->id}}"><i class="fa fa-remove"></i></a>
        </div>
        <div class="col-md-1 pull-right">
            <label class="label {{ ($product->status=="1")? 'label-success' : 'label-danger' }}">
                {{ ($product->status=="1") ? 'Active' : 'Inactive' }}
            </label>
        </div>

    </div>
</li>