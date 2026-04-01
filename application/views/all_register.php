









<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
                dom: 'Bfrtip',
                 buttons: [
                    {
                   extend: 'print',
                   exportOptions: {
                   columns: [ 1, 2, 3] //Your Colume value those you want
                       }
                     },
                     {
                      extend: 'excel',
                      exportOptions: {
                      columns: [1, 2, 3] //Your Colume value those you want
                     }
                   },
                 ]
 
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
<thead>
      <tr>

          <th>SR</th>

          <th>Name</th>
          <th>Phone number</th>
          <!-- <th>yyy-mm-dd</th>-->
          <!--<th>Address</th>-->
          <th>Price</th>
          <!--<th>Deposit</th>-->
         <!-- <th>Type</th>-->
          <!--<th>Day of giving</th>-->
         <!-- <th>Delivery boy</th>-->
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

        

          <td>
              <a href="<?php echo base_url()?>maincontroller/detail_reg?id=<?php echo $a->id?>">
              <?php echo $a->first_name?><?php echo $a->last_name?>
              </a>

              </td>
              
          <td><?php echo $a->number?></td>
<!--
          <td><?php echo $a->datee?></td>

          <td><?php echo $a->address?></td>-->

          <td><?php echo $a->price?></td>

        <!--  <td><?php echo $a->deposit?></td>-->

          <!--<td><?php echo ($a->type==1)?'Can':'bottle'?></td>-->
<!--
          <td><button type="button" data-key="<?=$a->id?>" class="btn btn-info btn-xm" onclick="all_day(this)">Click me</button>

</td>-->

<!--<td>
    <?php echo ($a->delivery_boy=='')?'Not-assign':$a->delivery_boy;?>
    </td>-->

          <td>

            <select id="slc" class="form-control" data-key="<?=$a->id?>">

              <option value="">Action</option>

              <option value="1">Detail</option>
              <option value="10">Personal Detail</option>

              <option value="2">Edit</option>

              <!--<option value="3">Delete</option>-->

               <option value="4">In-Active</option> 
               <option value="5">Order's</option>
               <option value="6">Day of giving</option>
             <!--  <option value="7">Old Orders</option>-->

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

<!-- Modal -->
<div id="myModal" class="modal fade" data-backdrop="false" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header " style="background:#32a8cd">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <table id="mytable" class="table table-hover" >
            <thead>
                <tr>
                    <th>Days</th>
                </tr>
            </thead>
            <tbody id="tbl_body">
                
            </tbody>
        </table>
        <button id="add_btn_open" class="btn btn-warning btn-xs" onclick="add_more_day()">Click me ! add more days</button>
        <button style="display:none" id="add_btn_h" class="btn btn-warning btn-xs" onclick="add_more_h()">hide me</button>
        
        <form method="POST" action="<?php echo base_url('maincontroller/slc_add_days')?>">
        <input type="hidden" name="user_id" id="id_day">
       <table class="table" id="tblu" style="display:none;">
           <tr>
        
          <td>
             <select id="slc_val" name="sel[]" class="form-control" multiple="multiple">
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
               <td><button type="submit" class="btn btn-info">ADD days</button></td>
               </tr>
       </table>
       
       </form>
      </div>
      <div class="modal-footer">
                 <!-- <button type="button" onClick="day_user_edit()" class="btn btn-info">Edit</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="myModal_e" class="modal fade" data-backdrop="false" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header " style="background:#32a8cd">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <table id="mytable_e" class="table table-hover" >
            <thead>
                <tr>
                    <th>Days</th>
                </tr>
            </thead>
            <tbody id="tbl_body_e">
                
                   
                
               
             </select>
                        
                    </td>
                    </tr>
            </tbody>
        </table>
       
       
      </div>
      <div class="modal-footer">
                 <!-- <button type="button" onClick="day_user_edit()" class="btn btn-info">Edit</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!--order_delivery_modal-->
<div class="modal fade" id="myModal_delivery"  role="dialog" aria-labelledby="myModalLabel"  data-backdrop="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel_delivery">Modal title</h4>
      </div>
      <div class="modal-body">
          <b>Bottle blance : <span id="total_bb_delivery"></span></b>
          ||<b>Amount blance : <span id="total_ad_delivery"></span></b>
         <!-- Amount blance<span id="total_ab"></span>-->
                <table class="table-striped table-condensed">
          <tr>
            <th>Filled Dlivery</th>
            <td>
              <input type="hidden" id="hidden_delivery">
              <input type="number" class="form-control" id="fd_delivery">
            </td>
          </tr>
          <tr>
            <th>Empty Recieved</th>
            <td>
              <input type="number" class="form-control" id="er_delivery">
            </td>
          </tr>
          <tr>
            <th>Amount Rec</th>
            <td>
              <input type="number" class="form-control" id="am_rec_delivery">
            </td>
          </tr>

          
          
          

        </table>
      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="bottle_data_delivery()" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!--modal-css-->
  
