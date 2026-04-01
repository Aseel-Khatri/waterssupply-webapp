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
$(document).ready(function() {
    $('#myTableee').DataTable( {
         responsive: {
        details: false
    },
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
       
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

             <h1  style="color: #084365;">Delivery boy</h1>

    <br>

    <div id="mydiv">

    <div class="table-responsive">

    <table  id="myTableee" class="table   table-condensed table-striped  table-hover  ">
<thead>
      <tr>

          <th>SR</th>

          <th>User name</th>
          <th>Password</th>
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

        

         

          <td><?php echo $a->user_name?></td>
          <td><?php echo $a->pass?></td>
          

</td>

          <td>
<button data-key="<?echo $a->delivery_boy_idd?>"   onClick='edit_day_user(this)' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-edit'> Edit </span></button >&nbsp;<button data-key="<?echo $a->delivery_boy_idd?>" onClick='day_delete(this)' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'> Delete </span></button >
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
        <h4 class="modal-title">Delivery Boy</h4>
      </div>
      <div class="modal-body">
        <table id="mytable" class="table table-hover" >
                <tr>
                    <th>User name</th>
                
                </tr>
                <tr>
                    <td>
                        <input type="hidden" id="d_id">
                        <input class="form-control" type="text" id="number_id">
                    </td>
                </tr>
                <tr>
                <th>Password</th>
                </tr>
                <tr>
                    <td>
                        <input class="form-control" type="text" id="pass_id">
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
    id=$(obj).attr('data-key');
        var id=$(obj).attr('data-key');
    var myurl='<?php echo base_url('maincontroller/get_delivery_boy')?>';

    

   $.ajax({

          url: myurl,
          method:'POST',
          data: 'id='+id,
          dataType: 'JSON',

           success: function(result){ 
       
       if(result!='')
              
              {
                  $('#d_id').val(id);
                  $('#number_id').val(result[0].user_name);
                  $('#pass_id').val(result[0].pass);
                  $('#myModal').modal('show');
              
                  
              }
              
           
               
               
           }
           
           
           
       
   });
}


function update_delivery_boy()
{
    var id=$('#d_id').val();
    var num=$('#number_id').val();
    var pass=$('#pass_id').val();
    
        flag=1;
    
    
 if(id=='' || id==undefined){
        flag=0;
          swal("Warning","Something went wrong","error");
        
  } 
  
 
if(pass=='' || pass==undefined){
        flag=0;
        $('#pass_id').css('border','1px solid red');
  }

if(num=='' || num==undefined){
        flag=0;
        $('#number_id').css('border','1px solid red');
  }


  
  if(flag==1)
  {
    
    
    
    var myurl='<?php echo base_url('maincontroller/update_delivery_boy')?>';

    

   $.ajax({

          url: myurl,
          method:'POST',
          data: 'num='+num+'&id='+id+'&pass='+pass,
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

      swal({

  title: "Are you sure?",

  text: "Once deleted, you will not be able to recover this  customer!",

  icon: "warning",

  buttons: true,

  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      var myurl='<?php echo base_url('maincontroller/delete_delivery_boy')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+id,
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



