<!DOCTYPE HTML>
<html>
    @include('frontend.layout.head')

    <body>
        <div class="global-wrapper clearfix" id="global-wrapper">            
            @include('frontend.layout.header')
            <div class="container">
                <div class="gap gap-small"></div>
                <div class="row bg-white-rounded">
                    <div class="col-md-6 col-md-push-3">
                        <h1 class="widget-title text-center">{{trans('keywords.Login')}}</h1>
                        <div class="gap gap-small gap-border"></div>
                        @include('common.message')
                        @include('frontend.layout.login_form')
                    </div>
                </div>
            </div>
            <div class="gap gap-small"></div>
            @include('frontend.layout.footer')
        </div>
        @include('frontend.layout.scripts')
    </body>
</html>
