@extends('layouts.main')

@section('modal')

<div id="successModalAlertdb" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="card">
        <div class="card-header">
          SALES DETAILS <button id="sCode" class="label label-outline-info" ></button> - <button id="bpSaleDetails" class="label label-outline-success" ></button>
        </div>
        <div class="card-body">
          <table id="salesDetailsList" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th width='10%'>Qty</th>
                <th width='10%'>Price</th>
                <th>Discount</th>
                <th width='10%'>Total</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          <div class="col-md-3" style="float:right;" >
            <!-- <button  class="form-control btn btn-danger">Cancel</button> -->
            <button id="cancelGE" class="btn btn-danger" data-toggle="modal" data-target="#successModalAlertzzz" data-dismiss="modal" type="button">Cancel</button>
          </div>
          <div class="col-md-3" style="float:right;" >
            <input style="text-align:right;" id="totalRec" class="form-control" disabled=""><p><i style="color:green;">Total</i></p>
            <input style="display: none;" id="goodsEntryId" class="form-control" disabled="">
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">DASHBOARD</span>
	</h1>
</div>

<div class="card">
  <div class="card-header">
    <b>DAILY SALES INFORMATION</b> 
  </div>
  <div class="card-body">

    <div class="row gutter-xs">
      <div class="col-md-6 col-lg-3 col-lg-push-0">
        <div class="card">
          <div class="card-body">
            <div class="media">
              <div class="media-middle media-left">
                <span class="bg-primary circle sq-48">
                  <span class="icon icon-user"></span>
                </span>
              </div>
              <div class="media-middle media-body">
                <h6 class="media-heading">Visitors</h6>
                <h3 class="media-heading">
                  <span  class="fw-l">1,031,760</span>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-lg-push-3">
        <div class="card">
          <div class="card-body">
            <div class="media">
              <div class="media-middle media-left">
                <span class="bg-danger circle sq-48">
                  <span class="icon icon-shopping-bag"></span>
                </span>
              </div>
              <div class="media-middle media-body">
                <h6 class="media-heading">Total Invoice</h6>
                <h3 class="media-heading">
                  <span id="totalInvoices" class="fw-l"></span>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-lg-pull-3">
        <div class="card">
          <div class="card-body">
            <div class="media">
              <div class="media-middle media-left">
                <span class="bg-primary circle sq-48">
                  <span class="icon icon-clock-o"></span>
                </span>
              </div>
              <div class="media-middle media-body">
                <h6 class="media-heading">Average Duration</h6>
                <h3 class="media-heading">
                  <span class="fw-l">00:07:56</span>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-lg-pull-0">
        <div class="card">
          <div class="card-body">
            <div class="media">
              <div class="media-middle media-left">
                <span class="bg-danger circle sq-48">
                  <span class="icon icon-php">&#8369;</span>
                </span>
              </div>
              <div class="media-middle media-body">
                <h6 class="media-heading">Total Sales</h6>
                <h3 class="media-heading">
                  <span id="totalSales" class="fw-l"></span>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


<div class="card">
  <div class="card-header">
    <i class="icon icon-list-ul"></i><b> SALES LIST</b>
  </div>
  <div class="card-body">
    <table id="saleData" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="5%">OS #</th>
          <th width="5%">Customer</th>
          <th width="5%">User</th>
          <th width="5%">Discount Type</th>
          <!-- <th>Address</th> -->
          <th width="5%">Total Amount</th>
          <th width="5%">Date</th>
          <th width="5%"></th>
          
        </tr>
      </thead>
      <tbody>

        <script>

          $('#saleData').DataTable({
            ajax: "{{route('showSales')}}",
            'bDestroy'    : true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            'responsive'  : true,

            'columns': [
            { 'data': 'code' },
            { 'data': 'os_no' },
            { 'data': 'bp' },
            { 'data': 'uName' },
            { 
              "data":    null,
              "render": function(data, type, full, meta){
                var disc=data.disc;

                if(disc==0 || disc == null)
                {
                  return '<span class="label label-outline-danger" width="100px">NO DISCOUNT</span>' ;
                }
                else
                {
                  return '<span class="label label-outline-info" width="100px" >'+disc+'</span>' ;
                }

                
              }

            },
            { 
              "data":    null,
              "render": function(data, type, full, meta){
                var tr=data.tr;
                var pad=data.pad;

                if(tr==pad)
                {
                  return tr;
                }
                else
                {
                  return tr+ '&nbsp;&nbsp;&nbsp;  <b> >>> </b>&nbsp;&nbsp;&nbsp;  ' +pad ;
                }
              }

            },
            { 'data': 'date'},
            { 
              "className": 'options',
              "data":    null,
              "render": function(data, type, full, meta){
                var valueHere=data.sId;

                return '<button value="'+valueHere+'" class="btn btn-info" data-toggle="modal" data-target="#successModalAlertdb" type="button"><i class="icon icon-eye"></i></button>';
              }

            },
            ]
          });
          



        </script>

      </tbody>
    </table>
  </div>
</div>


<div class="row col-md-6">
  <div class="card">
    <div class="card-header">
      <b>INVENTORY STATUS</b>
    </div>
    <div class="card-body">
      <table id="invDataDB" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width='5%'>Code</th>
            <th width='10%'>Name</th>
            <!-- <th width='10%'>Category</th> -->
            <!-- <th width='10%'>Unit</th> -->
            <th width='10%'>Qty</th>
           <!--  <th width='5%'>Status</th>
            <th width='5%'>Action</th>
          --></tr>
        </thead>
        <tbody>

          <script>

            $('#invDataDB').DataTable({
              ajax: "{{route('invDataDB')}}",
              'bDestroy'    : true,
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true,
              'responsive'  : true,

              'columns': [
              { 'data': 'invCode' },
              { 'data': 'invName' },
              {
                "className": 'text-center',
                "data": null,
                "render": function (data, type, full, meta) {
                  var qty = data.invQty;
                  if(qty == null)
                  {
                    return '0' ;
                  }
                  else
                  {
                    return qty ;
                  }

                }
              },
              ]
            });
            



          </script>

        </tbody>
      </table>
    </div>
  </div>
</div>
@include('dashboard.script.dashboardScript')
@endsection