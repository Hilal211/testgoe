@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Products <a href="#help" data-toggle="modal"><i class="fa fa-info-circle"></i></a></h1>
  </section>
  <section class="content">
    <div class="row">
      <div id="CatsHolder" class="col-md-6">
        <div class="panel-group categories-group" id="accordion">
          <div class="panel">
            <div class="panel-heading text-center">
              <h4 class="panel-title"><a class="btn-block action-btn" href="#CategoryModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Category</a></h4>
            </div>
          </div>
          @foreach($cats as $cat)
          @include('common.dynamic.cats',['cats'=>$cats]) 
          @endforeach
        </div>
      </div>
      <div id="SubCatsHolder" class="col-md-6">

      </div>
    </div>
  </section>
</div>
<div id="SubCategoryModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"/admin/save/subcategories","method"=>"POST","class"=>"",'onsubmit'=>'return SaveCategory(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sub Category</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('category_name','Sub Category',['class'=>'control-label']) }}
              {{ Form::text('category_name','',["class"=>'form-control','placeholder'=>'Category name',"spellcheck"=>"true"]) }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
            @include('common.required_mark') {{ Form::label('fr_category_name','French Sub Category',['class'=>'control-label']) }}
              {{ Form::text('fr_category_name','',["class"=>'form-control','placeholder'=>'French Category name',"spellcheck"=>"true"]) }}
            </div>
          </div>
        </div>
        {{ Form::hidden('id','0',['id'=>'category_id']) }}
        {{ Form::hidden('parent_id','0',['id'=>'parent_id']) }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
<div id="CategoryModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"/admin/save/categories","method"=>"POST","class"=>"",'onsubmit'=>'return SaveMainCategory(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Categories</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('category_name','Category',['class'=>'control-label']) }}
              {{ Form::text('category_name','',["class"=>'form-control','placeholder'=>'Category name',"spellcheck"=>"true"]) }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('category_name','French Category',['class'=>'control-label']) }}
              {{ Form::text('fr_category_name','',["class"=>'form-control','placeholder'=>'French Category name',"spellcheck"=>"true"]) }}
            </div>
          </div>
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('icon','Icon',['class'=>'control-label']) }}
          {{ Form::file('icon', ['data-multiple'=>false,'class' => 'file-input','id'=>'icon-image']) }}
        </div>
          {{-- {{ Form::text('icon','',["class"=>'form-control icon-picker']) }}
            <span class="input-group-addon"></span> --}}
        <div class="form-group">
          {{ Form::label('image','BG Image',['class'=>'control-label']) }}
            {{ Form::file('bg_image', ['data-multiple'=>false,'class' => 'file-input','id'=>'background-image']) }}
        </div>
        {{ Form::hidden('id','0',['id'=>'category_id']) }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
<div id="ProductModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"admin/product","method"=>"POST","class"=>"",'onsubmit'=>'return SaveProduct(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="product-modal-title" class="modal-title">Add Product</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('product_name','Name',['class'=>'control-label']) }}
              {{ Form::text('product_name','',["class"=>'form-control','placeholder'=>'Product name',"spellcheck"=>"true"]) }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
            @include('common.required_mark') {{ Form::label('fr_product_name','French Name',['class'=>'control-label']) }}
              {{ Form::text('fr_product_name','',["class"=>'form-control','placeholder'=>'French Product name',"spellcheck"=>"true"]) }}
            </div>
          </div>
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('cat_id','Category',['class'=>'control-label']) }}
          {{ Form::select('cat_id',$catsoptions,'',['class'=>'select2 form-control','data-placeholder'=>"select category",'onchange'=>'getSubCategories(this)']) }}
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('subcat_id','Sub Category:',['class'=>'control-label']) }}
          {{ Form::select('subcat_id',[],'', ['class'=>'select2 form-control','data-placeholder'=>"select category first"]) }}
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('unit','Unit',['class'=>'control-label']) }}
          {{ Form::select('unit',$units,'', ['class'=>'select2 form-control','data-placeholder'=>"Select"]) }}
        </div>
        {{ Form::hidden('id','0',['id'=>'product_id']) }}
        <div class="row form-group">
          <div class="col-md-6">
              @include('common.required_mark') {{ Form::label('recycle_fee','Recycling Fee',['class'=>'control-label']) }}
              <label class="col-md-12 no-padding">
                {{ Form::text('recycle_fee','',["class"=>'form-control','placeholder'=>'Recycling Fee']) }}
              </label>
          </div>
          <div class="col-md-3 no-padding">
            {{ Form::label('status','',['class'=>'control-label col-md-12']) }}
            <label class="col-md-12">
              {{ Form::checkbox('status','1','',["class"=>'i-check']) }} &nbsp; Active
            </label>
          </div>
          <div class="col-md-3 no-padding">
            {{ Form::label('is_taxable','',['class'=>'control-label col-md-12']) }}
            <label class="col-md-12">
              {{ Form::checkbox('is_taxable','1','',["class"=>'i-check']) }} &nbsp; Yes
            </label>
          </div>
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('image','Image',['class'=>'control-label']) }}
          {{ Form::file('image', ['data-multiple'=>false,'class' => 'file-input','id'=>'product-image']) }}
        </div>
        <div class="form-group">
          {{ Form::label('description','Description',['class'=>'control-label']) }}
          {{ Form::textarea('description','',["class"=>'form-control','placeholder'=>'Description / Note','rows'=>'3',"spellcheck"=>"true"]) }}
        </div>
        <div class="form-group">
          {{ Form::label('fr_description','French Description',['class'=>'control-label']) }}
          {{ Form::textarea('fr_description','',["class"=>'form-control','placeholder'=>'French Description / Note','rows'=>'3',"spellcheck"=>"true"]) }}
        </div>
        <div class="form-group">
          {{ Form::label('products','Related Products',['class'=>'control-label']) }}
          {{ Form::select('related_products[]',$products,'', ['id'=>'related_products','class'=>'product-select2 form-control','data-placeholder'=>"Select",'multiple'=>true]) }}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<div id="help" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Help</h4>
      </div>
      <div class="modal-body">
        <div class="panel-group" id="accordion">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#category-help">Add Category</a>
              </h4>
            </div>
            <div id="category-help" class="panel-collapse collapse in">
              <div class="panel-body">
                <ul>
                  <li>Go to Products section.</li>
                  <li>Click on Products in left and click Add Category buton on top.</li>
                  <li>Add details and click Save button.</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#sub-cat-help">Add Subcategory</a>
              </h4>
            </div>
            <div id="sub-cat-help" class="panel-collapse collapse in">
              <div class="panel-body">
                <ul>
                  <li>Click on + icon under Category.</li>
                  <li>Enter subcategory name and click Save.</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#product-help">Add Product</a>
              </h4>
            </div>
            <div id="product-help" class="panel-collapse collapse in">
              <div class="panel-body">
                <ul>
                  <li>Click + icon on any SubCategoryModal.</li>
                  <li>Add details and click Save.</li>
                </ul>
              </div>
            </div>
          </div>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('custom_js')
