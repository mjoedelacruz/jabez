@extends('layouts.main')

@section('modal')
<div id="successModalAlertzzz" tabindex="-1" role="dialog" class="modal fade">
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
          <span class="text-primary icon icon-close icon-5x"></span>
          <h3 class="text-primary">Are you sure you want to cancel?</h3>
          <p><span class="label label-outline-primary">WARNING!</span> THIS CHANGES CANNOT BE UNDONE!</p>
          <div class="m-t-lg">
            <button class="btn btn-primary" id="cancelGoods" data-dismiss="modal" type="button">Yes</button>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<div id="successModalAlert" tabindex="-1" role="dialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="card">
        <div class="card-header">
          <b id="ge1">GOODS ENTRY</b> <button id="geNum" class="label label-outline-info" ></button> - <button id="bpNameS" class="label label-outline-success" ></button> - <button id="grNum" class="label label-outline-info" ></button>
        </div>
        <div class="card-body">
          <table id="gEntryDetailList" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th width='10%'>Qty</th>
                <th width='10%'>Price</th>
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
            <!--  <button id="cancelGE" class="btn btn-danger" data-toggle="modal" data-target="#successModalAlertzzz" data-dismiss="modal" type="button">Cancel</button> -->
            <button class="btn btn-danger" data-dismiss="modal" type="button">Close</button>
          </div>
          <div class="col-md-3" style="float:right;" >
            <input style="text-align:right;" id="totalPay" class="form-control" disabled="">
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
		<span class="d-ib">Goods Entry</span>
	</h1>
