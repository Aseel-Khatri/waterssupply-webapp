        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Admin Dashboard</h2>   
                        <h5>Welcome <?php echo $_SESSION['login'][0]->com_name;?> ,<?php date_default_timezone_set("Asia/Karachi");echo date('d/M/Y');?></h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
             <h1 style ="color: #084365;">Plant Entry</h1>
    <br>
    
    
    
    
    <table class="table   table-condensed table-striped  table-hover ">
      <tr>
          <th>Plant name</th>
          <td>
                 <input  type="text" value="<?php echo @$data[0]->name?>"  class="form-control" id="fn">
                 <input  type="hidden" value="<?php echo $_GET['id'];?>"  class="form-control" id="id">
          </td>
        </tr>
        
      <tr>
          <th>Phone number</th>
          <td>
                 <input type="number"  class="form-control" value="<?php echo @$data[0]->number?>" id="pn">
          </td>
        </tr>
        
      <tr>
          <th>Address</th>
          <td>
                 <textarea id="add"  style="resize: none;" class="form-control"><?php echo @$data[0]->address?></textarea>
          </td>
        </tr>
        <tr>
          <th>Price</th>
          <td >
                 <input type="number" value="<?php echo @$data[0]->price?>" id="price" class="form-control">
          </td>
           

      </tr>
        <tr>
          <th>Type</th>
          <td >
          <span style="font-size: 24px;padding: 17px;">Can</span><input type="radio" name="n" class="c" id="r_v" <?php echo($data[0]->type==1)?"Checked":''?> value="1">
          <span style="font-size: 24px;padding: 17px;">Bottle</span><input type="radio" name="n" class="c" id="r_v" value="2" <?php echo($data[0]->type==2)?"Checked":''?>>
          </td>
          
      </tr>
 
           <tr>
               
        
        <td colspan="2">
          <input type="button"  value="customer registration" class=" btn btn-info form-control" id="btn_plant">
     
        </td>
      </tr>
      
     
      
    </table>
<!--</form>-->

<script>
$('#btn_plant').on('click',function(){
    var fn = $("#fn").val();
    var pn = $("#pn").val();
    var price = $("#price").val();
    var add = $("#add").val();
    var r_v = $("#r_v").val();
     var id = $("#id").val();
    var flag=1;
    
    if (fn == "" || fn ==undefined || pn == undefined|| price == undefined || pn == ""|| price == ""|| add == undefined|| add == "") {
        alert("Please fill all the giving filed...");
        flag=0;
    }
    if (r_v == "" || r_v ==undefined) {
        alert("Please Click on Type filed");
        flag=0;
    }
    if(flag==1)
    {
        var myurl="<?php echo base_url('maincontroller/plant_reg_update')?>";
        $.ajax({
					url: myurl,
					method:'POST',
					data: 'fn='+fn+'&price='+price+'&type='+r_v+'&add='+add+'&pn='+pn+'&id='+id,
					success: function(result){ 
						if(result==1)
						{
						    alert("Successfully updated");
						     location.reload();
						}
						if(result==2)
						{
						    alert("Something went wrong");
						    location.reload();
						    
						}
					}
        
    });
    }
    
});
</script>