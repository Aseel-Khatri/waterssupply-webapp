<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--order_delivery_modal-->
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
             <h1  styel="color: #084365;">Order Day  
             <?php $d=date('D');
             if($d=='Thu'){echo "Thursday";}if($d=='Fri'){echo "Friday";}
             if($d=='Sat'){echo "Saturday";}if($d=='Sun'){echo "Sunday";}
             if($d=='Mon'){echo "Monday";}if($d=='Tue'){echo "Tuesday";}
             if($d=='Wed'){echo "Wednesday";}?></h1>
    <br>
    <div id="mydiv">
    <div class="table-responsive">
    <table id="myTableee" class="table   table-condensed table-striped  table-hover ">
        <thead>
      <tr>
          <th>SR</th>
          <th>ID</th>
          <th>Name</th>
          <th>Phone number</th>
          
          <th>Address</th>
          <th>Type</th>
           <th>Date</th>
          <th>Status</th>
          <th>order</th>
          
      </tr>
      </thead>
      <tbody>
     
        <?php
        if(empty($data))
         {
          echo '<h1>There is no record</h1>';
        
         }
         else
         {
        $index=1;
          foreach ($data as $a) {
          ?>
      <tr>
         <td><?php echo $index++?></td>
         <td><?php echo $a->id?></td>
          <td>
               <a href="<?php echo base_url()?>maincontroller/detail_reg?id=<?php echo $a->id?>">
              <?php echo $a->first_name?><?php echo $a->last_name?>
              </a>
              </td>
          <td><?php echo $a->number?></td>
          <td><?php echo $a->address?></td>
          <td><?php echo ($a->type==1)?'Can':'bottle'?></td>
          <td><?php echo $a->datee?></td>
<td><span class="alert-danger">Not completed</span></td>

          <td><button datakey="<?=$a->id?>" type="button" class="btn btn-info btn-sm" onclick="mm(this)">orders</button>
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


  <!-- Modal -->
  <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel"  data-backdrop="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
          <b>Bottle blance : <span id="total_bb"></span></b>
          ||<b>Amount blance : <span id="total_ad"></span></b>
         <!-- Amount blance<span id="total_ab"></span>-->
                <table class="table-striped table-condensed">
          <tr>
            <th>Filled Dlivery</th>
            <td>
              <input type="hidden" id="hidden">
              <input type="number" class="form-control" id="fd">
            </td>
          </tr>
          <tr>
            <th>Empty Recieved</th>
            <td>
              <input type="number" class="form-control" id="er">
            </td>
          </tr>
          <tr>
            <th>Amount Rec</th>
            <td>
              <input type="number" class="form-control" id="am_rec">
            </td>
          </tr>

          
          
          

        </table>
      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="bottle_data()" class="btn btn-primary">ADD</button>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript">
    function mm(obj)
    {
      var id=$(obj).attr('datakey');
      var fd=$('#fd').val("");
      var er=$('#er').val("");
      
     $('#hidden').val(id);

    
    var myurl='<?php echo base_url('maincontroller/today_delivery_modal_data')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'id='+id,
          dataType :'JSON',
          success: function(result){
               $('#total_bb').text('0');
               $('#total_ad').text('0');
          if(result!=1)
          {
            var bb=result[0].bottle_blnc;
            if(bb==0 ||bb=='')
            {
                bb=0;
            }
            $('#total_bb').text(bb);
            var ab=result[0].amount_blnc;
            if(ab==0 ||ab=='')
            {
                ab=0;
            }
            $('#total_ad').text(ab);
                  
          }
            $('#myModal').modal();  
            }
            
   });
        
    }

    function bottle_data()
    {
        var fd=$('#fd').val();
        var er=$('#er').val();
        var id=$('#hidden').val();
        var am_rec=$('#am_rec').val();
       
     
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
          
          
            
          
          success: function(result){ 
          /*    console.log(result);*/
          
          $('#mydiv').load(" #mydiv > *");
            $('#myModal').hide();

        swal("Registered!", "Successfully!", "success");
        setTimeout(function(){
                                   
                            
 location.reload();}, 950);
          
  }
});
 }

    }
  </script>