@extends('layouts.main')

@section('content')
<div class="media">
  <div class="media-middle media-left">
    <span class="bg-primary circle sq-48">
      <span class="icon icon-pencil-square-o"></span>
    </span>
  </div>
  <div class="media-middle media-body">
    <h3 class="media-heading">
      <span class="fw-l">Take Order</span>

    </h3>
  </div>
</div>

<br/>
<script> 
  var count = 1;
</script>
<div class="row gutter">
  <div class="col-sm-6">
    <div class="card bg-primary">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <label>Order No:</label>
            <input class="form-control" type="text" value="S-{{$salesCount}}" disabled id="orderNo"/>
          </div>
          <div class="col-md-6">
            <label>OS No:</label> 
            <input class="form-control" type="text" id="osNo" value="OS-{{$osCount}}"/>
          </div>
        </div><br/>
        <div class="row">
          <div class="col-md-12">
            <label>Customer:</label>
            <select id="bp" class="custom-select">

              @foreach($bps as $bp)
              <option value="{{$bp->id}}">{{$bp->name}}</option>
              @endforeach
            </select>
          </div>
        </div><br/>
        <div class="row">
          <div class="col-md-6">
            <label>Table:</label>
            <select id="table" class="custom-select custom-select-lg">
         
              @foreach($tables as $t)
              <option value="{{$t->id}}">{{$t->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label>Server:</label>
            <select id="waiter" class="custom-select custom-select-lg">
          
              @foreach($users as $u)
              <option value="{{$u->id}}" @if($u->id == Auth::user()->id) selected @endif>{{$u->name}}</option>
              @endforeach
            </select>
          </div>
        </div><br/>
        <div class="table-responsive">
          <table id="orderList" class="table table-bordered table-hover">
          <thead>
            <tr style="color:black;">
              <th>Menu Item</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Total Price</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        </div><br/>
        <div class="row">
          <div class="col-md-12">
            <label>Remarks:</label>
            <textarea id="remarks" class="form-control" rows="3">
            </textarea>
          </div>
        </div>
        <h4>TOTAL: <strong><span id="grandTotal"></span></strong></h4>
        <input type="hidden" id="hidGrandTotal"/>
        <div class="row">
          <div class="col-md-12">
            <button class="btn btn-lg btn-block btn-info" id="saveSale">TAKE ORDER</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <h4>Menu Items</h4>
    <div class="card">
      <div class="card-body">
        <div class="panel m-b-lg">
          <div class="tabs-left">
            <ul class="nav nav-tabs">
              @foreach($categories as $cat)
              @if($cat->id == 1)
              <li class="active"><a href="#cat-{{$cat->id}}" data-toggle="tab">{{$cat->name}}</a></li>
              @else
              <li><a href="#cat-{{$cat->id}}" data-toggle="tab">{{$cat->name}}</a></li>
              @endif
              @endforeach



            </ul>
            <div class="tab-content">
              @foreach($categories as $cat)


              <div class="tab-pane fade @if($cat->id == 1) active in @endif" id="cat-{{$cat->id}}">
                <div class="row gutter-xs">
                  <?php $tempCounter = 1;?>
                  @foreach($menuItems as $mi)
                  @if($mi->menuCategoryId == $cat->id)

                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <h5>{{$mi->name." ".$mi->discountName}}</h5>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="row gutter-xs">
                          <div class="col-md-6">
                            <button class="btn btn-block btn-info plusMinus" data-id="1" value="{{$mi->id}}"><span class="icon icon-plus"></span></button>
                          </div>
                          <div class="col-md-6">
                            <button class="btn btn-block btn-primary plusMinus" data-id="2" value="{{$mi->id}}"><span class="icon icon-minus"></span></button>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  @endif
                  @endforeach
                </div>
              </div>




              @endforeach

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>


  function computeTotal(){
    var total=0;
    $(".form-control-sm.tpItem").each(function() {
            //add only if the value is number
            var tempAmount = 0;

            if(this.value.length != 0)
             tempAmount = this.value;
           else
            tempAmount = 0;


          total += parseFloat(tempAmount);

          

        });

    $("#hidGrandTotal").val(total);
    $("#grandTotal").html(total);
    $("#grandTotal").number(true, 2);

    return total;
  }


  function priceMultiplier(x){

    var price = parseFloat($("#hidPrice"+x).val() * $("#qty"+x).val());

    $("#totalPriceText"+x).html(price);
    $("#hidTotalPrice"+x).val(price);

    $("#totalPriceText"+x).number(true,2);


    computeTotal();
  }

  $(document).ready(function() {

    $("#itemCategoryText").hide();

    var $itemSelect = $('#item');
    $itemSelect.select2({ dir: 'ltr' });

//  $("#resellerPrice").hide();



$(".plusMinus").click(function(e){

  var plusMinus = $(this).attr("data-id");

  var invID = {

    miID: $(this).val(),
    action: 1
  };


  $.ajax({
   type:"POST",
   url: "{{route('sales.getSetSales')}}",
   data: invID,
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){

    console.log(data);
    if(plusMinus == 1){
      if($("#qty"+data.id).val() != null){
        $("#qty"+data.id).val(parseFloat($("#qty"+data.id).val())+1);
        $("#qtyText"+data.id).html($("#qty"+data.id).val());
        priceMultiplier(data.id);
        computeTotal();

      }
      else{

        var itemQty='<span id="qtyText'+data.id+'">1</span><input id="qty'+data.id+'" qtyCount="'+count+'" pos="item'+data.id+'" style="text-align: right;" type="hidden" class="form-control form-control-sm qtyItem" disabled value="1"/>';

        var itemPrice='<span>'+$.number(data.sellingPrice,2)+'</span><input id="hidPrice'+data.id+'" class="priceItem" pos2="itemPriceHid'+data.id+'" type="hidden" disabled value="'+data.sellingPrice+'"/>';

        var itemTotalPrice='<span id="totalPriceText'+data.id+'">'+$.number(data.sellingPrice,2)+'</span><input id="hidTotalPrice'+data.id+'" class="form-control-sm tpItem" type="hidden" value="'+data.sellingPrice+'"/>';

        $('#orderList > tbody:last-child').append('<tr style="color:black;font-size:20px;" id="orderRow'+data.id+'" class="rowCount"><td width="40%"><input type="hidden" class="selectedItem" value="'+data.id+'"/><input type="hidden" class="selectedCode" value="'+data.code+'"/>'+data.name+'</td><td>'+itemQty+'</td><td>'+itemPrice+'</td>><td>'+itemTotalPrice+'</td><td><button class="btn btn-info" data-id="10" value="'+data.id+'"><span class="icon icon-plus"></span></button><button class="btn btn-primary" data-id="11" value="'+data.id+'"><span class="icon icon-minus"></span></button></td></tr>');

        count++;
        computeTotal();
      }
    }
    else if(plusMinus == 2){
     if($("#qty"+data.id).val() != null && $("#qty"+data.id).val() != 1){
      $("#qty"+data.id).val(parseFloat($("#qty"+data.id).val())-1);
      $("#qtyText"+data.id).html($("#qty"+data.id).val());
      priceMultiplier(data.id);

    }
    else if($("#qty"+data.id).val() == 1){
      $("#orderRow"+data.id).remove();
      computeTotal();
    }
  }


}

});
});

$('#orderList tbody').on( 'click', 'button', function (){
  var id = $(this).attr("data-id");
  var invId =  $(this).val();
  if(id==10){

    $("#qty"+invId).val(parseFloat($("#qty"+invId).val())+1);
    $("#qtyText"+invId).html($("#qty"+invId).val());
    priceMultiplier(invId);


  }

  if(id==11){
   if($("#qty"+invId).val() != null && $("#qty"+invId).val() != 1){
    $("#qty"+invId).val(parseFloat($("#qty"+invId).val())-1);
    $("#qtyText"+invId).html($("#qty"+invId).val());
    priceMultiplier(invId);

  }
  else if($("#qty"+invId).val() == 1){
    $("#orderRow"+invId).remove();
    computeTotal();
  }
}

});

$("#saveSale").click(function(e){

  if($("#hidGrandTotal").val() == ""){
    var title   = 'Error!',
    message = 'Please add item to order list',
    type    = 'error',
    options = {};

    toastr[type](message, title, options);
  }
  else{

    $("#saveSale").prop("disabled",true);

    var masterlistIDs= [];
    var qty = [];
    var price = [];
    var codes = [];

    var invIds = $('#orderList').find('.selectedItem');
    var codeList = $('#orderList').find('.selectedCode');
    var qties = $('#orderList').find('.qtyItem');
    var prices = $('#orderList').find('.priceItem');

    invIds.each(function(){
      masterlistIDs.push(this.value);
      
    });

    codeList.each(function(){
      codes.push(this.value);
    })

    qties.each(function(){
      qty.push(this.value);
      
    });

    prices.each(function(){
      price.push(this.value);
      
    });


    var invID = {
      masterlistIDs: masterlistIDs,
      qty: qty,
      price: price,
      codes: codes,
      table: $("#table").val(),
      waiter: $("#waiter").val(),
      orderNo: $("#orderNo").val(),
      bp: $("#bp").val(),
      osNo: $("#osNo").val(),
      total: $("#hidGrandTotal").val(),
      remarks: $("#remarks").val(),
      //  total
      miID: $(this).val(),
      action: 2
    };


    $.ajax({
     type:"POST",
     url: "{{route('sales.getSetSales')}}",
     data: invID,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){
      $("#saveSale").prop("enabled",true);
      var title   = 'Success!',
      message = 'Order placed for '+data.bpName,
      type    = 'success',
      options = {};

      toastr[type](message, title, options);

      $("#orderList > tbody").empty();
      $("#saveSale").prop("disabled",false);

      $("#orderNo").val("S-"+data.saleCount);
      $("#osNo").val('');
      $("#bp").val(1);
      $("#table").val(0);
      $("#remarks").val('');
      $("#waiter").val(data.waiterId);
      $("#hidGrandTotal").val('');
      $("#grandTotal").html('');



    }
    
  });
  }

});


});
</script>
@endsection