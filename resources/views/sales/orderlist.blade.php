@extends('layouts.main')

@section('modal')
<div id="discountModal" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">DISCOUNTS</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <label>Choose Discount</label>
            
          </div>
        </div><br/>
        

        <div class="row">
          <div class="col-md-12">
            <h5>Total Discount: <span id="totalDiscount"></span><input type="hidden" id="hidTotalDiscount" value=""/></h5>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" id="confirmDiscount" type="button">Apply Discount</button>
        <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="deletePaymentModal" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-primary icon icon-exclamation-triangle icon-5x"></span>
          <h3 class="text-primary">Info</h3>
          <p>This will delete payment item</p>
          <div class="m-t-lg">
            <button class="btn btn-primary" data-dismiss="modal" id="continueDeletePayment" type="button">Continue</button>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<div id="settleOrderModal" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-primary icon icon-exclamation-triangle icon-5x"></span>
          <h3 class="text-primary">Info</h3>
          <p>Are you sure you want to Settle Order</p>
          <div class="m-t-lg">
            <button class="btn btn-primary" data-dismiss="modal" id="settleOrder" type="button">Settle Order</button>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<div id="voidOrderModal" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-default icon icon-exclamation-triangle icon-5x"></span>
          <h3 class="text-default">Info</h3>
          <p>Are you sure you want to Void Order</p>
          <div class="m-t-lg">
            <button class="btn btn-default" data-dismiss="modal" id="voidOrderButton" type="button">Void Order</button>
            <button class="btn btn-primary" data-dismiss="modal" type="button">Cancel</button>
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
      <span class="icon icon-th-list"></span>
    </span>
  </div>
  <div class="media-middle media-body">
    <h3 class="media-heading">
      <span class="fw-l">Order List</span>
    </h3>
  </div>
</div>

<br/> 
<script>
  var count = 1;
  var paymentRowCount = 1;
