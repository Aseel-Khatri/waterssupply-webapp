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
<script  type="text/javascript">
$(document).ready(function() {
    $('#myTableee').DataTable( {
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
                dom: 'Bfrtip',
        buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
        ]
           } );
} );



</script>


        <div id="page-wrapper" >

            <div id="page-inner">

                <div class="row">

                    <div class="col-md-12">

                     <h2>Admin Dashboard

                     </h2>   

                       <h5>Welcome <?php echo $_SESSION['login'][0]->com_name;?> ,<?php date_default_timezone_set("Asia/Karachi");echo date('d/M/Y');?></h5>

                    </div>

                </div>              

                 <!-- /. ROW  -->

                  <hr />

             <h1  style="color: #084365;">Customer's</h1>

        <br>
<form method="POST" action="<?php echo base_url()?>/maincontroller/active_user_by_date">
<div class="row">
<div class="col-md-12">
<div class="col-md-3">
    
    From <input type="date" data-date-format="yyyy-mmmm-dd" name="fdate" class="form-control">
</div>
<div class="col-md-3">
    To <input type="date" name="todate" class="form-control">
</div>
<div class="col-md-2">
    <input type="Submit" style="margin-top: 19px;" class="form-control btn-info">
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
          <th>Phone number</th>
           <th>yyy-mm-dd</th>
          <th>Address</th>
          <th>Price</th>
          <th>Deposit</th>
          <th>Type</th>
          <!--<th>Day of giving</th>-->
          <th>Delivery boy</th>
      
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

        

          <td data-toggle="tooltip" title="Click here to view detail!">
              <a  href="<?php echo base_url()?>maincontroller/active_user_report_by_id?id=<?php echo $a->id?>">
              <?php echo $a->first_name?><?php echo $a->last_name?></a></td>

          <td><?php echo $a->number?></td>

          <td><?php echo $a->datee?></td>

          <td><?php echo $a->address?></td>

          <td><?php echo $a->price?></td>

          <td><?php echo $a->deposit?></td>

          <td><?php echo ($a->type==1)?'Can':'bottle'?></td>
<!--
          <td><button type="button" data-key="<?=$a->id?>" class="btn btn-info btn-xm" onclick="all_day(this)">Click me</button>

</td>-->
<td>
    <?php echo ($a->delivery_boy=='')?'Not-assign':$a->delivery_boy;?>
    </td>

      </tr>

      <?php

    }

  }

    ?>

      

      
</tbody>
     

      

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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>