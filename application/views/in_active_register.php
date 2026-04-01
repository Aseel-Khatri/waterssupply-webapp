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
             <h1  style="color: #084365;">Customers</h1>
    <br>
    <div id="mydiv">
    <div class="table-responsive">
    <table id="myTableee" class="table   table-condensed table-striped  table-hover  ">
        <thead>
      <tr>
          <th>SR</th>
          <th>Name</th>
          <th>Phone number</th>
          <!-- <th>Date</th>
          <th>Address</th>-->
          <th>Price</th>
         <!-- <th>Deposit</th>
          <th>Type</th>-->
        
          <th>Action</th>
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
        
          <td><?php echo $a->first_name?><?php echo $a->last_name?></td>
          <td><?php echo $a->number?></td>
        <!--  <td><?php echo $a->datee?></td>
          <td><?php echo $a->address?></td>
-->          <td><?php echo $a->price?></td>
<!--          <td><?php echo $a->deposit?></td>
          <td><?php echo ($a->type==1)?'Can':'bottle'?></td>
-->         
         <td>
            <select id="slc" class="form-control" data-key="<?=$a->id?>">
              <option value="">Action</option>
              <option value="1">Detail</option>
        <!--      <option value="2">Edit</option>
              <option value="3">Delete</option>
        -->       <option value="4">Active</option> 
                  <option value="10">Personal Detail</option> 
            </select>
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
<script type="text/javascript">
  $(document).on('change','#slc',function(){
  var slc_val=$(this).val();
  var slc_id=$(this).attr('data-key');
  if(slc_val==3)
  {
      swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this  customer!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

      var myurl='<?php echo base_url('maincontroller/delete_user')?>';
    
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+slc_id,
           success: function(result){ 
         $('#mydiv').load(" #mydiv > *");
       }
          });

    swal("Poof! Your  customer has been deleted!", {
      icon: "success",
      
    });

   
  } 
  else {
    swal("Your  customer is safe!");
  }


});

  
  }


if(slc_val==4)
  {
      swal({
  title: "Are you sure?",
  text: "Once In-Active, you will not be able to recover this  customer!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

      var myurl='<?php echo base_url('maincontroller/active_cus')?>';
    
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+slc_id,
           success: function(result){ 
         $('#mydiv').load(" #mydiv > *");
       }
          });

    swal("Poof! Your  customer has been In-Active!", {
      icon: "success",
      
    });

   
  } 
  else {
    swal("Your  customer is safe!");
  }


});

  
  }


 else if(slc_val==2)
  {
     window.location="<?php echo base_url()?>maincontroller/edit_user?id="+slc_id;
  }
  else if(slc_val==1)
  {
   window.location="<?php echo base_url()?>maincontroller/detail_reg?id="+slc_id;
  }
  else if(slc_val==10)

  {

   window.location="<?php echo base_url()?>maincontroller/all_reg_detail?id="+slc_id;

  }
  else
  {
    swal("Something Went wrong!", "You clicked the button!", "error");
  }



  
  });

  
</script>

