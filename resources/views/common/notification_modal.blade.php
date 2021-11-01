<div id="get_notification_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {!! Form::open(["id"=>"frmCityNotification","url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('post.email.notification')),"method"=>"POST",'onsubmit'=>'return SaveNotificationForm(this)']) !!}
      <div class="modal-header">{{trans('keywords.Coming to your City soon')}}</div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
      	<div class="row">
          <div class="col-md-12">
            <div class="form-group">
              @include('common.required_mark') {!! Form::label('city',trans('keywords.City'),['class'=>'control-label']) !!}
              {{ Form::select('city',($type=='customer' ? $Allcities : $cities),'',['class'=>'form-control select2']) }}    
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              @include('common.required_mark') {!! Form::label('email',trans('keywords.Email'),['class'=>'control-label']) !!}
              {!! Form::text('email','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Email')]) !!}
              {{ Form::hidden('user_type',($type=='customer' ? "1" : "0")) }}
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer delete-actions">
      	<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('keywords.Close')}}</button>
        <button type="submit" class="btn btn-primary">{{trans('keywords.Save')}}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>