</div>
<div class="card">
	<div class="card-header">
		<b>ENTER GOODS</b>
	</div>
	<div class="card-body">
		<div class="row">


  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <b>SELECT A PARTNER & ITEMS </b>
      </div>
      <div class="card-body">
        <div id="bpDiv" class="form-group">
          <!-- <label>Choose Inventory Item</label> -->
          <select id="gEntryBpCode" class="form-control" >
            <option value="">- SELECT PARTNER -</option>
            @foreach($bp as $b)
            <option value="{{$b->bpCode}}">{{$b->name}}</option>
            @endforeach
          </select>
        </div>
        <div id="gEntryDiv" class="form-group">
          <!-- <label>Choose Inventory Item</label> -->
          <select id="gEntryitemCode" class="form-control" >
            <option value="">- SELECT ITEM -</option>
            @foreach($inv as $i)
            <option value="{{$i->code}}">{{$i->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <strong id="geTitle">GOODS ENTRY</strong>
      </div>
      <div class="card-body" data-toggle="match-height" style="height:300px;width:100%;">
        <table class="table" id="itemCodeList">
          <thead>
            <tr>
              <th width="10%">Code</th>
              <th >Item Name</th>
              <th width="10%">Qty</th>
              <th >Unit</th>
              <th width="10%">Price</th>
              <th width="10%">Shelf Life</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="itemRowList">
            <script>
             $(document).ready(function() {
              $('#itemCodeList').DataTable( {
                "scrollY":        "200px",
                "scrollCollapse": true,
                "paging":         false,
                "searching": false,
                "oLanguage": {"sZeroRecords": "", "sEmptyTable": ""},
                "bInfo": false,
                "orderable": false,
                "bDestroy": true,
                order: [],
                columnDefs: [ { orderable: false, targets: [0,1,2,3,4,5] } ],

              } );
            } );
          </script>

        </tbody>
      </table>
    </div>
    <div class="card-footer">
     <!-- <div class="col-md-4" style="float:right;" >
      <input id="totalValue" class="form-control" disabled="">
    </div> -->
    <div class="col-md-4" style="float:right;display:none;" disabled="">
      <input id="totalRowCount" class="form-control">
    </div>

    <div class="row">
 <div class="col-md-4" style="float:right;">
  <button id="clearEntry" data-action="1" style="float:right;" class="btn btn-info">Clear</button>
  <button id="addGoods" data-action="1" style="float:right;" class="btn btn-primary">Add</button>
  <button id="returnGoods" data-action="1" style="float:right;display:none;" class="btn btn-primary">Return</button>

</div>
</div>
  </div>
</div>
</div>

<div class="col-md-5">

        <div class="card">
          <div class="card-header">
           <b>ITEM LIST - STALE MONITORING</b>
         </div>
         <div class="card-body">
          <table class="table" id="staleList" width="100%">
            <thead>
              <tr>
                <th >Item Name</th>
                <th >Qty</th>
                <th >Good Until</th>
                <th>Days left</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="itemRowList">
              <script>
                $('#staleList').DataTable({
                  ajax: "{{route('staleItems')}}",
                  'bDestroy'    : true,
                  'paging'      : true,
                  'lengthChange': false,
                  'searching'   : false,
                  'ordering'    : true,
                  'info'        : true,
                  'autoWidth'   : true,
                  'responsive'  : true,
                  'pageLength'  : 8,
                  "order": [[ 4, "DESC" ]],
                  'columns': [
                  {'data':'itemName'},
                  {'data':'qty'},
                  {'data': 'sd'},
                  {
                    "className": 'text-center',
                    "data": null,
                    "render": function (data, type, full, meta) {
                      var sd = data.sd;
                      var sdf =data.sdf;
                      var d = new Date();

                      var month = d.getMonth()+1;
                      var day = d.getDate();
                      var output = d.getFullYear()+'-'+ (month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;

                      var date1 = new Date(sd);
                      var date2 = new Date(output);
                      var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                      var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

                     

                      if(diffDays <= 3)
                      {
                        return '<span class="label label-danger" width="100px" style="width: 70px;">'+diffDays+'  - Days</span>'; 
                      }
                      else
                      {
                        return '<span class="label label-success" width="100px" style="width: 70px;">'+diffDays+' - Days </span>';
                        
                      }
                      
                   }
                 },

                 { 
                  "className": 'options',
                  "data":    null,
                  "render": function(data, type, full, meta){
                    var valueHere=data.id;

                    return '<button value="'+valueHere+'" data-id="1" class="btn btn-info" type="button"><i class="icon icon-sign-out"></i></button> ';
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

</div>


</div>
</div>

<div class="col-md-6">
  <div class="card">
   <div class="card-header">
    <b>GOODS ENTRY LIST</b>
  </div>
  <div class="card-body">
    <table id="gEntryList" class="table table-bordered table-hover" style="width:100%;">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="15%">Supplier</th>
          <th width="15%">User</th>
          <th width="5%">Amount</th>
          <th width="5%">Status</th>
          <!-- <th width="5%">Stale</th> -->
          <th width="15%">Date</th>
          <th width="10%"></th>

        </tr>
      </thead>
      <tbody id="gEntryListBody">

        <script>

          $('#gEntryList').DataTable({
            ajax: "{{route('showGoodsEntry')}}",
            'bDestroy'    : true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            'responsive'	: true,

            'columns': [
            { 'data': 'id' },
            { 'data': 'bp' },
            { 'data': 'user' },
            { 'data': 'pay' },
            {
             "className": 'text-center',
             "data": null,
             "render": function (data, type, full, meta) {
              var stat = data.status;
              var valueHere=data.Date;
              var d = new Date();

              var month = d.getMonth()+1;
              var day = d.getDate();
              var output = d.getFullYear()+'-'+ (month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;

              var date1 = new Date(valueHere);
              var date2 = new Date(output);
              var timeDiff = Math.abs(date2.getTime() - date1.getTime());
              var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
              
              if(stat == 1)
              {
               return '<span class="label label-success" width="100px" style="width: 60px;">Received</span>';

             }
             else
             {
               return '<span class="label label-primary" width="100px" style="width: 60px;">Cancelled</span>' ;
             }
           }
         },

         { 
           "data":    null,
           "render": function(data, type, full, meta){
            var valueHere=data.Date;


            
            return valueHere;

          }

        },
        { 
         "className": 'options',
         "data":    null,
         "render": function(data, type, full, meta){
          var valueHere=data.id;

          return '<button value="'+valueHere+'" class="btn btn-danger" data-toggle="modal" data-target="#successModalAlert" type="button"><i class="icon icon-eye"></i></button> &nbsp; <button value="'+valueHere+'" data-id="1" class="btn btn-info" type="button"><i class="icon icon-arrow-circle-left"></i></button> ';
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

<div class="col-md-6">
  <div class="card">
    <div class="card-header">
      <b>GOODS RETURN LIST</b>
    </div>
    <div class="card-body">
      <table id="gRetList" class="table table-bordered table-hover" style="width:100%;">
        <thead>
          <tr>
            <th width="5%">#</th>
            <th width="15%">Supplier</th>
            <th width="15%">User</th>
            <th width="5%">Amount</th>
            <th width="5%">Status</th>
            <th width="15%">Date</th>
            <th width="10%"></th>

          </tr>
        </thead>
        <tbody id="gEntryListBody">

          <script>

            $('#gRetList').DataTable({
              ajax: "{{route('showGoodsReturn')}}",
              'bDestroy'    : true,
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true,
              'responsive'  : true,

              'columns': [
              { 'data': 'id' },
              { 'data': 'bp' },
              { 'data': 'user' },
              { 'data': 'amt' },
              {
               "className": 'text-center',
               "data": null,
               "render": function (data, type, full, meta) {
                var stat = data.status;
                if(stat == 1)
                {
                 return '<span class="label label-info" width="100px" style="width: 60px;">Returned</span>' ;
               }
               else
               {
                 return '<span class="label label-primary" width="100px" style="width: 60px;">Cancelled</span>' ;
               }

             }
           },
           { 'data': 'Date' },
           { 
             "className": 'options',
             "data":    null,
             "render": function(data, type, full, meta){
              var valueHere=data.id;

              return '<button value="'+valueHere+'" class="btn btn-danger" data-toggle="modal" data-target="#successModalAlert" type="button"><i class="icon icon-eye"></i></button>';
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


@include('goodsentry.script.gEntryScript')
@endsection