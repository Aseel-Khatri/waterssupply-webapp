
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Admin Dashboard</h2>   
                                            <h5>Welcome <?php echo @$_SESSION['login'][0]->com_name;?> ,<?php date_default_timezone_set("Asia/Karachi");echo date('d/M/Y');?></h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
             <h1 style="color: #084365;">Delivery boy Entry</h1>
    <br>
    <table class="table   table-condensed table-striped  table-hover ">
      <tr>
          <th>user_name</th>
          <td>
            <input type="hidden" id="id" value="<?php echo @$_GET['id']?>" >

                 <input  type="text" placeholder="enter a text without space" required="required" class="form-control" id="un">
          </td>
          </tr>
          <tr>
           <th>password</th>
          <td>
                   <input type="text" required="required" class="form-control" id="pass">
          </td>

      </tr>
      <tr>
      </td>
        
        <td colspan="2">
          <input type="button"  value="Add Delivery boy" onClick="register()" class=" btn btn-info form-control">
        </td>
      </tr>
      
     
      
    </table>
</form>
<script type="text/javascript">
  jQuery(function($){
    $('#un').keyup(function(e){
        if (e.which === 32) {
             swal("Space are not allowed in usernames", "", "error");
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
    var id=$('#id').val();
    
    flag=1;
    
    
 if(un=='' || un==undefined){
        flag=0;
        $('#un').css('border','1px solid red');
  } 
if(pass=='' || pass==undefined){
        flag=0;
        $('#pass').css('border','1px solid red');
  }


  
  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/add_delivery_boy_auth')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'un='+un+'&id='+id+'&pass='+pass,
           dataType: 'JSON',

          success: function(result){ 
       
            if(result==2)
            {
              alert("Sory limit has been crossed...");
        location. reload(true);
            }
              if(result==3)
            {
              swal("Warning","Please try different number","error");
               $('#un').css('border','1px solid red');
            }
            if(result==1)
            {
alert("Successfully Added");
        location. reload(true);

            }

          
  }
});
}
  }
</script>