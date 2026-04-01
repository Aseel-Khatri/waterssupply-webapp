
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Cutomers Added
            
                     </h2>   
                          <h5>Welcome <?php echo @$_SESSION['login'][0]->com_name;?> ,<?php date_default_timezone_set("Asia/Karachi");echo date('d/M/Y');?></h5>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
             <h1 style="color: #084365;"><?php
             echo $data[0]->first_name.'&nbsp;'.$data[0]->last_name;
             ?></h1>
    <br>
    <form method="POST" action="<?php echo base_url('maincontroller/edit_user_auth')?>">
    <table class="table   table-condensed table-striped  table-hover ">
      <tr>
          <th>First Name</th>
          <td>
            <input type="hidden" name="id" value='<?php echo $data[0]->id;?>'>
                 <input  type="text" value='<?php echo $data[0]->first_name;?>' required="required" name="fn" class="form-control" id="fn">
          </td>
          </tr>
          <tr>
           <th>Last Name</th>
          <td>
                   <input value='<?php echo $data[0]->last_name;?>' type="text" required="required" name="ln" class="form-control" id="ln">
          </td>

      </tr>
      <tr>
          <th>Phone number</th>
          <td>
                 <input value='<?php echo $data[0]->number;?>' type="number" required="required" name="pn" class="form-control" id="pn">
          </td>
          </tr>
          <tr>
           <th>Date</th>
          <td>
                   <input value='<?php echo $data[0]->datee;?>' type="Date" required="required" name="datee" class="form-control" id="datee">
          </td>

      </tr>
      <tr>
          <th>Address</th>
          <td>
<textarea required="required" id="add" name="add" style="resize: none;" class="form-control"><?php echo $data[0]->address;?></textarea>
          </td>
          </tr>
          <tr>
          <th>Price</th>
          <td>
                 <input required="required" name="price" value='<?php echo $data[0]->price;?>' type="text" id="price" class="form-control">
          </td>
           

      </tr>
      <tr>
        <th>Deposit</th>
          <td >
                 <input required="required" name="deposit" value='<?php echo $data[0]->deposit;?>' type="number" id="deposit" class="form-control">
          </td>
          </tr>
          <tr>
          <th>Type</th>
          <td>
          <span style="font-size: 24px;padding: 17px;">Can</span><input type="radio" <?php echo ($data[0]->type==1)?'Checked':''?>
 class="c" id="c" name="r_v" value="1">
          <span style="font-size: 24px;padding: 17px;">Bottle</span><input <?php echo ($data[0]->type==2)?'Checked':''?> type="radio" class="c" id="c" name="r_v" value="2">
          </td>
          
      </tr>
       <!--<tr>
        
          <th><button onclick="show_td()"class="btn btn-info ">Add more days of giving</button></th>
          <td style="display:none">
             <select required="required" name="sel[]" class="form-control" multiple="multiple">
               <option value="Mon">Monday</option>
          <option value="Tue">Tuesday</option>
          <option value="Wed">Wednesday</option>
          <option value="Thu">Thursaday</option>
          <option value="Fri">Friday</option>
          <option value="Sat">Saturday</option>
          <option value="Sun">Sunday</option>
             
          </select>
          </td>
           </tr>-->
           <tr>
            <th>Select delivery_boy</th>
             <td>
              <select  placeholder="select delivery boy" name="db_id" class="form-control">
                <option value="<?php echo (!empty($data[0]->dbid))?$data[0]->dbid:''?>"><?php echo (!empty($data[0]->delivery_boy))?$data[0]->delivery_boy:'SELECT here '?></option>
                
                <?php foreach ($db as $ue): ?>
                  <option value="<?php echo $ue->delivery_boy_idd?>"><?php echo $ue->user_name?></option>
                <?php endforeach ?>

               
             </td>
           </tr>
           
           
           
            <tr>
               <th><button type="button"  class="btn btn-md btn-warning" id="btn_old_show">Old record</button>
               <button type="button" style="display:none;" class="btn btn-xs btn-warning" id="btn_old_hide">Hide</button>
               </th>
           </tr>
           <tr style="display:none;" id="r_bb">
            <th>Bottle balance</th>
                <td>
        
                    <input type="number" placeholder="bottle balanc" class="form-control" name="bb" >
                </td>
            
            </tr>
            <tr style="display:none;" id="r_ab">
            <th>Amount balance</th>
                <td>
       
                    <input type="number" class="form-control" name="ab" placeholder="Amount balance">
                </td>
            
            </tr>
           
           
           
      <tr>
       
        
        <td colspan="2">
          <input type="submit"  value="Add customer"  class=" btn btn-info form-control">
        </td>
      </tr>
      
     
      
    </table>
</form>
<script type="text/javascript">
  function register()
  {
    var fn=$('#fn').val();
    var ln=$('#ln').val();
    var pn=$('#pn').val();
    var add=$('#add').val();
    var price=$('#price').val();
    var type=$('.c:Checked').val();
    var datee=$('#datee').val();
     var deposit=$('#deposit').val();
    var id=$('#id').val();
    flag=1;
 if(fn=='' || fn==undefined){
        flag=0;
        $('#fn').css('border','1px solid red');
  } 
if(deposit=='' || deposit==undefined){
        flag=0;
        $('#deposit').css('border','1px solid red');
  }


if(ln=='' || ln==undefined){
        flag=0;
        $('#ln').css('border','1px solid red');
  }
if(pn=='' || pn==undefined){
        flag=0;
        $('#pn').css('border','1px solid red');
  }
  if(price=='' || price==undefined){
        flag=0;
        $('#price').css('border','1px solid red');
  }
  if(add=='' || add==undefined){
        flag=0;
        $('#add').css('border','1px solid red');
  }
  if(datee=='' || datee==undefined){
        flag=0;
        $('#datee').css('border','1px solid red');
  }

    if(type=='' || type==undefined){
        flag=0;
        $('#type').css('border','1px solid red');
  }
  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/edit_user_auth')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'fn='+fn+'&ln='+ln+'&add='+add+'&datee='+datee+'&pn='+pn+'&price='+price+'&type='+type+'&deposit='+deposit+'&id='+id,
           dataType: 'JSON',
            
          
          success: function(result){ 
            console.log(result);
        swal("UPDATED!", "Successfully!", "success");
  }
});
}
  }
</script>
<script>
    $('#btn_old_show').on('click',function(){
       $('#btn_old_hide').show();
       $('#r_ab').show();
       $('#r_bb').show();
       $('#btn_old_show').hide();
    });
    $('#btn_old_hide').on('click',function(){
       $('#btn_old_hide').hide();
       $('#r_ab').hide();
       $('#r_bb').hide();
       $('#btn_old_show').show();

    });
    
   
</script>