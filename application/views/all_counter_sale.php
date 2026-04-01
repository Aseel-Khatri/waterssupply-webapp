<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--order_delivery_modal-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<link href=="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<style>
   .dataTables_filter {
   float: right !important;
}
</style>
<script  type="text/javascript">
/*$(document).ready(function() {
    $('#myTableee').DataTable( {
         responsive: {
        details: false
    },
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
       
           } );
} );*/


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

             <h1  style="color: #084365;">Counter sale</h1>



               <?php
    if(isset($_POST['btn_post']))
    {
        
   echo '<h1  style="color: #084365;">Records</h1>';
    }
    else
    {
        	date_default_timezone_set("Asia/Karachi");
        $d=date('d/M/Y');
        echo '<h1  style="color: #084365;">Today Records &nbsp;&nbsp;'.$d.'</h1>';
        
    }
   ?>
            
            
             
    <br>
    <form method="POST" action="<?php echo base_url()?>/maincontroller/counter_sale_filter">
<div class="row">
<div class="col-md-12">
<div class="col-md-3">

    Filter <input type="date"  name="fdate" class="form-control" value='<?php echo $today;?>' >
</div>
<div class="col-md-2">
    <input type="Submit" name="btn_post" style="margin-top: 19px;" class="form-control btn-info">
</div>
</div>
</div>
</form>
<br>
 
    <div id="mydiv">

    <div class="table-responsive">

    <table  id="myTableee" class="table   table-condensed table-striped  table-hover  ">
<thead>
      <tr>

          <th>SR</th>

          <th>Date</th>
          <th>Amount</th>
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

        

         

          <td><?php echo $a->dt?></td>
          <td><?php echo $a->amount?></td>
          

</td>

          <td>
<button data-key="<?echo $a->id?>" data-dt="<?echo $a->datee?>"  data-am="<?echo $a->amount?>"   onClick='edit_day_user(this)' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-edit'> Edit </span></button ><!--&nbsp;<button data-key="<?echo $a->id?>" data-am="<?echo $a->amount?>" data-dt="<?echo $a->datee?>" onClick='day_delete(this)' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'> Delete </span></button >-->
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
        <h4 class="modal-title">Counter Sale</h4>
      </div>
      <div class="modal-body">
        <table id="mytable" class="table table-hover" >
                <tr>
                    <th>Amount</th>
                
                </tr>
                <tr>
                    <td>
                        <input type="hidden" id="id">
                        <input class="form-control" type="text" id="amount">
                        <input  type="hidden" id="amount_old">
                        <input  type="hidden" id="datee">
                    </td>
                </tr>
                
                 <tr>
                     <td colspan=2>
                         <button class="btn btn-info form-control" onclick="update_delivery_boy()">Update</button>
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

<script type="text/javascript">

function edit_day_user(obj)
{

        var id=$(obj).attr('data-key');
        var am=$(obj).attr('data-am');
        var datee=$(obj).attr('data-dt');
    
                  $('#id').val(id);
                  $('#amount').val(am);
                  $('#amount_old').val(am);
                  $('#datee').val(datee);
                  $('#myModal').modal('show');
}


function update_delivery_boy()
{
    var id=$('#id').val();
    var num=$('#amount').val();
    var datee=$('#datee').val();
    var am_old=$('#amount_old').val();

        flag=1;
    
    
 if(id=='' || id==undefined){
        flag=0;
          swal("Warning","Something went wrong","error");
        
  } 
  
 

if(num=='' || num==undefined){
        flag=0;
        $('#number_id').css('border','1px solid red');
  }


  
  if(flag==1)
  {
    
    
    
    var myurl='<?php echo base_url('maincontroller/update_counter_sale')?>';

    

   $.ajax({

          url: myurl,
          method:'POST',
          data: 'num='+num+'&id='+id+'&am_old='+am_old+'&datee='+datee,
           success: function(result){ 
       
  if(result==1)
            {
              alert("Successfully updated...");
        location. reload(true);
            }
              if(result==2)
            {
             swal("Warning","Please try different number","error");
               $('#number_id').css('border','1px solid red');
        
            }
              
           }
   });

}
    
    
    
    
}



function day_delete(obj)
{

        var id=$(obj).attr('data-key');
        var am=$(obj).attr('data-am');
        var datee=$(obj).attr('data-dt');
        

      swal({

  title: "Are you sure?",

  text: "Once deleted, you will not be able to recover this  customer!",

  icon: "warning",

  buttons: true,

  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      var myurl='<?php echo base_url('maincontroller/delete_counter_sale')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+id+'&am='+am+'&datee='+datee,
           success: function(result){ 
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

</script>


