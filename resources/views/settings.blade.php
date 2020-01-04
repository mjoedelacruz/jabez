@extends('layouts.main')

@section('content')
<div class="media">
  <div class="media-middle media-left">
    <span class="bg-primary circle sq-48">
      <span class="icon icon-gear"></span>
    </span>
  </div>
  <div class="media-middle media-body">
    <h3 class="media-heading">
      <span class="fw-l">Settings</span>

    </h3>
  </div>
</div>

<br/>
<h4>DISCOUNTS</h4>
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <input type="hidden" id="discountId"  value = "0"/>
          <div class="col-md-6">
            <label>Name:</label>
            <input type="text" class="form-control" id="discountName"/>
          </div>
          <div class="col-md-6">
           <label>Discount Value:</label>
           <input type="text" class="form-control" id="discountValue"/>
         </div>
       </div><br/>
       <div class="row">
        <div class="col-md-12">
          <button style="float:right;" class="btn btn-primary" id="saveDiscounts" value ="1">Add Discount</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-body">
      <table id="discountsTable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Discount Value</th>
          </tr>
        </thead>

        <tbody style="font-size:12px;">
         <script>
          $('#discountsTable').DataTable({

            ajax: "/dataTablesDiscounts",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            "order": [[ 0, "asc" ]],
            'columns': [
            { 'data': 'name' },
            { 'data': 'discountValue' },
            // { 
            //   "className": 'options',
            //   "data":    null,
            //   "render": function(data, type, full, meta){
            //     var valueHere=data.id;
            //     var deleteBtn = "";

            //     return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>'+deleteBtn;



            //   }

            // },




            ],


          });




        </script> 
      </tbody>

    </table>
  </div>
</div>
</div>
</div>
<hr/>

<script> 
  var count = 1;
</script>

<script>
  $(document).ready(function() {

    $("#saveDiscounts").click(function(e){
      var discountsInfo = {
        save: $("#saveDiscounts").val(),
        discountId: $("#discountId").val(),
        discountName: $("#discountName").val(),
        discountValue: $("#discountValue").val(),
        action: 1
      };

      $.ajax({
        type:"POST",
        url: "{{route('settings.getSetSettings')}}",
        data: discountsInfo,
        headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
        success: function (data){
         console.log(data);

         var title   = 'Success!',
         message = 'Discount Saved!',
         type    = 'success',
         options = {};
         toastr[type](message, title, options);

         $('#discountsTable').DataTable({

            ajax: "/dataTablesDiscounts",
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
            { 'data': 'discountValue' },
            { 
              "className": 'options',
              "data":    null,
              "render": function(data, type, full, meta){
                var valueHere=data.id;
                var deleteBtn = "";

                return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>'+deleteBtn;



              }

            },

            ],


          });


       }
       

   })
    });

  });
</script>
@endsection