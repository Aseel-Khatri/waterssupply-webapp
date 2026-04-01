<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--order_delivery_modal-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<link href=="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<style>
   .dataTables_filter {
   float: right !important;
}
</style>

        <div id="page-wrapper" >

            <div id="page-inner">

                <div class="row">

                    <div class="col-md-12">

                     <h2>Admin Dashboard

                     </h2>   

                        <h5>Welcome Jhon Deo , Love to see you back. </h5>

                    </div>

                </div>              

                 <!-- /. ROW  -->

                  <hr />

             <h1  style="color: #084365;">Customer's</h1>

    <br>
<form method="POST" action="<?php echo base_url('Saeed_cont/customer_delete_rec_auth')?>">
<div class="row">
<div class="col-md-12">
<div class="col-md-3">
    
    customer id <input type="number"  name="c_id" class="form-control">
</div>

<div class="col-md-2">
    <input type="Submit" name="btn_post" style="margin-top: 19px;" class="form-control btn-info">
</div>
</div>
</div>
</form>
<br>
    <div id="mydiv">

    <div class="table-responsive">

        <table id="myTableee" class="table table-hover table-bordered" style="width:100%">
<thead>
      <tr>

          <th>SR</th>

          <th>Name</th>
           <th>yyy-mm-dd</th>
          <th>Deliver by</th>
          <th>Type</th>
          <th>Filled deliver</th>
          <th>Empty recieved</th>
          <th>Bottle balance</th>
          <th>Amount</th>
           <th>Amount recieved</th> 
           <th>Amount balance</th>
           <th>Total-Amount</th>
         

      
      </tr>

     </thead>
<tbody>
        <?php

         $index=1;

         if(empty($data))

         {

          echo '<h1>There is no record</h1>';

         }

         else

         {

          foreach ($data as $a) {

          ?>

      <tr>

         <td><?php echo $index++?></td>
          <td><?php echo $a->rfn?><?php echo $a->rln?></td>
          <td><?php echo $a->fdatee?></td>
          <td><?php echo $a->bottle_deliver?></td>
          <td><?php echo ($a->typ==1)?'Can':'bottle'?></td>
          <td class="fd"><?php echo $a->filled_deliver?></td>
          <td class="er"><?php echo $a->empty_recieved?></td>
          <td class="bb"><?php echo $a->bottle_blnc?></td>
          <td class="ta"><?php echo $a->date_wise?></td>
          <td class="ar"><?php echo $a->amount_rec?></td>
          <td class="ab"><?php echo $a->amount_blnc?></td>
          <td class=""><?php echo $a->total_amount?></td>
           <td class=""> <buttton class="btn-xs btn-danger" onclick="delete_c(this)" data-key="<?php echo $a->fc_id?>" >Delete</buttton></td>
          
          


      </tr>

      <?php

    }

  }

    ?>

      

    
    </table>
    
    
    

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js">
</script>

<script>
    function delete_c(obj)
    {
    var id=$(obj).attr('data-key');
    
      swal({

  title: "Are you sure?",

  text: "Delete ALL Recordes, you will not be able to recover this  customer!",

  icon: "warning",

  buttons: true,

  dangerMode: true,

})

.then((willDelete) => {

  if (willDelete) {
      var myurl='<?php echo base_url('Saeed_cont/delete_id_rec')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+id,
           success: function(result){
              
         $('#mydiv').load(" #mydiv > *");

       }
          });

    swal("Successfully! Records has been deleted!", {

      icon: "success",

    });

  } 

  else {
    swal("Your  customer is safe!");

  }

});

    }
</script>