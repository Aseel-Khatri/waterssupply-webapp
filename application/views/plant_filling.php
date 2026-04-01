
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
       responsive: false,
       
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

             <h1  style="color: #084365;">Plant's</h1>

    <br>

    <div id="mydiv">

    <div class="table-responsive">

        <table id="myTableee" class=" display responsive no-wrap table table-hover table-bordered" style="width:100%">
<thead>
      <tr>

          <th>SR</th>

          <th>Name</th>
          <th>Phone number</th>
          <th>Price</th>
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
           <a href="<?php echo base_url()?>maincontroller/plant_b_reg?id=<?php echo $a->id?>">
              <?php echo $a->name?>
             </a>  

              </td>
              
          <td><?php echo $a->number?></td>

          <td><?php echo $a->price?></td>

         <td>

            <select id="slc" class="form-control" data-key="<?=$a->id?>">

              <option value="">Action</option>

              <option value="1">Detail</option>
             <option value="10">Personal Detail</option>

              <option value="2">Edit</option>-->

              
               <option value="5">Order's</option>
               

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
            <th>Empty Delivery</th>
            <td>
              <input type="hidden" id="hidden_delivery">
              <input type="number" class="form-control" id="ed">
            </td>
          </tr>
          <tr>
            <th>Refil Recieved</th>
            <td>
              <input type="number" class="form-control" id="f_r">
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
  

    </div>
  </div>
</div>
 

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<script type="text/javascript">

  $(document).on('change','#slc',function(){

  var slc_val=$(this).val();

  var slc_id=$(this).attr('data-key');

  if(slc_val==5)

  {

     mm_delivery(slc_id);
 
  } 



  







 else if(slc_val==2)

  {

     window.location="<?php echo base_url()?>maincontroller/plant_edit_user?id="+slc_id;

  }

 

  else if(slc_val==1)

  {

   window.location="<?php echo base_url()?>maincontroller/plant_b_reg?id="+slc_id;

  }
   else if(slc_val==10)

  {

   window.location="<?php echo base_url()?>maincontroller/plant_all_reg_detail?id="+slc_id;

  }







  

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

    
    var myurl='<?php echo base_url('maincontroller/plant_order')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'id='+id,
          dataType :'JSON',
          success: function(result){
               $('#total_bb_delivery').text('0');
               $('#total_ad_delivery').text('0');
        
          
          if(result!=1)
          {
            var bb=result[0].blnc_b;
            if(bb==0 ||bb=='')
            {
                bb=0;
            }
            $('#total_bb_delivery').text(bb);
            var ab=result[0].am_blnc;
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
        var fr=$('#f_r').val();
        var ed=$('#ed').val();
        var id=$('#hidden_delivery').val();
        var am_rec=$('#am_rec_delivery').val();
        
     
        flag=1;
       if(fr=='' || fr==undefined)
       {
        flag=0;
        $('#f_r').css('border','1px solid red');
       }
       if(ed=='' || ed==undefined)
       {
        flag=0;
        $('#ed').css('border','1px solid red');
       }

  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/plant_order_auth')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'fr='+fr+'&ed='+ed+'&id='+id+'&am_rec='+am_rec,
          DataType:'JSON',
          
            
          
          success: function(result){ 
              //alert();
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