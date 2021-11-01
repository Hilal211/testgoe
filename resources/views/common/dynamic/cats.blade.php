<div id="main_category_{{$cat->id}}" class="panel">
	<div class="panel-heading">
		<a class="action-btn" href="javascript:;" data-json="{{json_encode(['id'=>$cat->id])}}" onclick="GetDetais(this)">
			<h4 class="panel-title">
				{{ Html::image(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).Functions::GetImageName($cat->icon,'-16x16'),"",['class'=>'user-image']) }}
				<span class="category-title">{{$cat->category_name}}</span> <span>(<span class="label-counter">{{count($cat->sub_cats)}}</span>)</span>
				@if(Auth::check() && Auth::user()->hasrole('admin'))
					<div class="panel-actions pull-right">
						<span class="action-btn category-add" onclick="SetCategory(this)" data-id="{{$cat->id}}" href="#SubCategoryModal" data-toggle="modal"><i class="fa fa-plus"></i></span>
						<span class="action-btn category-edit" onclick="GetCategoryEdit(this)" data-id="{{$cat->id}}"><i class="fa fa-pencil"></i></span>
						<span class="action-btn category-delete" onclick="GetDelete(this,'main')" data-id="{{$cat->id}}"><i class="fa fa-remove"></i></span>
						<span class="action-btn handle">
							<i class="fa fa-ellipsis-v"></i>
							<i class="fa fa-ellipsis-v"></i>
						</span>
					</div>
				@endif
			</h4>
		</a>
	</div>
</div>