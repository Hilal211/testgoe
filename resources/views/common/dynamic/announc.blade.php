<div id="main_category_{{$data->id}}" class="panel">
	<div class="panel-heading">
			<h4 class="panel-title">
				<!-- {{ Html::image(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).Functions::GetImageName($data->icon,'-16x16'),"",['class'=>'user-image']) }} -->
				<span class="category-title">{{$data->description}}</span> <span>(<span class="label-counter">{{$data->status}}</span>)</span>
				@if(Auth::check() && Auth::user()->hasrole('admin'))
					<div class="panel-actions pull-right">
						<span class="action-btn category-edit" onclick="GetCategoryEdit(this)" data-id="{{$data->id}}"><span><i class="fa fa-pencil"></i></span>
					</div>
				@endif
			</h4>
	</div>
</div>