@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('keywords.FAQ')}}</h1>
				<p class="description text-center">{{trans('keywords.Frequently Asked Questions')}}</p>
			</div>
		</div>
		<div>
			<div>
				<div class="gap gap-small gap-border"></div>
					<div class="col-md-12">
						<div class="bs-example">
							<div class="panel-group" id="accordion">
								@for($i=0;$i<count($title);$i++)
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#item{{$i}}">{{$i}}. {{$title[$i]}}</a>
											</h4>
										</div>
										<div id="item{{$i}}" class="panel-collapse collapse {{($i==0 ? "in" : "")}}">
											<div class="panel-body">
												<span>{!! $content[$i] !!}</span>
											</div>
										</div>
									</div>
								@endfor
							</div>
						</div>
					</div>
					<div class="gap gap-small"></div>
			</div>
		</div>
	</div>
</div>
@stop