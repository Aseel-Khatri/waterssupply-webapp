
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
             <h1 style ="color: #084365;">Customer Entry</h1>
    <br>
    
    
    <form  method="POST" action="<?php echo base_url('maincontroller/register')?>" name="myForm" onsubmit="return validateForm()">
    
    <table class="table   table-condensed table-striped  table-hover ">
      <tr>
          <th>First Name</th>
          <td>
                 <input  type="text"  class="form-control" name="fn">
          </td>
        </tr>
        <tr>
           <th>Last Name</th>
          <td>
                   <input type="text"  class="form-control" name="ln">
          </td>

      </tr>
      <tr>
          <th>Phone number</th>
          <td>
                 <input type="number"  class="form-control" name="pn">
          </td>
        </tr>
        <tr>
           <th>Date</th>
          <td>
                   <input type="Date"  class="form-control" name="datee">
          </td>

      </tr>
      <tr>
          <th>Address</th>
          <td>
                 <textarea name="add"  style="resize: none;" class="form-control"></textarea>
          </td>
        </tr>
        <tr>
          <th>Price</th>
          <td >
                 <input type="number" name="price" class="form-control">
          </td>
           

      </tr>
      <tr>
        <th>Deposit</th>
          <td >
                 <input type="number" name="deposit" class="form-control">
          </td>
        </tr>
        <tr>
          <th>Type</th>
          <td >
          <span style="font-size: 24px;padding: 17px;">Can</span><input type="radio"  class="c" name="r_v" value="1">
          <span style="font-size: 24px;padding: 17px;">Bottle</span><input type="radio"  class="c" name="r_v" value="2">
          </td>
          
      </tr>
 
      <tr>
        
          <th>Day of giving</th>
          <td>
             <select  name="sel[]" class="form-control" multiple="multiple">
              <option value="Mon">Monday</option>
              <option value="Tue">Tuesday</option>
              <option value="Wed">Wednesday</option>
              <option value="Thu">Thursday</option>
              <option value="Fri">Friday</option>
              <option value="Sat">Saturday</option>
              <option value="Sun">Sunday</option>
          
          </select>
          </td>
           </tr>
           <tr>
            <th>Select delivery_boy</th>
             <td>
              <select placeholder="select delivery boy" name="db_id" class="form-control">
                <option value="">SELECT here </option>
                <?php foreach ($data as $value): ?>
                  <option value="<?php echo $value->delivery_boy_idd?>"><?php echo $value->user_name?></option>
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
          <input type="submit" value="customer registration" class=" btn btn-info form-control" name="ss">
          <!-- <input type="button"  value="Add customer" onClick="register()" class=" btn btn-info form-control"> -->
        </td>
      </tr>
      
     
      
    </table>
</form>
<script type="text/javascript">
  
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

<script>
function validateForm() {
    var fn = document.forms["myForm"]["fn"].value;
    var ln = document.forms["myForm"]["ln"].value;
    var pn = document.forms["myForm"]["pn"].value;
    var datee = document.forms["myForm"]["datee"].value;
    var price = document.forms["myForm"]["price"].value;
    var add = document.forms["myForm"]["add"].value;
    var deposit = document.forms["myForm"]["deposit"].value;
    var dog = document.forms["myForm"]["sel[]"].value;
    var deposit = document.forms["myForm"]["deposit"].value;
    var r_v = document.forms["myForm"]["r_v"].value;
 
    if (fn == "" && ln == ""&& pn == ""&& datee == ""&& price == ""&& add == ""&& deposit == "") {
        alert("Please fill all the giving filed...");
        return false;
    }
    if (r_v == "") {
        alert("Please Click on Type filed");
        return false;
    }
    if (dog == "" ) {
        alert("Please fill  day of giving filed...");
        return false;
    }
}
</script>