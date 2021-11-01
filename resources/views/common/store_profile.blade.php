<section class="content">
	<div class="row">
		<div class="col-md-12">
			@include('errors.error')
			@if(Session::get('success')!='')
			<div class="alert alert-success alert-success-message">
				{{ Session::get('success') }}
			</div>
			@endif
			@if($store->is_virtual == 0)
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Basic Details</h3>
					</div>
					<div class="box-body">
						{!! Form::open(["url"=>route("save.store.profile",['step-1',Route::current()->getParameter('id')]),"method"=>"POST"]) !!}
						<div class="form-group">
							@include('common.required_mark') {!! Form::label('username','Store Contact Person Name',['class'=>'control-label']) !!}
							{!! Form::text('username',$user->username,['class'=>'form-control','placeholder'=>'Enter Name']) !!}
						</div>
						<div class="form-group">
							@include('common.required_mark') {!! Form::label('email','Email',['class'=>'control-label']) !!}
							{!! Form::text('email',$user->email,['class'=>'form-control','placeholder'=>'Enter Email']) !!}
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('password','Password',['class'=>'control-label']) !!}
									{!! Form::password('password',['class'=>'form-control 123','placeholder'=>'Enter Password']) !!}
									<span class="help-block">Leave password fields blank if you don't want to change password</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('password_confirmation','Confirm Password',['class'=>'control-label']) !!}
									{!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Retype Password']) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							@include('common.required_mark') {!! Form::label('contactnumber','Contact #',['class'=>'control-label']) !!}
							{!! Form::text('contactnumber',$store->contactnumber,['class'=>'form-control','placeholder'=>'Enter Contact #',"data-inputmask"=>'"mask": "(999) 999-9999"',"data-mask"=>""]) !!}
						</div>
						@if($type=='admin')
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									@include('common.required_mark') {!! Form::label('commision','Commision',['class'=>'control-label']) !!}
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-percent"></i></span>
										{!! Form::text('commision',$store->commision,['class'=>'form-control','placeholder'=>'Enter Commsion %']) !!}
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									@include('common.required_mark') {!! Form::label('commision_on','Commision on',['class'=>'control-label']) !!}
									{{ Form::select('commision_on',$com_type,$store->commision_on,['class'=>'form-control select2']) }}
								</div>
							</div>
						</div>
						@endif
						{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary nextBtn pull-right"]) }}
						{{ Form::hidden('type', $type) }}

						{!! Form::close() !!}
					</div>
				</div>
			@endif
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Store Details </h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-8">
							{!! Form::open(["url"=>route("save.store.profile",['step-2',Route::current()->getParameter('id')]),"method"=>"POST","enctype"=>'multipart/form-data']) !!}
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('storename','Store Name',['class'=>'control-label']) !!}
								{!! Form::text('storename',$store->storename,['class'=>'form-control','placeholder'=>'Enter Store Name']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('homedelievery','Do you offer the Home Delivery service?',['class'=>'control-label']) !!}
								<div>
									<label class="">
										{!! Form::radio('homedelievery','1',$store->homedelievery=='1' ? true : false,['class'=>'i-radio']) !!} Yes
									</label>
									<label class="radio-inline">
										{!! Form::radio('homedelievery','0',$store->homedelievery=='0' ? true : false,['class'=>'i-radio']) !!} No
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('add1','Enter Address 1',['class'=>'control-label']) !!}
										{!! Form::text('add1',$store->add1,['class'=>'form-control','placeholder'=>'Street Address']) !!}
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										{!! Form::label('add2','Enter Address 2',['class'=>'control-label']) !!}
										{!! Form::text('add2',$store->add2,['class'=>'form-control','placeholder'=>'Apartment, Suite, Unit etc... (optional)']) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										{!! Form::label('state','Province / State',['class'=>'control-label']) !!}
										{{ Form::select('state',$states,$store->state,['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										{!! Form::label('city','City',['class'=>'control-label']) !!}
										{{ Form::select('city',$cities,$store->city,['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('zip','Postal / Zip Code',['class'=>'control-label']) !!}
										{{ Form::text('zip',$store->zip,['class'=>'form-control'])}}
									</div>
								</div>
							</div>
							<div class="form-group">
								{!! Form::label('storetype','What type of store you have?',['class'=>'control-label']) !!}
								{{ Form::select('storetype',$types,$store->storetype,['class'=>'form-control select2']) }}
							</div>
							<div class="form-group">
								{{ Form::label('image','Profile Image',['class'=>'control-label']) }}
								<div class="col-md-12 input-group">
									{{ Form::file('profile_image', ['data-multiple'=>false,'class'=>'file-input','data-image'=>@$store->image]) }}
								</div>
								<span class="help-block">Please upload image with aspect ratio of 9:8 or preferably (45 px X 40 px) size.</span>
							</div>
							{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary nextBtn  pull-right"]) }}
							{{ Form::hidden('type', $type) }}
							{!! Form::close() !!}
						</div>
						<div class="col-md-4">
							<div id="map"></div>
						</div>
					</div>
				</div>
			</div>
			@if($store->is_virtual == 0)
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Legal Details</h3>
				</div>
				<div class="box-body">
					{!! Form::open(["url"=>route("save.store.profile",['step-3',Route::current()->getParameter('id')]),"method"=>"POST"]) !!}

					<div class="form-group">
						@include('common.required_mark') {!! Form::label('legalentityname','Legal Entity Name',['class'=>'control-label']) !!}
						{!! Form::text('legalentityname',$store->legalentityname,['class'=>'form-control','placeholder'=>'Enter Legal Entity Name']) !!}
					</div>
					<div class="form-group">
						@include('common.required_mark') {!! Form::label('yearestablished','Year of Establishment',['class'=>'control-label']) !!}
						{!! Form::text('yearestablished',$store->yearestablished,['class'=>'form-control','placeholder'=>'Enter Year of Establishment']) !!}
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('gstnumber','GST #',['class'=>'control-label']) !!}
								{!! Form::text('gstnumber',$store->gstnumber,['class'=>'form-control','placeholder'=>'Enter GST number']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('hstnumber','HST #',['class'=>'control-label']) !!}
								{!! Form::text('hstnumber',$store->hstnumber,['class'=>'form-control','placeholder'=>'Enter HST number']) !!}
							</div>
						</div>
					</div>
					{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
					{{ Form::hidden('type', $type) }}
					{!! Form::close() !!}
				</div>
			</div>
			@elseif($store->is_virtual == 1)
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Legal Details</h3>
				</div>
				<div class="box-body">
					{!! Form::open(["url"=>route("save.store.profile",['step-3',Route::current()->getParameter('id')]),"method"=>"POST"]) !!}
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('legal_entity_name',trans('keywords.Legal Entity Name'),['class'=>'control-label']) !!}
								{!! Form::text('legalentityname',$store->legalentityname,['class'=>'form-control','placeholder'=>trans('keywords.Enter Legal Entity Name')]) !!}
							</div>
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('year',trans('keywords.Year of Establishment'),['class'=>'control-label']) !!}
								{!! Form::text('yearestablished',$store->yearestablished,['class'=>'form-control','placeholder'=>trans('keywords.Enter Year of Establishment')]) !!}
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('legal_address_1',trans('keywords.Address 1'),['class'=>'control-label']) !!}
										{!! Form::text('legal_add1',$store->legal_add1,['class'=>'form-control','placeholder'=>trans('keywords.Enter Address1')]) !!}
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										{!! Form::label('legal_address_2',trans('keywords.Address 2'),['class'=>'control-label']) !!}
										{!! Form::text('legal_add2',$store->legal_add2,['class'=>'form-control','placeholder'=>trans('keywords.Enter Address2')]) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('legal_state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
										{{ Form::select('legal_state',$states,$store->legal_state,['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('legal_city',trans('keywords.City'),['class'=>'control-label']) !!}
										{{ Form::select('legal_city',$cities,$store->legal_city,['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('legal_zip',trans('keywords.Postal / Zip Code'),['class'=>'control-label']) !!}
										{{ Form::text('legal_zip',$store->legal_zip,['class'=>'form-control'])}}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('gst',trans('keywords.GST #'),['class'=>'control-label']) !!}
										{!! Form::text('gstnumber',$store->gstnumber,['class'=>'form-control','placeholder'=>trans('keywords.Enter GST number')]) !!}
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										@include('common.required_mark') {!! Form::label('hst',trans('keywords.HST #'),['class'=>'control-label']) !!}
										{!! Form::text('hstnumber',$store->hstnumber,['class'=>'form-control','placeholder'=>trans('keywords.Enter HST number')]) !!}
									</div>
								</div>
							</div>
					{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
					{{ Form::hidden('type', $type) }}
					{!! Form::close() !!}
				</div>
			</div>
			@endif
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Account Details</h3>
				</div>
				<div class="box-body">
					{!! Form::open(["url"=>route("save.store.profile",['account',Route::current()->getParameter('id')]),"method"=>"POST",'id'=>'account_details_form',"enctype"=>'multipart/form-data']) !!}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							@include('common.required_mark') {{ Form::label('firstname','Firstname') }}
                                {{ Form::text('firstname',@$user->firstname,['class'=>'form-control']) }}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							@include('common.required_mark') {{ Form::label('lastname','Lastname') }}
                                {{ Form::text('lastname',@$user->lastname,['class'=>'form-control']) }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							@include('common.required_mark') {{ Form::label('dob','Date Of Birth') }}
                                {{ Form::text('dob',(@$bank_details->dob ? @$bank_details->dob->format('d M Y') : ""),['class'=>'form-control date-picker']) }}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {{ Form::label('document','Verification document',['class'=>'control-label']) }}
								<div class="col-md-12 input-group">
									{{ Form::file('document',['data-multiple'=>false,'class'=>'file-input','data-image'=>@$bank_details->document]) }}
									<span class="help-block">Any goverment approved photo ID, E.g. Driver's License / Passport etc..</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('account_holder','Account Holder Name',['class'=>'control-label']) !!}
								{!! Form::text('account_holder_name',@$bank_details->account_holder_name,['class'=>'form-control','placeholder'=>'Enter Account Holder Name']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('bank_name','Bank Name',['class'=>'control-label']) !!}
								{!! Form::text('bank_name',@$bank_details->bank_name,['class'=>'form-control','placeholder'=>'Enter Bank Name']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('account_number','Account Number',['class'=>'control-label']) !!}
								{!! Form::text('account_number',@$bank_details->account_number,['class'=>'form-control','placeholder'=>'Enter Account Number']) !!}
								{{-- {!! Form::text('account_number',str_pad(substr(@$bank_details->account_number, -4), strlen(@$bank_details->account_number), '*', STR_PAD_LEFT),['class'=>'form-control','placeholder'=>'Enter Account Number']) !!} --}}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								@include('common.required_mark') {!! Form::label('routing_number','Transit Number',['class'=>'control-label']) !!}
								{!! Form::text('routing_number',@$bank_details->routing_number,['class'=>'form-control','placeholder'=>'Enter Routing Number']) !!}
								<span class="help-block">You can find it on your check. Please enter in the format "BBBBB-AAA" where AAA is three digits code identifying the institution and BBBBB is five digits code identifying the branch.</span>
								{{ Form::hidden('stripe_account_id',@$bank_details->stripe_account_id) }}
								{{ Form::hidden('id',@$bank_details->id) }}
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-md-12">
							{{ Form::checkbox('agreement','1',(@$bank_details->tos_acceptance_ip!='' ? true : false),["class"=>'i-check']) }} By registering your account, you agree to our <a href="">Services Agreement</a> and the <a href="https://stripe.com/connect-account/legal" target="_blank">Stripe Connected Account Agreement</a>.
						</label>
					</div>
					{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
					{{ Form::hidden('type', $type) }}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>