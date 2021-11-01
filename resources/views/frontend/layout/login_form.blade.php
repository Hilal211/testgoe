@include('errors.error')
{!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('post.login')),"method"=>"POST"]) !!}
<div class="form-group">
    @include('common.required_mark') {{ Form::label('email',trans('keywords.Email')) }}
    {{ Form::text('email','',["class"=>'form-control',"placeholder"=>trans('keywords.Email')]) }}
</div>
<div class="form-group">
    @include('common.required_mark') {{ Form::label('password',trans('keywords.Password')) }}
    {{ Form::password('password',["class"=>'form-control',"placeholder"=>trans('keywords.Password')]) }}
</div>
<div class="checkbox">
    <label>
        {{ Form::checkbox('remember_me','1','',["class"=>'i-check']) }} {{trans('keywords.Remember Me')}}
        {{ Form::hidden('pay_store_id','0',['id'=>'pay_store_id'])}}
    </label>
</div>
{{ Form::button(trans('keywords.Sign In'),["type"=>"submit","class"=>"btn btn-primary"]) }}
{!! Form::close() !!}
<div class="gap gap-small"></div>
<ul class="list-unstyled user-links-ul">
        {{--<li><a href="{{url('/buyer-register')}}">Not Member Yet</a>
    </li>--}}
    <li>{{trans("keywords.Don't have account yet? Register here.")}} <a href="{{url('/buyer-register')}}">{{trans('keywords.Register as Shopper')}}</a> | <a href="{{url('/store-owner')}}">{{trans('keywords.Register as Store Owner')}}</a></li>
    <li><a href="{{url('/forgot-password')}}">{{trans('keywords.Forgot Password?')}}</a>
    </li>
</ul>