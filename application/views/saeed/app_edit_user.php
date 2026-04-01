<div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Admin Dashboard</h2>   
                        <h5>Welcome Jhon Deo , Love to see you back. </h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
             <h1 style ="color: #084365;">Customer Entry</h1>
    <br>
    <table class="table   table-condensed table-striped  table-hover ">
      

         <tr>
                  <th>Full Name</th>
          <td>
                          
                   <input type="text" value="<?php echo $data[0]->full_name;?>" required="required" class="form-control" id="fn">
          </td>
      </tr>
        <tr>
            <th>phoneNumber</th>
          <td>
                         
                   <input type="text" value="<?php echo $data[0]->phone_number;?>" required="required" class="form-control" id="ph">
          </td>
      </tr>
      <tr>
          <th>Password</th>
          <td>
                         
                   <input type="text" value="<?php echo $data[0]->password;?>" required="required" class="form-control" id="ps">
          </td>
      </tr>
      <tr>
                  <th>Address</th>
          <td>
                         
                   <textarea id="add"><?php echo $data[0]->address;?></textarea>
          </td>
      </tr>
      <tr>
                  <th>company Name</th>
          <td>
                         
                   <input type="text" value="<?php echo $data[0]->company_name;?>" required="required" class="form-control" id="cn">
          </td>
      </tr>
      

      <tr>
                  <th>Expire Date</th>
          <td>
                           <input  type="hidden" required="required" value="<?php echo $data[0]->id;?>" id="h_id">
                   <input type="Date" value="<?php echo $data[0]->exp_date;?>" required="required" class="form-control" id="e_datee">
          </td>
      </tr>
      
        <tr>
          <th>Status</th>
          <td>
            <select class="form-control" id="st">
                <option value="<?php echo $data[0]->status;?>">
                    <?php
                    if ($data[0]->status==1){ echo'Active';}
                    if ($data[0]->status==3){ echo 'Expired';}
                    if ($data[0]->status==2){ echo 'In-Active';}
                    ?>
                </option>
                <option value="1">Active</option>
                 <option value="2">IN-Active</option>
                 <option value="3">Expired</option>
            </select>
          </td>
        </tr>
        <tr>
        <td colspan="2">
          <input type="button"  value="Add customer" onClick="register()" class=" btn btn-info form-control">
        </td>
      </tr>
      
     
      
    </table>
</form>
<script type="text/javascript">

  function register()
  {
    var st=$('#st').val();
    var e_datee=$('#e_datee').val();
    var h_id=$('#h_id').val();
    var cn=$('#cn').val();
    var ph=$('#ph').val();
    var add=$('#add').val();
    var fn=$('#fn').val();
    var ps=$('#ps').val();
    
    flag=1;

  
  if(flag==1)
  {
    var myurl='<?php echo base_url('Saeed_cont/app_edit_user_auth')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: '&e_datee='+e_datee+'&st='+st+'&h_id='+h_id+'&cn='+cn+'&ps='+ps+'&fn='+fn+'&ph='+ph+'&add='+add,
           dataType: 'JSON',

          success: function(result){ 
            alert("Sccessfully Edit..");
        location. reload(true);
  }
});
}
  }
</script>