<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--order_delivery_modal-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<link href=="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
   .dataTables_filter {
   float: right !important;
}
</style>
<script  type="text/javascript">
$(document).ready(function() {
    $('#myTableee').DataTable( {
         responsive: {
        details: false
    },
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
       
           } );
} );



</script>

        <div id="page-wrapper" >

            <div id="page-inner">

                <div class="row">

                    <div class="col-md-12">

                     <h2>Admin Dashboard

                     </h2>   

                                     <h5>Welcome <?php echo @$_SESSION['login'][0]->com_name;?> ,<?php date_default_timezone_set("Asia/Karachi");echo date('d/M/Y');?></h5>

                    </div>

                </div>              

                 <!-- /. ROW  -->

                  <hr />

             <h1  style="color: #084365;">Customer's</h1>

    <br>

    <div id="mydiv">

    <div class="table-responsive">

        <table id="myTableee" class=" display responsive no-wrap table table-hover table-bordered" style="width:100%">
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

    
        <th>Name</th>

          <td>
              <a href="<?php echo base_url()?>maincontroller/detail_reg?id=<?php echo $a->id?>">
              <?php echo $a->first_name?><?php echo $a->last_name?>
              </a>

              </td>
    </tr>
      
        
    <tr>
        <th>Phone number</th>
          <td><?php echo $a->number?></td>

</tr>
    <tr>
           <th>yyy-mm-dd</th>
          <td><?php echo $a->rdatee?></td>
</tr>
    <tr>
        <th>Address</th>
          <td><?php echo $a->address?></td>
    </tr>
    <tr>
          <th>Price</th>
          <td><?php echo $a->price?></td>
</tr>
    <tr>
        <th>Deposit</th>
          <td><?php echo $a->deposit?></td>
</tr>
    <tr>
        <th>Type</th>
          <td><?php echo ($a->type==1)?'Can':'bottle'?></td>
</tr>
    <tr>
         <th>Delivery boy</th>
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

    </div>
  </div>
</div>
 

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

