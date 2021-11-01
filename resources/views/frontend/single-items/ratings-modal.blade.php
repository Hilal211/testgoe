<div id="StoreRatingsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        {{ Html::image($StoreDetails->image,'') }}
        <p class="no-margin">{{$StoreDetails->storename}}</p>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 form-group">
            <b>
{{trans('keywords.Address')}}</b>
              {!! $address !!}
          </div>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col-md-2">
{{trans('keywords.Customer')}}</th>
                    <th class="col-md-4">
{{trans('keywords.Rating')}}</th>
                    <th class="col-md-6">
{{trans('keywords.Comments')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ratings as $rate)
                    <tr>
                      <td>{{$rate->firstname}}</td>
                      <td>
                        <div class="col-md-6 no-padding">
                          <input class="rating-input" value="{{Functions::GetRate($rate->rating)}}" type="number">
                        </div>
                        <div class="col-md-6">
                          <b>[{{Functions::GetRate($rate->rating)}}]</b>
                        </div>
                      </td>
                      <td class="limited-td">{{$rate->comments}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer delete-actions">
        <button type="button" class="btn btn-default" data-dismiss="modal">
{{trans('keywords.Close')}}</button>
      </div>
    </div>
  </div>
</div>