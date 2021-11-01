@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Taxes</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-8">
        {!! Form::open(["url"=>route("admin.save.tax"),"method"=>"POST"]) !!}
        <div class="box box-solid box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Tax</h3>
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
                    <th class="col-md-4">State</th>
                    <th class="col-md-4">Tax Type</th>
                    <th class="col-md-2">Federal Tax</th>
                    <th class="col-md-2">Province Tax</th>
                    <th>Total %</th>
                  </tr>
                </thead>
                <tbody>
                @for($i=0;$i<count($states);$i++)
                  <tr>
                    <td>{{$states[$i]->item_name}}</td>
                    <td>
                      {{ Form::text('description_'.$states[$i]->id,$states[$i]->description,["class"=>'form-control']) }}
                    </td>
                    <td>
                      {{ Form::text('ftax_'.$states[$i]->id,$states[$i]->ftax,["class"=>'form-control tax_field']) }}
                    </td>
                    <td>
                      {{ Form::text('ptax_'.$states[$i]->id,$states[$i]->ptax,["class"=>'form-control tax_field']) }}
                    </td>
                    <td class="v-middle text-center bg-gray">{{$states[$i]->total}}</td>
                  </tr>
                  @endfor

                </tbody>
              </table>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </section>
</div>
@stop
@section('custom_js')
<script>
$(document).ready(function(){
    $('.alert-danger').find('.font-16').removeClass('font-16');
    $(".tax_field").keyup(function() {
      var tr = $(this).parents('tr');
      var FTax_td = $(tr).find('td:eq(2)');
      var PTax_td = $(tr).find('td:eq(3)');
      var Total_td = $(tr).find('td:eq(4)');
      var Ftax = $(FTax_td).find('input').val();
      var Ptax = $(PTax_td).find('input').val();
      var Total = parseFloat(Ftax)+parseFloat(Ptax);
      Total_td.html(Total);
    });
})
</script>
@stop