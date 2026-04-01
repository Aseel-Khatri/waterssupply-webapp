

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
    <div id="mydiv">
    <div class="table-responsive">
    <table class="table table-bordered   table-condensed table-striped  table-hover  ">
      <tr>
          <th>SR</th>
          <th>Date</th>
          <th>user_name</th>
          <th>Password</th>
           <th>Company Name</th>
          <th>status</th>
          <th>Phone number</th>
          <th>Per-month-amount</th>
          <th>Delivery boy limit</th>
          <th>reffer</th>
           <th>SMS</th>
           <th>Add Free</th>
          <th>Action</th>
      </tr>
     
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
        <td><?php echo $a->sdatee." To ".$a->edatee?></td>
          <td><?php echo $a->user_name?></td>
          <td><?php echo $a->password?></td>
          <td><?php echo $a->com_name?></td>
          <td>
              <?php
                    if ($data[0]->status==1){ echo'<span class="alert-info">Active</span>';}
                    if ($data[0]->status==3){ echo '<span class="alert-danger">Expire</span>';}
                    if ($data[0]->status==2){ echo '<span class="alert-danger">In-Active</span>';}
                    ?>
              
              </td>
        
           <td><?php echo $a->phone_number?></td>
          <td><?php echo $a->per_month_amount?></td>
           <td><?php echo $a->delivery_boy_limit?></td>
          <td><?php echo $a->reffer?></td>
           <td><?php echo ($a->sms==1)?'Yes':'No'?></td>
           <td><?php echo ($a->is_add_free==1)?'Yes':'No'?></td>
          <td>
            <select id="slc" class="form-control" data-key="<?=$a->id?>">
              <option value="">Action</option>
              <option value="1">Edit</option>
              <option value="2">In-Active</option>
              <option value="3">Delete All-records</option>
            </select>
          </td>
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

<script type="text/javascript">
  $(document).on('change','#slc',function(){
  var slc_val=$(this).val();
  var slc_id=$(this).attr('data-key');



  if(slc_val==1)
  {

    
     window.location="<?php echo base_url()?>Saeed_cont/edit_user?id="+slc_id;
  }
  else if(slc_val==2)
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
      var myurl='<?php echo base_url('Saeed_cont/in_active_cus')?>';
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

 
 
 
  
 /* else
  {
    swal("Something Went wrong!", "You clicked the button!", "error");
  }
*/


 else if(slc_val==3)
  {


      swal({

  title: "Are you sure?",

  text: "Delete ALL Recordes, you will not be able to recover this  customer!",

  icon: "warning",

  buttons: true,

  dangerMode: true,

})

.then((willDelete) => {

  if (willDelete) {
      var myurl='<?php echo base_url('Saeed_cont/delete_all_rec')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+slc_id,
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






















  
  });

</script>

