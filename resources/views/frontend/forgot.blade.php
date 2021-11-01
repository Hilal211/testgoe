<!DOCTYPE HTML>
<html>
    @include('frontend.layout.head')

    <body>
        <div class="global-wrapper clearfix" id="global-wrapper">
            @include('frontend.layout.header')
            <div class="container">
                <div class="gap gap-small"></div>
                <div class="row bg-white-rounded">
                    @include('common.message')
                    <div class="col-md-7 col-md-push-3">
                        <h1 class="widget-title text-center">{{trans('keywords.Forgot Password')}}</h1>
                        <div class="gap gap-small gap-border"></div>
                        @include('errors.error')
                        {!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('post.forgot')),"method"=>"POST"]) !!}
                        <div class="form-group">
                            {{ Form::label('email',trans('keywords.Please submit your email here to retrive your password')) }}
                            {{ Form::text('email','',["class"=>'form-control',"placeholder"=>trans('keywords.Email')]) }}
                        </div>
                        {{ Form::button(trans('keywords.Submit'),["type"=>"submit","class"=>"btn btn-primary"]) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="gap gap-small"></div>
            @include('frontend.layout.footer')
        </div>
        @include('frontend.layout.scripts')
    </body>
</html>
