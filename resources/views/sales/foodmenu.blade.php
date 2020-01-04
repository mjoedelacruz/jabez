@extends('layouts.main')

@section('modal')
<div id="dangerModalAlert" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-primary icon icon-info icon-5x"></span>
          <h3 class="text-primary">Warning</h3>
          <h4>This action will remove saved ingredient from list.</h4>
          <div class="m-t-lg">
            <button id="deleteIngreRow" class="btn btn-primary" data-dismiss="modal" type="button">Continue</button>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div> 
</div>
@endsection
@section('content')

<div class="media">
  <div class="media-middle media-left">
    <span class="bg-primary circle sq-48">
      <span class="icon icon-cutlery"></span>
    </span>
  </div>
  <div class="media-middle media-body">
    <h3 class="media-heading">
      <span class="fw-l">Menu Item List</span>
    </h3>
  </div>
</div>
<br/>

<script>
  var count = 1;
  var selectedItems2 = [0];
</script>

<div class="card" id="itemInfoDiv">

  <div class="card-body">
    <div class="row">
      <div class="col-md-5">
        <div id="itemDiv" class="form-group">
          <label>Name </label>
          <input id="itemName" type="text" class="form-control" placeholder="Enter Item Name..."/>
        </div>
      </div>
      <div class="col-md-3">
        <div id="catDiv" class="form-group">
          <label>Category</label>
          <select id="itemCategory" class="form-control" placeholder="Select Category...">
            <option value="0">--Select Category--</option>
            @foreach($menuCategories as $mct)
            <option value="{{$mct->id}}">{{$mct->name}}</option>
            @endforeach
          </select>
          <input id="itemCategoryText" class="form-control" placeholder="Enter New Category..." />
          &nbsp;<button class="btn btn-link btn-sm" value="1" id="addCategory"><i class="icon icon-plus"></i> Add Category</button>
        </div>
      </div>
      <div class="col-md-2">
        <div id="unitPriceDiv" class="form-group">
          <label>Selling Price</label>
          <input id="itemSellingPrice" type="text" class="form-control" placeholder="0.00"/>
        </div>
      </div>
      <div class="col-md-2">
        <div id="statusDiv" class="form-group">
          <label>Status</label>
          <select id="itemStatus" class="form-control">
            <option value="1">Active</option>
            <option value="2">Inactive</option>
          </select>
        </div>
      </div>

    </div>

    <div class="row m-t-0">
      <div class="col-md-5">
        <div id="descriptionDiv" class="form-group">
          <label>Description</label>
          <input id="itemDescription" type="text" class="form-control" placeholder="Enter Description..."/>
        </div>
        <div class="row">

          <div class="col-md-6">
            <label>Discount</label>
            <select class="form-control" id="discountId">
              <option value="0">---Select Discount----</option>
              @foreach($discounts as $d)
              <option value="{{$d->id}}">{{$d->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-5"><br/>
        <div class="row">
          <div class="col-md-12">
            <label>INGREDIENTS LIST</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-9">
            <select id="item" accesskey="s" data-placeholder="Choose item..." class="standardSelect" tabindex="1" >
              <option value=""></option>
              @foreach($invList as $i)
              @if($i->id != 0)
              <option value="{{$i->id}}">{{$i->name}}</option>

              @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <button id="addIngredient" class="btn btn-primary"><span class="icon icon-plus"></span>&nbsp; Add Ingredient</button>
          </div>
        </div><br/>
        <div class="row">
          <div class="col-md-12">
            <table id="ingredientsTable" class="table table-striped">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>UoM</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="ingredientsBodyTable">
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <button id="addItem" value="0" data-action="1" style="float:right;" class="btn btn-lg btn-primary">Add Item</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5><strong>MENU ITEM LIST</strong></h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="miList" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Menu Name</th>
            <th>Description</th>
            <th>Category</th>  
            <th>Selling Price</th>

            <th></th>
          </tr>
        </thead>

        <tbody style="font-size:12px;">
         <script>
          $('#miList').DataTable({

            ajax: "{{route('dataTablesMenuItemsList')}}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            "order": [[ 0, "asc" ]],
            'columns': [
            { 'data': 'name' },
            { 'data': 'description' },
            { 'data': 'catName'},
            { 'data': 'sellingPrice' },

            { 
              "className": 'options',
              "data":    null,
              "render": function(data, type, full, meta){
                var valueHere=data.code;

                return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-success" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
              }

            },

            ],


          });

          $('#miList tbody').on( 'click', 'button', function (){
            var id = $(this).attr("data-id");

            if(id==1){
             var salesInfo = {
              miID: $(this).val(),
              action: 3

            }; 

            $.ajax({
             type:"POST",
             url: "{{route('menuItems.getSetMenuItems')}}",
             data: salesInfo,
             headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
             success: function (data){
              console.log(data);
              $("#addItem").val(data.menuItem.id);
              $("#addItem").attr("data-action", 2);

              $("#addItem").text("Edit Item");
              $("#addItem").removeClass("btn-primary").addClass("btn-success");


              $("#item").empty();
              $("#ingredientsTable > tbody").empty();  

              var options2 = "<option value=''></option>";

              for(var i = 0; i < data.invList.length; i++){
                options2 +="<option value='"+data.invList[i]["id"]+"'>"+data.invList[i]["name"]+"</option>";
              }

              $("#item").html(options2);
              jQuery(".standardSelect").trigger("chosen:updated");

              $("#itemName").val(data.menuItem.name);
              $("#itemDescription").val(data.menuItem.description);
              $("#itemCategory").val(data.menuItem.menuCategoryId);
              $("#itemStatus").val(data.menuItem.status);
              if(data.menuItem.discountId != null)
                $("#discountId").val(data.menuItem.discountId);
              else
                $("#discountId").val(0);

              $("#itemSellingPrice").val(data.menuItem.sellingPrice);



              count++;

              for(var i = 0; i < data.miList.length; i++){
                $('#ingredientsTable > tbody:last-child').append('<tr id="savedRow'+data.miList[i]["invId"]+'" class="rowCount"><td width="40%"><input type="hidden" class="selectedItem" value="'+data.miList[i]["invId"]+'"/>'+data.miList[i]["invName"]+'</td><td width="20%"><input id="qty'+count+'" class="form-control ingreQty" type="text" data-id="'+data.miList[i]["id"]+'" data-inv="'+data.miList[i]["invId"]+'" value="'+data.miList[i]["qty"]+'"/></td><td width="20%">'+data.miList[i]["uomName"]+'</td><td><button id="removeIngre" class="btn btn-primary btn-sm" miId="'+data.miList[i]["id"]+'" invId="'+data.miList[i]["invId"]+'" value="'+count+'" data-toggle="modal" data-id="2" data-target="#dangerModalAlert"><span class="icon icon-trash"></span></button></td></tr>');
              }


            }


          });

            $("html, body").animate({
              scrollTop: 0
            }, 800);
          }

        });


      </script> 
    </tbody>

  </table>
</div>

</div>
</div>
<script>
  $(document).ready(function() {

    $("#itemCategoryText").hide();

    var $itemSelect = $('#item');
    $itemSelect.select2({ dir: 'ltr' });

//  $("#resellerPrice").hide();

$("#addCategory").click(function(e){
  if($(this).val() == 1){
   $("#itemCategory").hide();
   $("#itemCategoryText").show();

   $("#itemCategoryText").focus();
   $("#addCategory").text("Back to Default Selection");
   $("#addCategory").val(2);
 }
 else if($(this).val() == 2){
   $("#itemCategory").show();
   $("#itemCategoryText").hide();
   $("#itemCategoryText").val('');

   $("#addCategory").html("<i class='fa fa-plus'></i> Add Category");
   $("#addCategory").val(1);
 }

});

$("#addIngredient").click(function(e){

  var invID = {

    invID: $("#item").val(),
    action: 1
  };

  if($("#item").val() >= 1){
    $.ajax({
     type:"POST",
     url: "{{route('menuItems.getSetMenuItems')}}",
     data: invID,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){

      count++;

      $('#ingredientsTable > tbody:last-child').append('<tr id="rowIngre'+count+'" class="rowCount"><td width="40%"><input type="hidden" class="selectedItem" value="'+data.id+'"/>'+data.name+'</td><td width="20%"><input id="qty'+count+'" class="form-control ingreQty" type="text" data-id="0" data-inv="'+data.id+'" value="0"/></td><td width="20%">'+data.uomName+'</td><td><button id="removeIngre" class="btn btn-primary btn-sm" data-id="1" invId="'+data.id+'" value="'+count+'"><span class="icon icon-trash"></span></button></td></tr>');

      $("#item option[value='"+data.id+"']").remove();

      jQuery(".standardSelect").trigger("chosen:updated");

      var selects = $('#ingredientsTable').find('.selectedItem');

      selects.each(function(){
       selectedItems2.push(this.value);
     });

    }


  });
  }


});


//REMOVE INGREDIENT IF NOT YET SAVED
$('#ingredientsTable tbody').on( 'click', 'button', function (){
  var id = $(this).attr("data-id");

  if(id==1){

    var x = $(this).attr('value');


    var itemInfo = {
      invID: $(this).attr("invId"),
      action: 1,
    };

    $.ajax({
     type:"POST",
     url: "{{route('menuItems.getSetMenuItems')}}",
     data: itemInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){

      $("#item").append("<option value='"+data.id+"'>"+data.name+"</option>");

      jQuery(".standardSelect").trigger("chosen:updated");

    }
  });

    $("#rowIngre"+x).remove();


  }
  if(id == 2){
    $("#deleteIngreRow").val($(this).attr("miId"));
  }


});

$("#deleteIngreRow").click(function(e){

  var delRow = {
    id: $(this).val(),
    action: 4
  };

  $.ajax({
   type:"POST",
   url: "{{route('menuItems.getSetMenuItems')}}",
   data: delRow,
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){

    if(data != null){

      $("#item").append("<option value='"+data.id+"'>"+data.name+"</option>");
      jQuery(".standardSelect").trigger("chosen:updated");


    }



    $("#savedRow"+data.id).remove();
  }

});
});

$("#addItem").click(function(e){



  var itemQtys = [9999];
  var invMiIDs = [9999];
  var invIDs = [9999];

  var ingreQty = $('#ingredientsTable').find('.ingreQty');

  ingreQty.each(function(){
    itemQtys.push(this.value);
    invMiIDs.push(this.getAttribute("data-id"));
    invIDs.push(this.getAttribute("data-inv"));
  });




  var itemInfo = {
    name: $("#itemName").val(),
    category: $("#itemCategory").val(),
    categoryText: $("#itemCategoryText").val(),
    sellingPrice:$("#itemSellingPrice").val(),
    status:$("#itemStatus").val(),
    sellingPrice: $("#itemSellingPrice").val(),
    description: $("#itemDescription").val(),
    discountId: $("#discountId").val(),
    itemQtys: itemQtys,
    invMiIDs: invMiIDs,
    invIDs: invIDs,
    action: 2,
    miID: $(this).val(),
    save: $("#addItem").attr("data-action"),

  };

  if($("#itemName").val() == "" || ($("#itemCategory").val() == 0 && $("#itemCategoryText").val() == "") || $("#itemSellingPrice").val() == "" || $("#itemDescription").val() == ""){
    var title   = 'Error!',
    message = 'Please check information',
    type    = 'error',
    options = {};

    toastr[type](message, title, options);

    if($("#itemName").val() == ""){
      $("#itemDiv").addClass("has-error");
    }
    else{
      $("#itemDiv").removeClass("has-error");
    }

    if($("#itemCategory").val() == 0 && $("#itemCategoryText").val()==""){
      $("#catDiv").addClass("has-error");
    }
    else if($("#itemCategory").val() == 0 && $("#itemCategoryText").val()!=""){
      $("#catDiv").removeClass("has-error");
    }
    else{ 
      $("#catDiv").removeClass("has-error");
    }

    if($("#itemDescription").val()==""){
      $("#descriptionDiv").addClass("has-error");
    }
    else{
      $("#descriptionDiv").removeClass("has-error");
    }

    if($("#itemSellingPrice").val() == ""){
      $("#unitPriceDiv").addClass("has-error");
    }
    else{
      $("#unitPriceDiv").removeClass("has-error");
    }

  }
  else{ 
    $("#addItem").prop("disabled",true);
    $.ajax({
     type:"POST",
     url: "{{route('menuItems.getSetMenuItems')}}",
     data: itemInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){

      $('#ingredientsTable > tbody').empty();
      $("#item").empty();

                    //  console.log(data);     
                    if(data.save == 1){
                      var title   = 'Success!',
                      message = 'Added Item: '+data.name,
                      type    = 'success',
                      options = {};

                      toastr[type](message, title, options);
                    }
                    else if(data.save == 2){
                     var title   = 'Edit Successful!',
                     message = 'Edited Item: '+data.name,
                     type    = 'success',
                     options = {};

                     toastr[type](message, title, options);

                   }       

                   $("#itemDiv").removeClass("has-error");
                   $("#statusDiv").removeClass("has-error");
                   $("#descriptionDiv").removeClass("has-error");
                   $("#unitPriceDiv").removeClass("has-error");
                   $("#catDiv").removeClass("has-error");

                   $("#addItem").val(0);
                   $("#addItem").attr("data-action", 1);
                   $("#discountId").val(0);

                   $("#addItem").text("Add Item");
                   $("#addItem").removeClass("btn-success").addClass("btn-primary");


                   $("#itemName").val('');
                   $("#itemCategory").val(0);


                   var options = "<option value='0'>--Select Category--</option>";

                   for(var i = 0; i < data.categories.length; i++){
                    options +="<option value='"+data.categories[i]["id"]+"'>"+data.categories[i]["name"]+"</option>";
                  }

                  $("#itemCategory").html(options);
                  $("#itemCategory").show();

                  $("#addCategory").html("<i class='fa fa-plus'></i> Add Category");
                  $("#addCategory").val(1);
                  $("#itemCategoryText").val('');
                  $("#itemSellingPrice").val('');
                  $("#itemDescription").val('');
                  $("#itemCategoryText").hide();

                  console.log('done');

                  $("#addItem").prop("disabled",false);


                  var options2 = "<option value=''></option>";

                  for(var i = 0; i < data.invList.length; i++){
                    options2 +="<option value='"+data.invList[i]["id"]+"'>"+data.invList[i]["name"]+"</option>";
                  }

                  $("#item").html(options2);
                  jQuery(".standardSelect").trigger("chosen:updated");

                  

                  $('#miList').DataTable({

                    ajax: "{{route('dataTablesMenuItemsList')}}",
                    'paging'      : true,
                    'lengthChange': true,
                    'searching'   : true,
                    'ordering'    : true,
                    'info'        : true,
                    'bDestroy'    : true,
                    'autoWidth'   : true,
                    "order": [[ 0, "asc" ]],
                    'columns': [
                    { 'data': 'name' },
                    { 'data': 'description' },
                    { 'data': 'catName'},
                    { 'data': 'sellingPrice' },

                    { 
                      "className": 'options',
                      "data":    null,
                      "render": function(data, type, full, meta){
                        var valueHere=data.code;

                        return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-success" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
                      }

                    },

                    ],


                  });

                }

              });
}

});

});
</script>

@endsection

