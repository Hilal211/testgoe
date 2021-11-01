<div id="zipModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center-'.App::getLocale().'.png','',['width'=>'150px;']) }}
      </div>
      <div class="modal-body">
      	<div class="zip-section">
      		<div class="form-group">
      			<span class="help-block label-zip"></span>
      			{!! Form::text('zip','',['id'=>'zip','class'=>'form-control']) !!}
      		</div>
      	</div>
      </div>
      <div class="modal-footer delete-actions">
      	<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('keywords.Close')}}</button>
        <button type="button" onclick="saveNewZip()" class="btn btn-primary">{{trans('keywords.Save')}}</button>
      </div>
    </div>
  </div>
</div>