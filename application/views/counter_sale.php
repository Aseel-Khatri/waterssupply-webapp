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
             <h1 style="color: #084365;">Total counter sale</h1>
    <br>
    
    <table class="table   table-condensed table-striped  table-hover ">
      <tr>
          <th>Total Counter Sale</th>
          <td>


                 <input  type="number" placeholder="enter a counter sale" required="required" class="form-control" id="c_s" name="c_s">
          </td>
          </tr>
          
      <tr>

        <td colspan="2">
          <input type="button" onclick="counter()"  value="Add counter sale"  class=" btn btn-info form-control">
        </td>
      </tr>
      
     
      
    </table>


<script>
    function counter()
    {
        
        var c_s=$('#c_s').val();
        var flag=1;  
          if(c_s==''||c_s==undefined)
          {
              flag=0;
              alert("Fill out the fields...");
          }
          if(flag==1)
          {
          
          var myurl='<?php echo base_url()?>maincontroller/counter_sale_auth';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'c_s='+c_s,
           success: function(result){ 
        console.log(result);
        $('#c_s').val("");
       swal("Success","Successfully Added","success");
               
           }
          });
          }

    }
</script>