<!--
   <div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container" style="background:#32a8cd;"> 
        <span onclick="document.getElementById('id01').style.display='none'" 
        class="w3-button w3-display-topright">&times;</span>
        <h2 style="color:white;">Old orders</h2>
      </header>
      <div class="w3-container">
        <table class="table">
            <tr>
            <th>Date</th>
                <td>
                  
                    <input type="date" class="form-control" id="datee_old" >
                </td>
            
            </tr>
            <tr>
            <th>Time</th>
                <td>
                    
                    <input type="time" class="form-control" id="time_old" >
                </td>
            
            </tr>
            <tr>
            <th>Filled deliver</th>
                <td>
                    <input type="hidden" id="h_id_old">
                    <input type="number" class="form-control" id="fd_old" placeholder="filled deliver">
                </td>
            
            </tr>
            <tr>
            <th>Empty recieved</th>
                <td>
       
                    <input type="number" class="form-control" id="er_old" placeholder="Empty recieved">
                </td>
            
            </tr>
            <tr>
            <th>bottle balance</th>
                <td>
       
                    <input type="number" class="form-control" id="bb_old" >
                </td>
            
            </tr>
               <tr>
            <th>Total amount</th>
                <td>
       
                    <input type="number" id="ta_old" class="form-control" >
                </td>
            
            </tr>
            
            <tr>
            <th>Amount recieved</th>
                <td>
       
                    <input type="number" id="ar_old" class="form-control" >
                </td>
            
            </tr>
            <tr>
            <th>Amount balance</th>
                <td>
       
                    <input type="number" id="ab_old" class="form-control" >
                </td>
            
            </tr>
            <tr>
                <td colspan="2">
                    <button type="button" onclick="add_old_order()" class="btn btn-info form-control">Enter</button>
                </td>
            </tr>
            
        </table>
      </div>-->
      <!--<footer class="w3-container w3-teal">
        <p>Modal Footer</p>
      </footer>-->
    </div>
  </div>
</div>
 

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<script type="text/javascript">








function add_more_day()
{ 
    $('#tblu').show();
    $('#add_btn_h').show();
     $('#add_btn_open').hide();
}
function add_more_h()
{ 
    $('#add_btn_open').show();
    $('#add_btn_h').hide();
    $('#tblu').hide();
    
}


function edit_day_user(obj)
{
    var text=$(obj).attr('text-td');
    var id=$(obj).attr('data-key');
    var v=$(obj).attr('text-td-v');
    
  $('#myModal').hide();   

$("#tbl_body_e").append("<tr><td class='my_td'><select id='slc_id'  class='form-control'><option value='"+v+"'>"+text+"</option><option value='Mon'>Monday</option><option value='Tue'>Tuesday</option><option value='Wed'>Wednesday</option><option value='Thu'>Thursday</option><option value='Fri'>Friday</option><option value='Sat'>Saturday</option><option value='Sun'>Sunday</option></select><input type='hidden' id='h_id' value='"+id+"'></td></tr><tr><td colspan='2'><button class='form-control btn btn-info' onclick='day_update()'>update</button><td></tr>");    
    $('#myModal_e').modal('show');
}


function day_update()
{
    var slc=$('#slc_id').val();
 
    var id=$('#h_id').val();
    var myurl='<?php echo base_url('maincontroller/day_update')?>';

    

   $.ajax({

          url: myurl,
          method:'POST',
          data: 'slc='+slc+'&id='+id,
          

           success: function(result){ 
       
       if(result!='')
              
              {
                  alert("Successfully updated");
                location.reload();
               
                  
              }
              
           }
   });


    
    
    
    
}



