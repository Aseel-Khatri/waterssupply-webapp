
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
          <th>user_name</th>
          <td>
                 <input  type="text" required="required" class="form-control" id="un">
          </td>
        </tr>
        <tr>
           <th>password</th>
          <td>
                   <input type="text" required="required" class="form-control" id="pass">
          </td>

      </tr>
      <tr>
          <th>Company Name</th>
          <td>
                 <input type="text" required="required" class="form-control" id="cn">
          </td>
        </tr>
        <tr>
          <th>Address</th>
          <td>
                 <textarea id="add"  style="resize: none;" class="form-control"></textarea>
          </td>
          

      </tr>
      <tr>
           <th>Start Date</th>
          <td>
                   <input type="Date" required="required" class="form-control" id="s_datee">
          </td>
        </tr>
        <tr>
<th>End Date</th>
          <td>
                   <input type="Date" required="required" class="form-control" id="e_datee">
          </td>
      </tr>
       <tr>
          <th>Per mount Amount</th>
          <td>
                   <input type="number" id="pma" required="required" class="form-control" >
          </td>
        </tr>
        <tr>
<th>Phone number</th>
          <td>
                   <input type="number" id="pn" required="required" class="form-control">
          </td>
      </tr>
      <tr>
      <th>Reffer</th>
      <td>
      <input type="text"  id="ref" placeholder="reffer name" class="form-control">
      </td>
        </tr>
        <tr>
          <th>Delivery Boy limit</th>
          <td>
            <input type="number" class="form-control" id="dbl">
          </td>
        </tr>
        <tr>
          <th>Sms service</th>
          <td>
            <select class="form-control" id="sms_id">
                <option value="">Select sms service</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Is Add Free</th>
          <td>
            <select class="form-control" id="add_free_id">
                <option value="">Select Add Free</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
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
  jQuery(function($){
    $('#un').keyup(function(e){
        if (e.which === 32) {
             swal("No space are allowed in usernames", "", "error");
            var str = $(this).val();
            str = str.replace(/\s/g,'');
            $(this).val(str);            
        }
    }).blur(function() {
        var str = $(this).val();
        str = str.replace(/\s/g,'');
        $(this).val(str);            
    });
});
  function register()
  {
    var un=$('#un').val();
    var pass=$('#pass').val();
    var cn=$('#cn').val();
    var add=$('#add').val();
    var s_datee=$('#s_datee').val();
    var e_datee=$('#e_datee').val();
    var dbl=$('#dbl').val();
    var pn=$('#pn').val();
    var ref=$('#ref').val();
    var pma=$('#pma').val();
    var sms_id=$('#sms_id').val();
    var add_free_id=$('#add_free_id').val();
    flag=1;
    
    if(add_free_id=='' || add_free_id==undefined){
        flag=0;
        $('#add_free_id').css('border','1px solid red');
    }
    if(sms_id=='' || sms_id==undefined){
        flag=0;
        $('#sms_id').css('border','1px solid red');
    } 
    
    
    if(pn=='' || pn==undefined){
        flag=0;
        $('#pn').css('border','1px solid red');
  } 
  if(dbl=='' || dbl==undefined){
        flag=0;
        $('#dbl').css('border','1px solid red');
  } 
 /* if(ref=='' || ref==undefined){
        flag=0;
        $('#ref').css('border','1px solid red');
  } */
    if(pma=='' || pma==undefined){
        flag=0;
        $('#pma').css('border','1px solid red');
  } 
    
    
    
    
 if(un=='' || un==undefined){
        flag=0;
        $('#un').css('border','1px solid red');
  } 
if(pass=='' || pass==undefined){
        flag=0;
        $('#pass').css('border','1px solid red');
  }


if(cn=='' || cn==undefined){
        flag=0;
        $('#cn').css('border','1px solid red');
  }
if(add=='' || add==undefined){
        flag=0;
        $('#add').css('border','1px solid red');
  }
  if(s_datee=='' || s_datee==undefined){
        flag=0;
        $('#s_datee').css('border','1px solid red');
  }
  if(e_datee=='' || e_datee==undefined){
        flag=0;
        $('#e_datee').css('border','1px solid red');
  }
  
  if(flag==1)
  {
    var myurl='<?php echo base_url('Saeed_cont/add_client_auth')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'un='+un+'&cn='+cn+'&add='+add+'&s_datee='+s_datee+'&e_datee='+e_datee+'&pass='+pass+'&pma='+pma+'&ref='+ref+'&pn='+pn+'&dbl='+dbl+'&sms_id='+sms_id+'&add_free_id='+add_free_id,
           dataType: 'JSON',

          success: function(result){ 
            alert("Sccessfully Edit..");
        location. reload(true);
  }
});
}
  }
</script>