<script>
  $(document).ready(function(){
    $(".product-select2").select2({
        placeholder: SelectPlaceHolder,
        allowClear: true,
        maximumSelectionLength: 10,
        minimumInputLength:3
    });
    $(".categories-group").sortable({
      axis: 'y',
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999,
      update: function (event, ui) {
        var data = $(this).sortable('serialize');
        //console.log(JSON.stringify(data));
        $.ajax({
          data: data,
          type: 'POST',
          url: APP_URL+'/admin/update/category-order'
        });
      }
    });
    $('#SubCategoryModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="parent_id"]').val('0');
      $(Form).find('input[name="category_name"]').val('');
      $(Form).find('input[name="fr_category_name"]').val('');
      $(Form).find('.form-errors').hide();
    })
    $('#CategoryModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="category_name"]').val('');
      $(Form).find('input[name="fr_category_name"]').val('');
      $(Form).find('input[name="icon"]').val('');
      $(Form).find('.form-errors').hide();
      $("#background-image").fileinput('destroy');
      $("#background-image").fileinput({
        showUpload: false,
        showRemove: false,
      });

      $("#icon-image").fileinput('destroy');
      $("#icon-image").fileinput({
        showUpload: false,
        showRemove: false,
      });
    })
    $('.icon-picker').iconpicker({
      hideOnSelect: true,
    });
    //$('.icon-picker').fontIconPicker();
  });
  function SetCategory(element){
    var Id = $(element).attr('data-id');
    $('#parent_id').val(Id);
  }
  function GetDetais(element){
    var JSON = $(element).data('json'),
    Id = JSON.id,
    parent = $(element).parents('.panel-heading');
    $.ajax({
      type: "POST",
      url: APP_URL+'/admin/get-subcats-view/'+Id,
      data: "",
      dataType: "json",
      success: function(res) {
        $('#accordion').find('.panel-heading').removeClass('bg-active');
        $(parent).addClass('bg-active');
        $('#SubCatsHolder').html(res.SubCategoryHtml);
        setTimeout(function(){
          $('#search_input').focus();
        }, 500)
      }
    });
  }
  function searchProduct(e,element, catId){
    var regex = new RegExp("^[a-zA-Z0-9\b\s]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    var keywordVal = element.value;
    var length = keywordVal.length;

    if (regex.test(str)) {
        if(length == 0){
          $.ajax({
            type: "POST",
            url: APP_URL+'/admin/get-subcats-view/'+catId,
            data: {},
            dataType: "json",
            success: function(res) {
              $('#accordion').find('.panel-heading').removeClass('bg-active');
              $('#SubCatsHolder').html(res.SubCategoryHtml);
              setTimeout(function(){
                var keywordVal = $('#search_input').val();
                $('#search_input').focus().val('').val(keywordVal);
              }, 500)
            }
          });
        }
        if(length > 3){
            $.ajax({
              type: "POST",
              url: APP_URL+'/admin/get-subcats-view/'+catId,
              data: {keyword:keywordVal},
              dataType: "json",
              success: function(res) {
                $('#accordion').find('.panel-heading').removeClass('bg-active');
                $('#SubCatsHolder').html(res.SubCategoryHtml);
                setTimeout(function(){
                  var keywordVal = $('#search_input').val();
                  $('#search_input').focus().val('').val(keywordVal);
                }, 500)
              }
            });
        }
    }else{
      e.preventDefault();
      return false;      
    }
    
  }
  function SetEdit(element){
    var Id = $(element).attr('data-id');
    var ParentId = $(element).attr('data-parent-id');
    var Title = $(element).parents('.panel-title').find('.title-container').text();
    var Fr_Title = $(element).parents('.panel-title').find('.fr-title-container').text();
    var Form = $('#SubCategoryModal').find('form');
    $(Form).find('input[name="id"]').val(Id);
    $(Form).find('input[name="parent_id"]').val(ParentId);
    $(Form).find('input[name="category_name"]').val(Title);
    $(Form).find('input[name="fr_category_name"]').val(Fr_Title);
    $('#SubCategoryModal').modal('show');
  }

  function SaveCategory(form){
    var Data = $(form).serialize();
    $.ajax({
      type: "POST",
      url: $(form).attr('action'),
      data: Data,
      dataType: "json",
      success: function(res) {
        var Id = $(form).find('input[name="id"]').val();
        var ParentId = $(form).find('input[name="parent_id"]').val();
        var Title = $(form).find('input[name="category_name"]').val();
        var FrTitle = $(form).find('input[name="fr_category_name"]').val();

        if(Id=='0'){
            $('#main_category_'+ParentId).find('a').click();
            var OldCount = $('#main_category_'+ParentId).find('.label-counter').html();
            $('#main_category_'+ParentId).find('.label-counter').html(parseFloat(OldCount)+1)
        }else{
          $('#sub_category_'+Id).find('.title-container').html(Title)
          $('#sub_category_'+Id).find('.fr-title-container').html(FrTitle)
        }
        $(form).parents('.modal').modal('hide');
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
    });
    return false;
  }
  function GetCategoryEdit(element){
    //IsChildWindow = true;
    var Form = $('#CategoryModal').find('form')
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    $.ajax({
      type: "POST",
      url: APP_URL+'/admin/category/'+Id,
      data: "",
      dataType: "json",
      success: function(res) {
        $('#CategoryModal').modal('show');
        SetFormValues(res.inputs,Form);
        $(Form).find('input[name="icon"]').next('.input-group-addon').find('i.fa').attr('class','fa fa-fw '+res.inputs.icon.value);
        console.log($(Form).find('input[name="icon"]').next('.input-group-addon').find('i.fa'));
        $("#background-image").fileinput('destroy');
        if(res.inputs.image.file!=''){
          $("#background-image").fileinput('clear');
          $("#background-image").fileinput('refresh',{
            'initialPreview': [
              '<img src="'+res.inputs.image.file+'" class="file-preview-image" width="auto">'
            ],
            'initialPreviewAsData': false,
            'initialPreviewConfig': [
              {key: 1, showDelete: true}
            ],
            'deleteUrl': APP_URL+'/admin/delete-category-image/'+Id+'/bg',
            'overwriteInitial': true,
            'showUpload': false,
            'showRemove': false,
          });
          $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        }else{
          $("#background-image").fileinput('clear');
          $("#background-image").fileinput('refresh',{
            showUpload: false,
            showRemove: false,
          });
        }

        if(res.inputs.icon.file!=''){
          $("#icon-image").fileinput('clear');
          $("#icon-image").fileinput('refresh',{
            'initialPreview': [
              '<img src="'+res.inputs.icon.file+'" class="file-preview-image" width="500px">'
            ],
            'initialPreviewAsData': false,
            'initialPreviewConfig': [
              {key: 1, showDelete: true}
            ],
            'deleteUrl': APP_URL+'/admin/delete-category-image/'+Id+'/icon',
            'overwriteInitial': true,
            'showUpload': false,
            'showRemove': false,
          });
          $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        }else{
          $("#icon-image").fileinput('clear');
          $("#icon-image").fileinput('refresh',{
            showUpload: false,
            showRemove: false,
          });
        }
      }
    })
  }
  function GetDelete(element,type){
    //IsChildWindow = true;
    var Id = $(element).attr('data-id');
    var IsDeleteMdal = $('#DeleteModal').length;    
    var Title = "";
    var Msg = "";
    if(type=='sub'){
      Title = "Delete Sub Category";
      Msg = "Are you sure you want to delete this sub category?";
    }else if(type=='main'){
      Title = "Delete Category";
      Msg = "Are you sure you want to delete this category?";
    }
    if(IsDeleteMdal=='0'){
      $('body').loader('show');
      MakeDeleteModal(Id,Title,Msg,'RemoveCategory()','1');
    }else{
      MakeDeleteModal(Id,Title,Msg,'RemoveCategory()','0');
    }
  }
  
  function RemoveCategory(){
    var Id = $('#delete-modal-id').val();
    $('#DeleteModal').loader('show');
    $.ajax({
      type: "DELETE",
      url: 'categories/'+Id,
      data: "id="+Id,
      dataType: "json",
      success: function(res) {
        if(res.type=='sub_cat'){
          var ParentId = res.parent;
          $('#sub_category_'+Id).remove();
          var OldCount = $('#main_category_'+ParentId).find('.label-counter').html();
          $('#main_category_'+ParentId).find('.label-counter').html(parseFloat(OldCount)-1)
        }else{
          $('#main_category_'+Id).remove();
        }
        $('#DeleteModal').loader('hide');
        $('#DeleteModal').modal('hide');
      }
    });
  }
  function SaveMainCategory(form){
    var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
        var Id = $(form).find('input[name="id"]').val();
        if(Id=='0'){
          $('#CatsHolder').find('.categories-group').find('.panel:first').after(res.CategoryHtml);
          //window.location.reload();
        }else{
          var CategoryHolder = $('#main_category_'+Id);
          CategoryHolder.after(res.CategoryHtml);
          CategoryHolder.remove();
        }
        $(form).parents('.modal').modal('hide');

        var SubList = $('#ProductModal').find('select[name="cat_id"]');
        $(SubList).select2('destroy');
        $(SubList).html('');
        $.each(res.catsoptions, function(index, catsoptions){
          var HTML = "<option value='"+index+"'>"+catsoptions+"</option>";
          $(SubList).append(HTML);
        });

        $(SubList).select2({
            placeholder: "Select",
            allowClear: true,
        });
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
    }
    $(form).ajaxSubmit(options);
    return false;
  }
</script>
<script>
  var Modal = $('#ProductModal');
  var Form = $(Modal).find('form');
  $(document).ready(function () {
    Form.find('select[name="cat_id"]').change();
    $('#ProductModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="recycle_fee"]').val('');
      $(Form).find('input[name="fr_product_name"]').val('');
      $(Form).find('input[name="product_name"]').val('');
      $(Form).find('select[name="unit"]').val('').change();
      $(Form).find('#related_products').val('').change();
      $(Form).find('textarea[name="description"]').val('');
      $(Form).find('textarea[name="fr_description"]').val('');
      $(Form).find('input:checkbox').iCheck('uncheck');
      $(Form).find('input:checkbox').iCheck('update');
      $("#product-image").fileinput('clear');
      $(Form).find('.form-errors').hide();
      $('#product-modal-title').html('Add Product');
      $('#related_products').find('.disabled_option').removeAttr('disabled').removeClass('.disabled_option');
      $('#related_products').select2('destroy');
      $('#related_products').select2({
        placeholder: "Select",
        allowClear: true,
      });
    })
  });
  function SetAddNew(element){
    var SubCat = $(element).data('subcat');
    var Cat = $(element).data('parent-id');
    Form.find('select[name="cat_id"]').val(Cat).change();
    global_sub_cat = SubCat;
    /*setTimeout(function(){
      Form.find('select[name="subcat_id"]').val(SubCat);
    },1000)
    if($(Form).find('select[name="subcat_id"]').val()==''){
      Form.find('select[name="subcat_id"]').val(SubCat);
    }
    Form.find('select[name="subcat_id"]').change();*/
    $(Modal).modal('show');
  }
  function SetProductEdit(element){
    var Form = $('#ProductModal').find('form')
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    $.ajax({
      type: "POST",
      url: APP_URL+'/admin/products/'+Id,
      data: "id="+Id,
      dataType: "json",
      success: function(res) {
        $('#related_products').find('option[value="'+res.inputs.id.value+'"]').addClass('disabled_option').attr('disabled','disabled');
        $('#product-modal-title').html('Edit Product');
        $('#ProductModal').modal('show');
        SetFormValues(res.inputs,Form);
        $("#product-image").fileinput('destroy');
        if(res.inputs.image.file!=''){
          $("#product-image").fileinput('refresh',{
            'initialPreview': [
            '<img src="'+res.inputs.image.file+'" class="file-preview-image" width="auto">'
            ],
            'initialPreviewAsData': true,
            'overwriteInitial': true,
            'showUpload': false,
            'showRemove': false,
          });
          $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        }else{
          $("#product-image").fileinput('clear');
          $("#product-image").fileinput('refresh',{
            showUpload: false,
            showRemove: false,
          });
        }


      }
    });
  }
  function SaveProduct(form){
    var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
        var Id = $(form).find('input[name="id"]').val();
        var SubCatId = $(form).find('select[name="subcat_id"]').val();
        var ParentId = $(form).find('select[name="cat_id"]').val();
        var Title = $(form).find('input[name="category_name"]').val();
        $('#main_category_'+ParentId).find('a').click();
        /*if(Id=='0'){
          $('#sub_cat_products_list_'+SubCatId).append(res.productHTML);
        }else{
          var Product = $('#sub_cat_products_list_'+SubCatId).find('#product_'+Id);
          $(Product).after(res.productHTML);
          $(Product).remove();
        }*/
        if(Id=='0'){
          var OldCount = $('#product_counter').html();
          var NewCount = OldCount = parseFloat(OldCount) + parseFloat(1);
          $('#product_counter').html(NewCount);
        }
        
        $(form).parents('.modal').modal('hide');
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
    }
    $(form).ajaxSubmit(options);
    return false;
  }
  function GetProductDelete(element) {
    var Id = $(element).attr('data-id');
    var IsDeleteMdal = $('#DeleteModal').length;
    if (IsDeleteMdal == '0') {
      $('body').loader('show');
      MakeDeleteModal(Id, 'Delete Product', 'Are you sure you want to delete this product?', 'RemoveProduct()', '1');
    } else {
      MakeDeleteModal(Id, 'Delete Product', 'Are you sure you want to delete this product?', 'RemoveProduct()', '0');
    }
  }
  function RemoveProduct() {
    var Id = $('#delete-modal-id').val();
    $('#DeleteModal').loader('show');
    $.ajax({
      type: "DELETE",
      url: APP_URL+'/admin/products/' + Id,
      data: "id=" + Id,
      dataType: "json",
      success: function (res) {
        $('.categories-group').find('.bg-active').find('a').click();
        //$('#product_' + Id).remove();
        $('#DeleteModal').loader('hide');
        $('#DeleteModal').modal('hide');

        var OldCount = $('#product_counter').html();
        var NewCount = OldCount = parseFloat(OldCount) - parseFloat(1);
        $('#product_counter').html(NewCount);
      }
    });
  }
</script>
@stop