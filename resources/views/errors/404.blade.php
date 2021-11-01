@extends('frontend.layout.default')

@section('content')
<div class="container">
    <div class="gap gap-small"></div>
    <div class="row bg-orange-rounded">
        <div class="registration-bg form-group">
            <div class="col-md-8 col-md-push-2">
            <h1 class="widget-title text-center">{{ trans('keywords.Page Not Found') }}</h1>
            </div>
        </div>
        <div>
            <div class="col-md-8 col-md-push-2 text-center">
                <h1 class="widget-title title-404"><p>404 <i class="fa fa-search"></i></p></h1>
                <h2 class="widget-title text-light"><p>{{trans('keywords.This page does not exist')}}</p></h2>
                <a href="{{route('frontend.home')}}" class="btn btn-primary nextBtn text-center"><i class="fa fa-arrow-circle-left"></i> {{trans('keywords.Home')}}</a>
                <div class="gap gap-small gap-border"></div>
            </div>
        </div>
    </div>
</div>
@stop