</script>
<div class="row gutter-s">
  <div class="col-sm-6">
   <div class="card">
    <div class="card-header">
      <div class="row gutter-s">
        <div class="col-sm-6">
          <div class="input-group input-daterange" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
            <input class="form-control" id="dateStart" type="text" value="{{date('Y-m-d')}}">
            <span class="input-group-addon">to</span>
            <input class="form-control" id="dateEnd" type="text" value="{{date('Y-m-d')}}">
          </div>
        </div>
        <div class="col-sm-6">
          <button class="btn btn-primary" id="sortOrderList"><span class="icon icon-filter"></span> &nbsp;Filter</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="ordersTable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th></th>
              <th>Order No.</th>
              <th>Date</th>
              <th>Customer/Table</th>

              <th>Total Bill</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody style="font-size:12px;">
           <script>
            $('#ordersTable').DataTable({

              ajax: "/dataTablesOrderList/{{date('Y-m-d')}}/{{date('Y-m-d')}}",
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true,
              "order": [[ 0, "asc" ]],
              'columns': [
              { 
                "className": 'options',
                "data":    null,
                "render": function(data, type, full, meta){
                  var valueHere=data.id;
                  if(data.status == 1){
                    return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
                  }
                  else if(data.status == 2){
                    return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
                  }
                  else if(data.status == 3){
                    return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-default" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
                  }

                }

              },
              { 'data': 'orderNo' },
              { 'data': 'date' },
              { 'data': 'customer' },

              { 'data': 'totalPrice'},
              { 'data': 'statusName' },



              ],


            });

            $('#ordersTable tbody').on( 'click', 'button', function (){
              var id = $(this).attr("data-id");

              if(id==1){
               var orderInfo = {
                orderID: $(this).val(),
                action: 3

              };

              $.ajax({
               type:"POST",
               url: "{{route('sales.getSetSales')}}",
               data: orderInfo,
               headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
               success: function (data){

                $("#orderList > tbody").empty();
                $("#orderSlipList > tbody").empty();
                $("#paymentList > tbody").empty();
                $("#addtlItem").empty();

                $("#remarks").prop("disabled",false);
                $("#remarks").val(data.sales.remarks);

                $("#hidSubtotal").val(data.sales.priceAfterVAT);
                $("#hidVat").val(data.sales.tax);
                $("#noOfGuests").val(data.sales.noOfGuests);
                $("#noOfSpecial").val(data.sales.noOfSpecial);
                $("#hidTotalPrice").val(data.sales.totalReceivables);
                $("#hidDiscount").val(data.sales.totalDiscounts);
                $("#hidDiscountId").val(data.sales.discountId);

                $("#discountChoice").val(data.sales.discountId);
                if(data.sales.discountId == 1 || data.sales.discountId == 2){
                  $("#discountTextArea").show();
                }
                else{
                  $("#discountTextArea").hide();
                }
                $("#hidPriceAfterDiscount").val(data.sales.priceAfterDiscount);
                $("#hidChange").val(data.sales.change);
                $("#hidStatus").val(data.sales.status);
                $("#hidSalesId").val(data.sales.id);
                $("#hidCashAmount").val(data.sales.cashAmount);

                $("#addCashTableButton").prop("disabled",false);
                $("#addCardTableButton").prop("disabled",false);

                $("#noOfGuests").prop("disabled",false);


                $("#printBill").prop("disabled",false);

                if(data.sales.status >= 2){
                  $("#printReceipt").prop("disabled",false);
                  $("#settleOrderToggle").prop("disabled",true);
                  $("#updateSales").prop("disabled",true);
                  $("#voidOrder").prop("disabled",true);
                }
                else{
                  if(data.sales.cashAmount != 0)
                    $("#settleOrderToggle").prop("disabled",false);
                  $("#voidOrder").prop("disabled",false);
                  $("#updateSales").prop("disabled",false);
                }

                if(data.sales.cashAmount == 0)
                  $("#settleOrderToggle").prop("disabled",true);
                $("#userLogin").html(data.sales.userName);


                $("#addtlItem").prop("disabled",false);
                $("#paidThru").prop("disabled",false);

                $("#applyDiscount").prop("disabled",false);

                $("#zeroRated").val(data.sales.zeroRated);
                $("#orderNo").val(data.sales.code);
                $("#printBill").val(data.sales.code);
                $("#osNo").prop("disabled",false);
                $("#osNo").val(data.sales.os_no);

                $("#bp").val(data.sales.bpId);
                $("#bp").prop("disabled",false);

                $("#table").prop("disabled",false);
                $("#table").val(data.sales.tableId);

                $("#waiter").prop("disabled",false);
                $("#waiter").val(data.sales.waiterId);

                $("#subtotal").html($.number(data.sales.priceAfterVAT,2));
                $("#vat").html($.number(data.sales.tax,2));
                $("#totalPrice").html($.number(data.sales.totalReceivables,2));
                $("#discount").html($.number(data.sales.totalDiscounts,2));
                $("#priceAfterDiscount").html($.number(data.sales.priceAfterDiscount,2));



                $("#change").html($.number(data.sales.change,2));
                $("#cashAmount").html($.number(data.sales.cashAmount,2));

                $("#discountName").html(data.sales.discountName);

                if($("#paidThru").val() == 1){
                  $("#cardNo").prop("disabled",true);
                  $("#cardTransactionNo").prop("disabled",true);
                }
                else{
                 $("#cardNo").prop("disabled",false);
                 $("#cardTransactionNo").prop("disabled",false);
               }

               $("#hidSalesId").val(data.sales.id);
               $("#hidStatus").val(data.sales.status);

               $("#orderDate").html(data.sales.date);
               if(data.sales.status == 1){
                $("#orderStatus").removeClass("label-info");
                $("#orderStatus").removeClass("label-default");

                $("#orderStatus").html("OPEN");
                $("#orderStatus").addClass("label label-primary");
              }
              else if(data.sales.status == 2){
                $("#orderStatus").removeClass("label-primary");
                $("#orderStatus").removeClass("label-default");

                $("#orderStatus").html("SETTLED");
                $("#orderStatus").addClass("label label-info");
              }
              if(data.sales.status == 3){
                $("#orderStatus").removeClass("label-info");
                $("#orderStatus").removeClass("label-primary");

                $("#orderStatus").html("VOID");
                $("#orderStatus").addClass("label label-default");
              }
              count++;
              console.log(data.salesOrders);
              for(var i = 0; i < data.salesOrders.length; i++){


               var itemQty='<input id="qty'+data.salesOrders[i]["id"]+'" qtyCount="'+count+'" pos="item'+data.salesOrders[i]["id"]+'" style="text-align: right;" type="text" class="form-control form-control-sm qtyItem" value="'+data.salesOrders[i]["qty"]+'" onkeyup="qtyChange('+data.salesOrders[i]["id"]+');" onkeydown="qtyChange('+data.salesOrders[i]["id"]+');"/>';

               var itemPrice='<span id="price'+data.salesOrders[i]["id"]+'">'+$.number(data.salesOrders[i]["price"],2)+'</span><input id="hidPrice'+data.salesOrders[i]["id"]+'" class="priceItem" pos2="itemPriceHid'+data.salesOrders[i]["inventoryMasterListId"]+'" type="hidden" disabled value="'+data.salesOrders[i]["price"]+'"/>';

               var itemTotalPrice='<span id="totalPriceText'+data.salesOrders[i]["id"]+'">'+$.number(data.salesOrders[i]["price"]*data.salesOrders[i]["qty"],2)+'</span><input id="hidTotalPrice'+data.salesOrders[i]["id"]+'" class="form-control-sm tpItem" data-check="'+data.salesOrders[i]["status"]+'" type="hidden" value="'+data.salesOrders[i]["price"]*data.salesOrders[i]["qty"]+'"/>';

               if(data.salesOrders[i]["free"] == 1){
                var itemFree = '<td><label class="switch switch-primary"><input class="switch-input freeItem" type="checkbox" checked id="itemFree'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["free"]+'" onclick="checkBoxFree('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
              }
              else{
                var itemFree = '<td><label class="switch switch-primary"><input class="switch-input freeItem" type="checkbox" id="itemFree'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["free"]+'" onclick="checkBoxFree('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
              }

              if(data.accountType == 1){
                if(data.salesOrders[i]["status"] == 1){
                  var itemVoid = '<td><label class="switch switch-primary"><input class="switch-input voidItem" type="checkbox" checked id="itemVoid'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["status"]+'" onclick="checkBoxCheck('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
                }
                else{
                  var itemVoid = '<td><label class="switch switch-primary"><input class="switch-input voidItem" type="checkbox" id="itemVoid'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["status"]+'" onclick="checkBoxCheck('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
                }
              }
              else{
                var itemVoid = "<td></td>";
              }


              $('#orderList > tbody:last-child').append('<tr width="20%" id="orderRow'+data.salesOrders[i]["id"]+'" class="rowCount"><td>'+data.salesOrders[i]["osCode"]+'<input type="hidden" class="selectedOS" value="'+data.salesOrders[i]["osCode"]+'"/></td><td width="40%"><input type="hidden" class="selectedItem" value="'+data.salesOrders[i]["id"]+'"/><input type="hidden" class="selectedInventoryMasterlistIDs" value="'+data.salesOrders[i]["imCode"]+'"/>'+data.salesOrders[i]["orderName"]+'</td><td>'+itemQty+'</td><td align="right">'+itemPrice+'</td><td align="right">'+itemTotalPrice+'</td>'+itemVoid+itemFree+'</tr>');
            }

            for(var a= 0; a < data.orderlists.length; a++){
              $("#orderSlipList > tbody:last-child").append('<tr><td>'+data.orderlists[a]["code"]+'</td><td><button class="btn btn-primary btn-sm" data-id="6" value="'+data.orderlists[a]["id"]+'"><span class="icon icon-print"></span>&nbsp; Print</button></td></tr>')

            }

            var addtlOptions = "<option value='0'>----Add Additional Order----</option>";

            for(var j = 0; j< data.menuItems.length; j++){
              addtlOptions+="<option value='"+data.menuItems[j]["id"]+"'>"+data.menuItems[j]["name"]+"</option>";
            }



            $("#addtlItem").html(addtlOptions);
            jQuery(".standardSelect").trigger("chosen:updated");

            for(var k = 0; k < data.payments.length; k++){

              if(data.payments[k]["type"] == 1){
                $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+data.payments[k]["id"]+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="1"/>'+data.payments[k]["name"]+'<input type="hidden" class="paymentIds" value="'+data.payments[k]["id"]+'"/></td><td><input class="form-control paymentTransactionNo" type="text" value="'+data.payments[k]["transactionNo"]+'"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();" value="'+data.payments[k]["amount"]+'"/></td><td><button data-id="5" class="btn btn-sm btn-primary" value="'+data.payments[k]["id"]+'" data-toggle="modal" data-target="#deletePaymentModal"><span class="icon icon-trash icon-sm"></span></td></tr>');
              }
              else if(data.payments[k]["type"] == 2){
                $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+data.payments[k]["id"]+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="2"/>'+data.payments[k]["name"]+'<input type="hidden" class="paymentIds" value="'+data.payments[k]["id"]+'"/></td><td><input class="form-control paymentTransactionNo" type="text" value="'+data.payments[k]["transactionNo"]+'"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();" value="'+data.payments[k]["amount"]+'"/></td><td><button data-id="5" class="btn btn-sm btn-primary" value="'+data.payments[k]["id"]+'" data-toggle="modal" data-target="#deletePaymentModal"><span class="icon icon-trash icon-sm"></span></td></tr>');
              }


            }



            console.log(data.menuItems);

         //   computeTotal();
         //   computeChange();

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
</div>
<div class="col-sm-6">
  <h4><strong>ORDER DETAILS</strong></h4>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-4">
          <h5>Date: <span id="orderDate"></span></h5>
        </div>
        <div class="col-md-4">
          <h5>Account: <span id="userLogin"></span></h5>
        </div>
        <div class="col-md-4">
          <h5>Status: <span id="orderStatus"></span></h5>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <label>Order No:</label>
          <input class="form-control" type="text" value="" disabled id="orderNo"/>
        </div>
        <div class="col-md-6">
          <label>Zero Rated:</label>
          <select class="form-control" id="zeroRated">
            <option value="0">NO</option>
            <option value="1">YES</option>
          </select>
        </div>
      </div><br/>
      <div class="row">
        <div class="col-md-6">
          <label>Customer:</label>
          <select id="bp" disabled class="form-control">

            @foreach($bps as $bp)
            <option value="{{$bp->id}}">{{$bp->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label>No of Guest/s:</label>
          <input class="form-control" type="text" value="" disabled id="noOfGuests" onkeyup="guestChange();" onkeydown="guestChange();" />
        </div>
      </div><br/>
      <div class="row">
        <div class="col-md-6">
          <label>Table:</label>
          <select id="table" disabled class="form-control">
            <option value="0">---Select Table---</option>
            @foreach($tables as $t)
            <option value="{{$t->id}}">{{$t->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label>Server:</label>
          <select id="waiter" disabled class="form-control">
            <option value="0">---Select Waiter---</option>
            @foreach($users as $u)
            <option value="{{$u->id}}">{{$u->name}}</option>
            @endforeach
          </select>
        </div>
      </div><br/>
      <div class="row">
        <div class="col-md-12">
          <label>Remarks</label>
          <textarea id="remarks" class="form-control" disabled rows="3"></textarea>
        </div>
      </div>
      <hr/>
      <div class="row">
        <div class="col-md-12">
          <label>Add Additional Menu</label>
          <select id="addtlItem" disabled accesskey="s" data-placeholder="Add Additional Order" class="standardSelect" tabindex="1" onchange="itemSelect();">
            <option value=""></option>
            @foreach($menuItems as $mi)
            @if($mi->id != 0)
            <option value="{{$mi->id}}">{{$mi->name}}</option>
            @endif
            @endforeach
          </select>
        </div>
      </div><br/>
      <table id="orderList" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="15%">OS No.</th>
            <th>Menu Item</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total Price</th>
            <th>Void Item</th>
            <th>Free Item</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <br/>
      <div class="row">
       <div class="col-md-12">
        <table width="100%">
          <tbody>
            <input type="hidden" id="hidSubtotal"/>
            <input type="hidden" id="hidVat"/>
            <input type="hidden" id="hidTotalPrice"/>
            <input type="hidden" id="hidDiscount" value="0" />
            <input type="hidden" id="hidDiscountId" value="0"/>
            <input type="hidden" id="hidPriceAfterDiscount"/>
            <input type="hidden" id="hidChange"/>
            <input type="hidden" id="hidCashAmount"/>
            <input type="hidden" id="hidStatus"/>
            <input type="hidden" id="hidSalesId"/>
            <tr>
              <td><p>SubTotal</p></td>
              <td align="right"><span style="text-align: right;" id="subtotal"></span></td>
            </tr>
            <tr>
              <td><p>VAT (12%)</p></td>
              <td align="right"><span style="text-align: right;" id="vat"></span></td>
            </tr>
            <tr>
              <td colspan="2"><hr style="margin-top:5px;margin-bottom:5px;"/></td>
            </tr>
            <tr>
              <td><h5>TOTAL</h5></td>
              <td align="right"><span style="text-align:right;" id="totalPrice"></span></td>
            </tr>
            <tr>
              <td><h5>Discount:</h5>
                <select id="discountChoice" class="form-control" style="width:250px;" onchange="discountToggle();">
                  <option value="0">---No Discount---</option>
                  @foreach($discounts as $d)
                  <option value="{{$d->id}}">{{$d->name}}</option>
                  @endforeach
                </select>
                <div id="discountTextArea">
                  <div class="row gutter-xs">

                    <div class="col-sm-12" style="width:250px;">
                      <h6>No. of Seniors/PWD</h6>
                      <input type="text" id="noOfSpecial"  onkeydown = "computeDiscount();" onkeyup="computeDiscount();" class="form-control"/>
                    </div>
                  </div><br/>
                </div>
              </td>
              <td align="right">(<span style="text-align:right;" id="discount"></span>)</td>
            </tr>
            <tr>
              <td><h5><strong>GRAND TOTAL</strong></h5></td>
              <td align="right">&#8369; <span style="text-align:right;" id="priceAfterDiscount"></span></td>
            </tr>


          </tbody>
        </table>
        <hr style="margin-top:5px;margin-bottom:5px;"/>
        <label>PAYMENTS</label>&nbsp;<button id="addCashTableButton" class="btn btn-sm btn-info" disabled><span class="icon icon-money icon-sm"></span>&nbsp; Add Cash Payment</button>&nbsp;<button id="addCardTableButton" class="btn btn-sm btn-info" disabled><span class="icon icon-credit-card icon-sm"></span>&nbsp; Add Card Payment</button>
        <br/><br/>
        <table id="paymentList" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Type</th>
              <th>Transaction No.</th>
              <th>Amount</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table><br/>
        <hr style="margin-top:5px;margin-bottom:5px;"/>
        <label>Order Slip List</label>
        <table id="orderSlipList" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>OS No</th>
              <th></th>
            </thead>
            <tbody>
            </tbody>
          </table>
          <div style="float:right;">
           <h4 style="margin-top:5px;text-align: right;">Total Cash: <span id="cashAmount"></span></h4>
           <h4 style="margin-top:5px;text-align: right;">Change: <span id="change"></span></h4>
         </div>

       </div>
     </div><br/>

     <div class="row">

      <div class="col-md-12">
        <button class="btn btn-lg btn-block btn-primary" disabled id="updateSales">UPDATE</button>
      </div>
    </div><br/>
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-lg btn-block btn-default" disabled id="printBill"><span class="icon icon-print"></span>&nbsp; PRINT BILL</button>
      </div>
      <div class="col-md-6">
        <button class="btn btn-lg btn-block btn-info" disabled id="printReceipt"><span class="icon icon-print"></span>&nbsp; PRINT TEMP RECEIPT</button>
      </div>
    </div><br/>
    <div class="row">

      <div class="col-md-12">
        <button class="btn btn-lg btn-block btn-primary" disabled id="settleOrderToggle">SETTLE ORDER</button>
      </div>
    </div><br/>
    @if(Auth::user()->type == 1)
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-lg btn-block btn-default" id="voidOrder" data-toggle="modal" data-target="#voidOrderModal" disabled>VOID ORDER</button>
      </div>
    </div>
    @endif
  </div>
</div>
</div>
</div>

<script>

  function computeChange(){
    var paymentTotal=0;

    $(".form-control.paymentAmount").each(function() {
            //add only if the value is number
            var tempAmount = 0;

            if(this.value.length != 0)
             tempAmount = this.value;
           else
            tempAmount = 0;


          paymentTotal += parseFloat(tempAmount);

          

        });


    var total = parseFloat($("#hidPriceAfterDiscount").val());

    $("#change").html(paymentTotal-total);
    $("#hidChange").val(paymentTotal-total);

    $("#cashAmount").html(paymentTotal);
    $("#hidCashAmount").val(paymentTotal);

    $("#cashAmount").number(true,2);
    $("#change").number(true,2);
  }

  function discountToggle(){
    var d = $("#discountChoice").val();

    if(d != 0){
      $("#hidDiscountId").val(d);

      if(d == 1 || d == 2){
        $("#discountTextArea").show();
      }
      else{
        $("#noOfSenior").val('');
        $("#noOfGuest").val(1);
        $("#discountTextArea").hide();
      }

      computeDiscount();
    }
    else{
      $("#hidDiscount").val(0);

      $("#discount").html(0);
      $("#discount").number(true, 2);


      $("#hidDiscountId").val(0);

      computeTotal();
    }
    
    computeChange();
  }

  function paidThruToggle(){

    var p = $("#paidThru").val();

    if(p == 1){
      $("#cardNo").prop("disabled",true);
      $("#cardTransactionNo").prop("disabled",true);
      $("#cardNo").val('');
      $("#cardTransactionNo").val('');
    }
    else{
      $("#cardNo").prop("disabled",false);
      $("#cardTransactionNo").prop("disabled",false);
    }
  }

  function computeDiscount(){
    computeTotal();
    var d = $("#hidDiscountId").val();
    var total = parseFloat($("#hidSubtotal").val());
    var otherTotal = parseFloat($("#hidTotalPrice").val());

    var discountInfo = {
      dId: d,
      action: 6
    };

    $.ajax({
     type:"POST",
     url: "{{route('sales.getSetSales')}}",
     data: discountInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){
      console.log(data);


      if($("#noOfSpecial").val() == "")
        noOfSenior = 0;
      else
        noOfSenior = parseFloat($("#noOfSpecial").val());

      if($("#noOfGuests").val() == "")
        noOfGuest = 1;
      else
        noOfGuest = parseFloat($("#noOfGuests").val());

      if(data.id == 1 || data.id ==2){

       var discount =   ((total / noOfGuest) *  noOfSenior) * data.discountValue;
       if(discount == "")
        discount = 0;

      $("#totalDiscount").html(discount);
      $("#hidTotalDiscount").val(discount);
      $("#totalDiscount").number(true,2);
    }
    else
    {
      var discount = otherTotal * data.discountValue;
      $("#totalDiscount").html(discount);
      $("#hidTotalDiscount").val(discount);
      $("#totalDiscount").number(true,2);
    }

    $("#hidDiscount").val($("#hidTotalDiscount").val());

    $("#discount").html($("#hidTotalDiscount").val());
    $("#discount").number(true, 2);


    $("#hidDiscountId").val($("#discountChoice").val());

    if(d == 0){
      $("#hidDiscount").val(0);

    }
    computeTotal();

    computeChange();
  }
});






    
  }

  function computeTotal(){
    var total=0;

    $(".form-control-sm.tpItem").each(function() {
            //add only if the value is number
            var tempAmount = 0;

            if(this.value.length != 0)
             tempAmount = this.value;
           else
            tempAmount = 0;

          if($(this).attr("data-check") == 0)
            total += parseFloat(tempAmount);

          

        });

    $("#hidTotalPrice").val(total);
    $("#totalPrice").html(total);
    $("#totalPrice").number(true, 2);


    $("#hidSubtotal").val(total/1.12);
    $("#subtotal").html(total/1.12);
    $("#subtotal").number(true, 2);

    $("#hidVat").val(total-(total/1.12));
    $("#vat").html(total-(total/1.12));
    $("#vat").number(true, 2);

    $("#hidPriceAfterDiscount").val(total-parseFloat($("#hidDiscount").val()));
    $("#priceAfterDiscount").html(total-parseFloat($("#hidDiscount").val()));
    $("#priceAfterDiscount").number(true, 2);


    computeChange();
    return total;
  }

  function qtyChange(x){

    var mult = parseFloat($("#hidPrice"+x).val()) * parseFloat($("#qty"+x).val());
    $("#hidTotalPrice"+x).val(mult);
    $("#totalPriceText"+x).html(mult);
    $("#totalPriceText"+x).number(true,2);


    computeTotal();
    computeDiscount();
    computeTotal();
    computeChange();

  }

  function guestChange(){
    computeTotal();
    computeDiscount();
    computeTotal();
    computeChange();
  }
  function qtyChangeNew(x){

    var mult = parseFloat($("#hidPriceNew"+x).val()) * parseFloat($("#qtyNew"+x).val());
    $("#hidTotalPriceNew"+x).val(mult);
    $("#totalPriceTextNew"+x).html(mult);
    $("#totalPriceTextNew"+x).number(true,2);

    
    computeTotal();
    computeDiscount();
    computeTotal();
    computeChange();

  }

  function checkBoxCheck(x){

    var sdInfo = {
      sdId: x,
      sdIsVoid: $("#itemVoid"+x).attr("data-check"),

      action: 4

    };

    $.ajax({
     type:"POST",
     url: "{{route('sales.getSetSales')}}",
     data: sdInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){

      $("#itemVoid"+data.id).attr("data-check",data.check);
      $("#hidTotalPrice"+data.id).attr("data-check",data.check);

      var title   = 'Success!',
      message = data.message,
      type    = 'success',
      options = {};

      toastr[type](message, title, options);

      computeDiscount();
      computeTotal();
      computeChange();
    }
  });

  }

  function checkBoxFree(x){

    var sdInfo = {
      sdId: x,
      sdIsFree: $("#itemFree"+x).attr("data-check"),
      action: 13
    };

    $.ajax({
     type:"POST",
     url: "{{route('sales.getSetSales')}}",
     data: sdInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){

      $("#itemFree"+data.id).attr("data-check",data.check);
      $("#hidTotalPrice"+data.id).attr("data-check",data.check);

      var title   = 'Success!',
      message = data.message,
      type    = 'success',
      options = {};

      toastr[type](message, title, options);

      computeDiscount();
      computeTotal();
      computeChange();
    }
  });

  }


  function itemSelect(){

    var masterlistIDs= [];

    var invIds = $('#orderList').find('.selectedInventoryMasterlistIDs');

    invIds.each(function(){
      masterlistIDs.push(this.value);
      
    });


    var orderInfo = {
      miID: $("#addtlItem").val(),
      masterlistIDs: masterlistIDs,
      discountId : $("#hidDiscountId").val(),
      action: 5

    };

    $.ajax({
     type:"POST",
     url: "{{route('sales.getSetSales')}}",
     data: orderInfo,
     headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
     success: function (data){


      var addtlOptions = "<option value='0'>----Add Additional Order----</option>";

      for(var j = 0; j< data.menuItems.length; j++){
        addtlOptions+="<option value='"+data.menuItems[j]["id"]+"'>"+data.menuItems[j]["name"]+"</option>";
      }

      $("#addtlItem").html(addtlOptions);
      jQuery(".standardSelect").trigger("chosen:updated");

      var itemQty='<input id="qtyNew'+count+'" qtyCount="'+count+'" pos="item'+count+'" style="text-align: right;" type="text" class="form-control form-control-sm qtyItem" value="1" onkeyup="qtyChangeNew('+count+');" onkeydown="qtyChangeNew('+count+');"/>';

      var itemPrice='<span id="price'+count+'">'+$.number(data.menuItem.sellingPrice,2)+'</span><input id="hidPriceNew'+count+'" class="priceItem" pos2="itemPriceHid'+count+'" type="hidden" disabled value="'+data.menuItem.sellingPrice+'"/>';

      var itemTotalPrice='<span id="totalPriceTextNew'+count+'">'+$.number(data.menuItem.sellingPrice,2)+'</span><input id="hidTotalPriceNew'+count+'" class="form-control-sm tpItem" data-check="0" type="hidden" value="'+data.menuItem.sellingPrice+'"/>';

      var itemVoid = '<td><button class="btn btn-sm btn-primary" value="'+data.menuItem.menuItemId+'" data-id="5"><span class="icon icon-trash"></span></button></td>';

      var itemFree = '<td></td>';


      $('#orderList > tbody:last-child').append('<tr id="orderRowNew'+data.menuItem.id+'" class="rowCount"><td><input type="text" class="form-control selectedOS" value="OS-'+data.osCount+'"/></td><td width="40%"><input type="hidden" class="selectedItem" value="0"/><input type="hidden" class="selectedInventoryMasterlistIDs" value="'+data.menuItem.code+'"/>'+data.menuItem.name+'</td><td>'+itemQty+'</td><td align="right">'+itemPrice+'</td><td align="right">'+itemTotalPrice+'</td>'+itemVoid+itemFree+'</tr>');

      count++

      $("#hidDiscount").val(0);
      $("#discount").html('0.00')
      $("#discountName").html('');

      computeTotal();

      computeChange();
    }


  });
  }

  $(document).ready(function() {

    $("#discountTextArea").hide();
    $("#settleText").hide();

    var $itemSelect = $('#addtlItem');
    $itemSelect.select2({ dir: 'ltr' });

    $("#applyDiscount").click(function(e){
      // $("#discountChoice").val(0);
      // $("#totalDiscount").html(0.00);
      // $("#hidTotalDiscount").val(0);
      // $("#noOfGuest").val(1);
      // $("#noOfSenior").val('');
      // $("#discountTextArea").hide();
    });

    $("#addCashTableButton").click(function(e){

      $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+paymentRowCount+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="1"/>CASH<input type="hidden" class="paymentIds" value="0"/></td><td><input class="form-control paymentTransactionNo" type="text"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();"/></td><td><button id="delRowPayment" data-id="4" class="btn btn-sm btn-primary" value="'+paymentRowCount+'"><span class="icon icon-trash icon-sm"></span></td></tr>');

      paymentRowCount++;
    });

    $("#addCardTableButton").click(function(e){

      $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+paymentRowCount+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="2"/>CARD<input type="hidden" class="paymentIds" value="0"/></td><td><input class="form-control paymentTransactionNo" type="text"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();"/></td><td><button id="delRowPayment" data-id="4" class="btn btn-sm btn-primary" value="'+paymentRowCount+'"><span class="icon icon-trash icon-sm"></span></td></tr>');

      paymentRowCount++;
    });

    $("#sortOrderList").click(function(e){
      $('#ordersTable').DataTable({

        ajax: "/dataTablesOrderList/"+$("#dateStart").val()+"/"+$("#dateEnd").val(),
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'bDestroy'    :true,
        "order": [[ 0, "asc" ]],
        'columns': [
        { 
          "className": 'options',
          "data":    null,
          "render": function(data, type, full, meta){
            var valueHere=data.id;
            if(data.status == 1){
              return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
            }
            else if(data.status == 2){
              return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
            }
            else if(data.status == 3){
              return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-default" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
            }

          }

        },
        { 'data': 'orderNo' },
        { 'data': 'date' },
        { 'data': 'customer' },

        { 'data': 'totalPrice'},
        { 'data': 'statusName' },



        ],


      });
    });

    $("#orderSlipList tbody").on('click','button', function(){
      var id = $(this).attr("data-id");

      if(id== 6){

        $.ajax({
         type:"GET",
         url: "/print-os/"+$(this).val(),

         headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
         success: function (data){


         }

       });


      }
    })

    $('#paymentList tbody').on( 'click', 'button', function (){
      var id = $(this).attr("data-id");

      if(id==4){

        var row = $(this).val();

        $("#paymentRow"+row).remove();

        computeChange();


      }

      if(id==5){
        $("#continueDeletePayment").val($(this).val());
      }

    });

    $("#continueDeletePayment").click(function(e){
      var id = $(this).val();

      var paymentInfo = {
        pId: id,
        action: 8
      };

      $.ajax({
        type:"POST",
        url: "{{route('sales.getSetSales')}}",
        data: paymentInfo,
        headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
        success: function (data){
         console.log(data);

         $("#paymentList > tbody").empty();
         for(var k = 0; k < data.payments.length; k++){

          if(data.payments[k]["type"] == 1){
            $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+data.payments[k]["id"]+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="1"/>'+data.payments[k]["name"]+'<input type="hidden" class="paymentIds" value="'+data.payments[k]["id"]+'"/></td><td><input class="form-control paymentTransactionNo" type="text" value="'+data.payments[k]["transactionNo"]+'"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();" value="'+data.payments[k]["amount"]+'"/></td><td><button data-id="5" class="btn btn-sm btn-primary" value="'+data.payments[k]["id"]+'" data-toggle="modal" data-target="#deletePaymentModal"><span class="icon icon-trash icon-sm"></span></td></tr>');
          }
          else if(data.payments[k]["type"] == 2){
            $('#paymentList > tbody:last-child').append('<tr id="paymentRow'+data.payments[k]["id"]+'" class="rowCount"><td width="40%"><input type="hidden" class="paymentType" value="2"/>'+data.payments[k]["name"]+'<input type="hidden" class="paymentIds" value="'+data.payments[k]["id"]+'"/></td><td><input class="form-control paymentTransactionNo" type="text" value="'+data.payments[k]["transactionNo"]+'"/></td><td align="right"><input class="form-control paymentAmount" type="number" onkeyup="computeChange();" onkeydown="computeChange();" value="'+data.payments[k]["amount"]+'"/></td><td><button data-id="5" class="btn btn-sm btn-primary" value="'+data.payments[k]["id"]+'" data-toggle="modal" data-target="#deletePaymentModal"><span class="icon icon-trash icon-sm"></span></td></tr>');
          }


        }
        computeChange();
      }
    })
    });


    $('#orderList tbody').on( 'click', 'button', function (){
      var id = $(this).attr("data-id");

      if(id==5){

        var itemInfo = {
          miID: $(this).val(),
          action: 1,
        };

        $.ajax({
         type:"POST",
         url: "{{route('sales.getSetSales')}}",
         data: itemInfo,
         headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
         success: function (data){
           console.log(data);
           $("#addtlItem").append("<option value='"+data.id+"'>"+data.name+"</option>");

           jQuery(".standardSelect").trigger("chosen:updated");

           $("#orderRowNew"+data.id).remove();

           $("#hidDiscount").val(0);
           $("#discount").html('0.00')
           $("#discountName").html('');

           computeTotal();
         }
       });



      }

    });

//  $("#resellerPrice").hide();

$("#confirmDiscount").click(function(e){
  $("#hidDiscount").val($("#hidTotalDiscount").val());

  $("#discount").html($("#hidTotalDiscount").val());
  $("#discount").number(true, 2);


  $("#hidDiscountId").val($("#discountChoice").val());

  if($("#discountChoice").val() != 0)
    $("#discountName").html($("#discountChoice option:selected").text());
  else
    $("#discountName").html('');



  computeTotal();

});

$("#settleOrder").click(function(e){
  var salesInfo = {
    salesId: $("#hidSalesId").val(),
    action: 9,
  }

  $.ajax({
   type:"POST",
   url: "{{route('sales.getSetSales')}}",
   data: salesInfo,
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){

    console.log(data);
    
    var title   = 'Success!',
    message = 'Order Settled for '+data.code,
    type    = 'success',
    options = {};
    toastr[type](message, title, options);


    $("#printReceipt").prop("disabled",false);
    $("#settleOrderToggle").prop("disabled",true);
    $("#updateSales").prop("disabled",true);

    if(data.status == 1){
      $("#orderStatus").removeClass("label-info");
      $("#orderStatus").removeClass("label-default");

      $("#orderStatus").html("OPEN");
      $("#orderStatus").addClass("label label-primary");
    }
    else if(data.status == 2){
      $("#orderStatus").removeClass("label-primary");
      $("#orderStatus").removeClass("label-default");

      $("#orderStatus").html("SETTLED");
      $("#orderStatus").addClass("label label-info");
    }
    if(data.status == 3){
      $("#orderStatus").removeClass("label-info");
      $("#orderStatus").removeClass("label-primary");

      $("#orderStatus").html("VOID");
      $("#orderStatus").addClass("label label-default");


      $("#printReceipt").prop("disabled",false);
      $("#settleOrderToggle").prop("disabled",true);

    }


    $('#ordersTable').DataTable({

      ajax: "/dataTablesOrderList/"+$("#dateStart").val()+"/"+$("#dateEnd").val(),
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      'bDestroy'    :true,
      "order": [[ 0, "asc" ]],
      'columns': [
      { 
        "className": 'options',
        "data":    null,
        "render": function(data, type, full, meta){
          var valueHere=data.id;
          if(data.status == 1){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
          }
          else if(data.status == 2){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
          }
          else if(data.status == 3){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-default" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
          }

        }

      },
      { 'data': 'orderNo' },
      { 'data': 'date' },
      { 'data': 'customer' },

      { 'data': 'totalPrice'},
      { 'data': 'statusName' },

      ],

    });

  }
});


});

$("#settleOrderToggle").click(function(e){



  if($("#hidCashAmount").val() != 0){
    $("#settleOrderModal").modal("show");
  }
  else{
   var title   = 'Error!',
   message = 'No payments made for this order!',
   type    = 'error',
   options = {};
   toastr[type](message, title, options);
 }

});

$("#voidOrderButton").click(function(e){
  var salesInfo = {
    salesId: $("#hidSalesId").val(),
    action: 10,
  }

  $.ajax({
   type:"POST",
   url: "{{route('sales.getSetSales')}}",
   data: salesInfo,
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){

    console.log(data);
    var title   = 'Success!',
    message = 'Order Voided for '+data.code,
    type    = 'success',
    options = {};
    toastr[type](message, title, options);


    // $("#printReceipt").prop("disabled",false);
    $("#settleOrderToggle").prop("disabled",true);
    $("#updateSales").prop("disabled",true);

    if(data.status == 1){
      $("#orderStatus").removeClass("label-info");
      $("#orderStatus").removeClass("label-default");

      $("#orderStatus").html("OPEN");
      $("#orderStatus").addClass("label label-primary");
    }
    else if(data.status == 2){
      $("#orderStatus").removeClass("label-primary");
      $("#orderStatus").removeClass("label-default");

      $("#orderStatus").html("SETTLED");
      $("#orderStatus").addClass("label label-info");
    }
    if(data.status == 3){

      $("#orderStatus").removeClass("label-info");
      $("#orderStatus").removeClass("label-primary");

      $("#orderStatus").html("VOID");
      $("#orderStatus").addClass("label label-default");


      $("#printReceipt").prop("disabled",false);
      $("#settleOrderToggle").prop("disabled",true);
      $("#printBill").prop("disabled",true);
      $("#updateSales").prop("disabled",true);
      $("#voidOrder").prop("disabled",true);

    }


    $('#ordersTable').DataTable({

      ajax: "/dataTablesOrderList/"+$("#dateStart").val()+"/"+$("#dateEnd").val(),
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      'bDestroy'    :true,
      "order": [[ 0, "asc" ]],
      'columns': [
      { 
        "className": 'options',
        "data":    null,
        "render": function(data, type, full, meta){
          var valueHere=data.id;
          if(data.status == 1){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
          }
          else if(data.status == 2){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
          }
          else if(data.status == 3){
            return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-default" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
          }

        }

      },
      { 'data': 'orderNo' },
      { 'data': 'date' },
      { 'data': 'customer' },

      { 'data': 'totalPrice'},
      { 'data': 'statusName' },

      ],

    });

  }
});


});

$("#updateSales").click(function(e){


  $("#updateSales").prop("disabled",true);

  var soId= [];
  var qty = [];
  var price = [];
  var masterlistId = [];
  var osId = [];

  var paymentIds = [9999];
  var paymentTypes = [9999];
  var paymentTransactionNo = [9999];
  var paymentAmount = [9999];

  var soIds = $('#orderList').find('.selectedItem');
  var masterlistIDs = $("#orderList").find('.selectedInventoryMasterlistIDs');
  var qties = $('#orderList').find('.qtyItem');
  var prices = $('#orderList').find('.priceItem');
  var osIds = $("#orderList").find('.selectedOS');

  var payIds = $('#paymentList').find('.paymentIds');
  var payTypes = $('#paymentList').find('.paymentType');
  var payTrans = $('#paymentList').find('.paymentTransactionNo');
  var payAmount = $('#paymentList').find('.paymentAmount');

  masterlistIDs.each(function(){
    masterlistId.push(this.value);
  });

  soIds.each(function(){
    soId.push(this.value);
  });

  qties.each(function(){
    qty.push(this.value);
  });

  osIds.each(function(){
    osId.push(this.value);
  });

  prices.each(function(){
    price.push(this.value);
  });

  payIds.each(function(){
    paymentIds.push(this.value);
  });

  payTypes.each(function(){
    paymentTypes.push(this.value);
  })

  payTrans.each(function(){
    paymentTransactionNo.push(this.value);
  });

  payAmount.each(function(){
    paymentAmount.push(this.value);
  });


  var salesInfo = {
    masterlistId: masterlistId,
    soId: soId,
    qty: qty,
    osId: osId,
    price: price,
    remarks: $("#remarks").val(),
    paymentIds: paymentIds,
    paymentTransactionNo: paymentTransactionNo,
    paymentTypes: paymentTypes,
    paymentAmount: paymentAmount,
    orderNo: $("#orderNo").val(),
    zeroRated: $("#zeroRated").val(),
    osNo: $("#osNo").val(),
    bp: $("#bp").val(),
    table: $("#table").val(),
    waiter: $("#waiter").val(),
    subtotal: $("#hidSubtotal").val(),
    tax: $("#hidVat").val(),
    priceAfterVat: $("#hidTotalPrice").val(),
    discountAmount : $("#hidDiscount").val(),
    discountId: $("#hidDiscountId").val(),
    priceAfterDiscount: $("#hidPriceAfterDiscount").val(),
    change: $("#hidChange").val(),
    paidThru: $("#paidThru").val(),
    cardNo: $("#cardNo").val(),
    cardTransactionNo: $("#cardTransactionNo").val(),
    cashAmount: $("#hidCashAmount").val(),
    status: $("#hidStatus").val(),
    noOfGuests: $("#noOfGuests").val(),
    noOfSpecial: $("#noOfSpecial").val(),

    //  total
    salesId: $("#hidSalesId").val(),
    action: 7
  };


  $.ajax({
   type:"POST",
   url: "{{route('sales.getSetSales')}}",
   data: salesInfo,
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){


    $("#updateSales").prop("enabled",true);
    var title   = 'Success!',
    message = 'Order Updated for '+data.code,
    type    = 'success',
    options = {};

    toastr[type](message, title, options);

    console.log(data);

    $("#orderList > tbody").empty();
    $("#orderSlipList > tbody").empty();

    for(var i = 0; i < data.salesOrders.length; i++){


     var itemQty='<input id="qty'+data.salesOrders[i]["id"]+'" qtyCount="'+count+'" pos="item'+data.salesOrders[i]["id"]+'" style="text-align: right;" type="text" class="form-control form-control-sm qtyItem" value="'+data.salesOrders[i]["qty"]+'" onkeyup="qtyChange('+data.salesOrders[i]["id"]+');" onkeydown="qtyChange('+data.salesOrders[i]["id"]+');"/>';

     var itemPrice='<span id="price'+data.salesOrders[i]["id"]+'">'+$.number(data.salesOrders[i]["price"],2)+'</span><input id="hidPrice'+data.salesOrders[i]["id"]+'" class="priceItem" pos2="itemPriceHid'+data.salesOrders[i]["inventoryMasterListId"]+'" type="hidden" disabled value="'+data.salesOrders[i]["price"]+'"/>';

     var itemTotalPrice='<span id="totalPriceText'+data.salesOrders[i]["id"]+'">'+$.number(data.salesOrders[i]["price"]*data.salesOrders[i]["qty"],2)+'</span><input id="hidTotalPrice'+data.salesOrders[i]["id"]+'" class="form-control-sm tpItem" data-check="'+data.salesOrders[i]["status"]+'" type="hidden" value="'+data.salesOrders[i]["price"]*data.salesOrders[i]["qty"]+'"/>';

     if(data.salesOrders[i]["free"] == 1){
      var itemFree = '<td><label class="switch switch-primary"><input class="switch-input freeItem" type="checkbox" checked id="itemFree'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["free"]+'" onclick="checkBoxFree('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
    }
    else{
      var itemFree = '<td><label class="switch switch-primary"><input class="switch-input freeItem" type="checkbox" id="itemFree'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["free"]+'" onclick="checkBoxFree('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
    }

    if(data.accountType == 1){
      if(data.salesOrders[i]["status"] == 1){
        var itemVoid = '<td><label class="switch switch-primary"><input class="switch-input voidItem" type="checkbox" checked id="itemVoid'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["status"]+'" onclick="checkBoxCheck('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
      }
      else{
        var itemVoid = '<td><label class="switch switch-primary"><input class="switch-input voidItem" type="checkbox" id="itemVoid'+data.salesOrders[i]["id"]+'" data-check="'+data.salesOrders[i]["status"]+'" onclick="checkBoxCheck('+data.salesOrders[i]["id"]+');"><span class="switch-track"></span><span class="switch-thumb"></span></label></td>';
      }
    }
    else{
      var itemVoid = "<td></td>";
    }


    $('#orderList > tbody:last-child').append('<tr width="20%" id="orderRow'+data.salesOrders[i]["id"]+'" class="rowCount"><td>'+data.salesOrders[i]["osCode"]+'<input type="hidden" class="selectedOS" value="'+data.salesOrders[i]["osCode"]+'"/></td><td width="40%"><input type="hidden" class="selectedItem" value="'+data.salesOrders[i]["id"]+'"/><input type="hidden" class="selectedInventoryMasterlistIDs" value="'+data.salesOrders[i]["imCode"]+'"/>'+data.salesOrders[i]["orderName"]+'</td><td>'+itemQty+'</td><td align="right">'+itemPrice+'</td><td align="right">'+itemTotalPrice+'</td>'+itemVoid+itemFree+'</tr>');
  }

  for(var a= 0; a < data.orderlists.length; a++){
    $("#orderSlipList > tbody:last-child").append('<tr><td>'+data.orderlists[a]["code"]+'</td><td><button class="btn btn-primary btn-sm" data-id="6" value="'+data.orderlists[a]["id"]+'"><span class="icon icon-print"></span>&nbsp; Print</button></td></tr>')

  }

  $("#updateSales").prop("disabled",false);

  if(data.sales.cashAmount != 0)
    $("#settleOrderToggle").prop("disabled",false);

  $('#ordersTable').DataTable({

    ajax: "/dataTablesOrderList/"+$("#dateStart").val()+"/"+$("#dateEnd").val(),
    'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : true,
    'bDestroy'    :true,
    "order": [[ 0, "asc" ]],
    'columns': [
    { 
      "className": 'options',
      "data":    null,
      "render": function(data, type, full, meta){
        var valueHere=data.id;
        if(data.status == 1){
          return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
        }
        else if(data.status == 2){
          return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
        }
        else if(data.status == 3){
          return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-default" data-id="1" value="'+valueHere+'"><i class="icon icon-eye"></i></button>';
        }

      }

    },
    { 'data': 'orderNo' },
    { 'data': 'date' },
    { 'data': 'customer' },

    { 'data': 'totalPrice'},
    { 'data': 'statusName' },



    ],


  });


}

});

});

$("#printBill").click(function(e){
  $.ajax({
   type:"GET",
   url: "/print-bill/"+$("#hidSalesId").val(),
   
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){


   }

 });
});


$("#printReceipt").click(function(e){
  $.ajax({
   type:"GET",
   url: "/print-receipt/"+$("#hidSalesId").val(),
   
   headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
   success: function (data){


   }

 });
});

});
</script>
@endsection