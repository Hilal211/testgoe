<footer class="main-footer">
    <div class="">
        <div class="containerFooter">
            <div class="containerLinks">
                <div class="linksleft">
                    <h5>Quick Links</h5>
                    <ul class="main-footer-links-list ">
                        <li><a href="{{url('/about')}}">{{trans('keywords.About Us')}}</a></li>
                        <li><a href="{{url('/contact-us')}}">{{trans('keywords.Contact US')}}</a></li>
                        <li><a href="javascript:void(0)" onclick="reloadCartItem();">My Cart</a></li>
                        <li><a href="#nav-subscribe-dialog" data-effect="mfp-move-from-top" class="popup-text">{{trans('keywords.Subscribe')}}</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Customer service</h5>
                    <ul class="main-footer-links-list ">
                        <li><a href="{{url('/how-it-works')}}">{{trans('keywords.How it works')}}</a></li>
                        <li><a href="{{url('/terms')}}">{{trans('keywords.Terms and Conditions')}}</a></li>
                        <li><a href="{{url('/faq')}}">{{trans('keywords.FAQ')}}</a></li>
                        <li><a href="{{url('/privacy')}}">{{trans('keywords.Privacy Policy')}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="ff">
            <div class="formFooter">
                
                    <h5 class="widget-title-sm newsletter-join">{{trans('keywords.Join our mailing list and receive great deals near you!')}}</h5>
                    {!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('post.subscription')),"method"=>"POST","class"=>'',"onsubmit"=>'return Subscribe(this)']) !!}
                    <div class="no-padding form-group col-md-4">
                        <label for="email" class="font-12">{{trans('keywords.Email')}}</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{Auth::check() ? Auth::user()->email : ""}}" {{Auth::check() ? "readonly" : ""}}>
                    </div>
                    {{-- <div class="clearfix"></div> --}}
                    <div class="form-group col-md-3">
                        <label for="pwd" class="font-12"> {{trans('keywords.Postal / Zip Code')}} </label>
                        <input type="text" name="zip" class="form-control" id="pwd">
                    </div>

                    {{-- <div class="form-group">
                    @include('common.required_mark') <label for="email">{{trans('keywords.Email')}}</label>
                    {{ Form::text('email','',["class"=>'form-control',"placeholder"=>trans('keywords.Email')]) }}
                </div>
                <div class="form-group">
                    @include('common.required_mark') <label for="zip">Postal</label>
                    {{ Form::select('zip',$zips,'',['class'=>'form-control select2',"id"=>"zip-select"]) }}
                </div> --}}
                {{-- <div class="clearfix"></div> --}}

                {!! Form::close() !!}
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::button(trans('keywords.Submit'),["type"=>"submit","class"=>"btn btn-primary btn-sm"]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'footer.png',"",['class'=>'main-footer-img']) }} -->
</footer>
<div class="copyright-area">
    <div class="">
        <div class="row">
            <div class="col-md-6 imageFooter">
                {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center-'.App::getLocale().'.png',"",['width'=>'100']) }}
                <span class="copyright-text">{{trans('keywords.Copyright')}} &copy; 2016 - {{Carbon::now()->format('Y')}} <a href="#">Goecolo Inc</a>.</span>
            </div>


            <div class="col-md-6">
                <ul class="main-footer-social-list text-center social-links">
                    <li>
                        <a class="fa fa-facebook" href="https://www.facebook.com/goecolo" target="_blank"></a>
                    </li>
                    <li>
                        <a class="fa fa-twitter" href="https://twitter.com/@Go_ecolo" target="_blank"></a>
                    </li>
                    {{-- <li>
                        <a class="fa fa-instagram" href="#"></a>
                    </li> --}}
                </ul>
            </div>

        </div>
    </div>
</div>