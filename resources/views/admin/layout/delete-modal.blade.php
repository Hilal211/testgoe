<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="title_holder">{{$title}}</h4>
            </div>
            <div class="modal-body">
                <p  id="message_holder">{{$message}}</p>
            </div>
            <div class="modal-footer delete-actions">
                <input type="hidden" id="delete-modal-id" value="{{$id}}">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('keywords.No')}}</button>
                <button type="button" id="btn_holder" class="btn btn-danger" onclick="{{$click_function}}">{{trans('keywords.Yes, Sure')}}</button>
            </div>
        </div>
    </div>
</div>