function day_delete(obj)
{

        var id=$(obj).attr('data-key');

      swal({

  title: "Are you sure?",

  text: "Once deleted, you will not be able to recover this  customer!",

  icon: "warning",

  buttons: true,

  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      var myurl='<?php echo base_url('maincontroller/day_delete')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+id,
           success: function(result){ 
         $('#myModal').hide();
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






function all_day(slc_id)
{
   /* var id=$(obj).attr('data-key');*/
   var id=slc_id;
    var myurl='<?php echo base_url('maincontroller/all_day_by_user')?>';

    

   $.ajax({

          url: myurl,
          method:'POST',
          data: 'id='+id,
          dataType: 'JSON',

           success: function(result){ 
       
       if(result!='')
              
              {
                  document.getElementById("tbl_body").innerHTML ="";
              
            for(var i =0;i < result.length;i++)
                {
                  var item = result[i];
                  var d=0;
                  if(item.days=='Mon'){d="Monday";}
                  if(item.days=='Tue'){d="Thuesday";}
                  if(item.days=='Wed'){d="Wednesday";}
                  if(item.days=='Thu'){d="Thursday";}
                  if(item.days=='Fri'){d="Friday";}
                  if(item.days=='Sat'){d="Sat";}
                  if(item.days=='Sun'){d="Sunday";}
                  
                 $("#tbl_body").append("<tr><td class='my_td'>"+d+"</td><td><button data-key='"+item.id+"' text-td-v='"+item.days+"' text-td='"+d+"' onClick='edit_day_user(this)' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-edit'> Edit </span></button >&nbsp;<button data-key='"+item.id+"' onClick='day_delete(this)' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'> Delete </span></button ></td></tr>");
                }  
                $('#id_day').val(id);
               $('#myModal').modal('show');
              }
              else
              {
              alert("d");
              }  
               
               
               
           }

          });
}

  $(document).on('change','#slc',function(){

  var slc_val=$(this).val();

  var slc_id=$(this).attr('data-key');

  if(slc_val==5)

  {

     mm_delivery(slc_id);
 
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



      var myurl='<?php echo base_url('maincontroller/in_active_cus')?>';

    

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
/*  else if(slc_val==7)

  {
   
   
     $('#h_id_old').val(slc_id);
     document.getElementById('id01').style.display='block';

  }*/
  
 else if(slc_val==6)

  {

      all_day(slc_id);
/*     window.location="<?php echo base_url()?>maincontroller/edit_user?id="+slc_id;*/

  }

  else if(slc_val==1)

  {

   window.location="<?php echo base_url()?>maincontroller/detail_reg?id="+slc_id;

  }
   else if(slc_val==10)

  {

   window.location="<?php echo base_url()?>maincontroller/all_reg_detail?id="+slc_id;

  }

/*  else

  {

    swal("Something Went wrong!", "You clicked the button!", "error");

  }
*/






  

  });



  

</script>



<!--delivery-->
<script type="text/javascript">
    function mm_delivery(slc_id)
    {
      var id=slc_id;
      var fd=$('#fd_delivery').val("");
      var er=$('#er_delivery').val("");     
     $('#hidden_delivery').val(id);

    
    var myurl='<?php echo base_url('maincontroller/today_delivery_modal_data')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'id='+id,
          dataType :'JSON',
          success: function(result){
               $('#total_bb_delivery').text('0');
               $('#total_ad_delivery').text('0');
          if(result==3)
          {
              alert("On Today !You have already supplied your Order to this customer..");
               location.reload();
          }
          
          if(result!=1)
          {
            var bb=result[0].bottle_blnc;
            if(bb==0 ||bb=='')
            {
                bb=0;
            }
            $('#total_bb_delivery').text(bb);
            var ab=result[0].amount_blnc;
            if(ab==0 ||ab=='')
            {
                ab=0;
            }
            $('#total_ad_delivery').text(ab);
                  
          }
            $('#myModal_delivery').modal();  
            }
            
   });
        
    }

    function bottle_data_delivery()
    {
        var fd=$('#fd_delivery').val();
        var er=$('#er_delivery').val();
        var id=$('#hidden_delivery').val();
        var am_rec=$('#am_rec_delivery').val();
        
     
        flag=1;
       if(fd=='' || fd==undefined)
       {
        flag=0;
        $('#fd').css('border','1px solid red');
       }
       if(er=='' || er==undefined)
       {
        flag=0;
        $('#er').css('border','1px solid red');
       }

  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/bottle_data')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'fd='+fd+'&er='+er+'&id='+id+'&am_rec='+am_rec,
          DataType:'JSON',
          
            
          
          success: function(result){ 
          $('#mydiv').load(" #mydiv > *");
            $('#myModal_delivery').hide();
           if(result==1)
           {

        swal("Registered!", "Successfully!", "success");
        setTimeout(function(){
 location.reload();}, 950);
           }
           if(result==2)
           {

 alert("On Today !You have already supplied your Order to this customer..");               location.reload();
           }
  }
});
 }

    }
    
    
/*function add_old_order()
{
    
     var fd=$('#fd_old').val();

     var er=$('#er_old').val();
     var bb=$('#bb_old').val();
     var ta=$('#ta_old').val();
     var ar=$('#ar_old').val();
     var ab=$('#ab_old').val();
     var datee=$('#datee_old').val();
     var timee=$('#time_old').val();
     var h_id=$('#h_id_old').val();
     var flag=1;

     if(fd=='' || fd==undefined)
       {
        flag=0;
        $('#fd_old').css('border','1px solid red');
       }
       
     if(er=='' || er==undefined)
       {
        flag=0;
        $('#er_old').css('border','1px solid red');
       }
       if(bb=='' || bb==undefined)
       {
        flag=0;
        $('#bb_old').css('border','1px solid red');
       }
       if(ta=='' || ta==undefined)
       {
        flag=0;
        $('#ta_old').css('border','1px solid red');
       }
       if(ar=='' || ar==undefined)
       {
        flag=0;
        $('#ar_old').css('border','1px solid red');
       }
       if(ab=='' || ab==undefined)
       {
        flag=0;
        $('#ab_old').css('border','1px solid red');
       }
       if(datee=='' || datee==undefined)
       {
        flag=0;
        $('#datee_old').css('border','1px solid red');
       }
       if(timee=='' || timee==undefined)
       {
        flag=0;
        $('#time_old').css('border','1px solid red');
       }
       
  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/add_old_order')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'fd='+fd+'&er='+er+'&h_id='+h_id+'&ar='+ar+'&ta='+ta+'&ab='+ab+'&timee='+timee+'&datee='+datee+'&bb='+bb,
          success: function(result){ 
              console.log(result);
alert("successfully addedd");
location.reload();

        
 }
   });

}
   
}*/
    
  </script>