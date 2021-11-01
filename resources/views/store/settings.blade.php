@extends('store.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Settings</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        @if($settings!='')
        <div class="box box-info">
        {!! Form::open(["url"=>route("save.store.settings",[Route::current()->getParameter('storeid')]),"method"=>"POST"]) !!}
         <div class="box-header">
          <h3 class="box-title">Shipping Charge Settings</h3>
          <div class="box-tools pull-right">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
        <div class="box-body">
          @include('errors.error')
          <div class="table-responsive">
            <table class="table no-margin table-small-padding">
              <thead>
                <tr>
                  <th>Amount</th>
                  <th>Charge</th>
                </tr>
              </thead>
              <tbody>
                @for($i=0;$i<count($settings);$i++)
                <tr>
                  <td>{{$settingsobj->RangeTitle[$i]['title']}}</td>
                  <td>
                    {{ Form::text('charge_amount_'.$i,$settings[$i]->charge_amount,["class"=>'form-control']) }}
                  </td>
                </tr>
                @endfor

              </tbody>
            </table>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
      @endif
    </div>
  </div>
</section>
</div>
@stop