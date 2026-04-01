<!--data ota liya hai cutomer id heddin main hai or primary id=r_id main hai bs data otana hai or save krana hai.. sale main bhe changes hnge-->
<style type="text/css">
  .s_td{
  font-weight;color:brown;font-weight: bold;font-size: 15px;
  }
</style>
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
        responsive: false,
       
           } );
} );



</script>
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
        
    <br>
    <br>
    <div id="mydiv">
<input type="hidden" id="g_id" value="<?php echo @$_GET['id']?>">
    <div class="table-responsive">
    <table id="myTableee" class="table  table-bordered table-condensed table-striped  table-hover  ">
      <thead>
      <tr>
          <th>SR</th>
          <th>Date</th>
          <th>Empty Deliver</th>
          <th>Refil Rec</th>
           <th>bottle Blnc</th>
          <th>Amount</th>
          <th>Amount rec</th>
          <th>Amount blnc</th>
          <th>Total Amount</th>
          
          
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
      <tr class="table">
         <td><?php echo $index++?></td>
         <td class="datee"><?php echo $a->datee?>
         </td>
          <td class="fd"><?php echo $a->m_t_b?></td>
          <td class="er"><?php echo $a->refil_b?></td>
          <td class="bb"><?php echo $a->blnc_b?></td>
          <td class="am"><?php echo $a->amount?></td>
          <td class="ar"><?php echo $a->amount_rec?></td>
          <td class="ab"><?php echo $a->am_blnc?></td>
            <td class="ta"><?php echo $a->t_am?></td>
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
  <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel"  data-backdrop="false" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <table class="table-striped table-condensed">
          <tr>
            <th>Filled Dlivery</th>
            <th>Empty Recieved</th>
           </tr>
           <tr>
            <td>
              <input type="hidden" id="hidden">
              <input type="hidden" id="r_id">
              <input type="hidden" id="datee">
              <input type="hidden" id="bd">
              <input type="number" class="form-control" id="fd">
              <input type="hidden" class="form-control" id="fdd">
            </td>
            
            <td>
              <input type="number" class="form-control" id="er">
               <input type="hidden" class="form-control" id="err">
            </td>
          </tr>
          <tr>
              <th>Amount</th>
            <th>bottle blnc</th>
           </tr>
           <tr>
            <td>
              <input type="number" class="form-control" id="amount">
              <input type="hidden" class="form-control" id="amountt">
            </td>
             
            <td>
              <input type="number" class="form-control" id="bb">
              <input type="hidden" class="form-control" id="bbb">
            </td>
          </tr>
                <tr>
            <th>Amount rec</th>
            <th>Amount  blnc</th>
            </tr>
            <tr>
           
            <td>
              <input type="number" class="form-control" id="ar">
               <input type="hidden" class="form-control" id="arr">
            </td>
             
            <td>
              <input type="number" class="form-control" id="ab">
              <input type="hidden" class="form-control" id="abb">
            </td>
          </tr>
                <tr>
            <th>Total amount</th>
            <td>
              <input type="number" class="form-control" id="ta">
              <input type="hidden" class="form-control" id="taa">
            </td>
            
          </tr>
          

          
          
          

        </table>
      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="detail_edit()" class="btn btn-primary">ADD</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

  $("table tr").last().css('color','#0000ff');
 // $("table td").last().css('background','#0000ff');
 $(document).ready(function(){
var c= $("tr:last td:eq(3)").text();

 });


 /*function deletee(obj)
 {

  var id=$(obj).attr('datakey');

 swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this  record!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

  var myurl='<?php echo base_url('maincontroller/detail_user_delete')?>';
    
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'id='+id,
           success: function(result){ 
         $('#mydiv').load(" #mydiv > *");
       }
          });

    swal("Poof! Your record has been deleted!", {
      icon: "success",
      
    });

   
  } 
  else {
    swal("Your record is safe!");
  }


});

 }*/


/*$("tr.table").click(function() {
    var tableData = $(this).children("td").map(function() {
        return $(this).text();
    }).get();

var id=$('#g_id').val();
if(id!='')
{
$('#fd').val($.trim(tableData[3]));
$('#er').val($.trim(tableData[4]));
$('#bb').val($.trim(tableData[5]));
$('#amount').val($.trim(tableData[6]));
$('#ar').val($.trim(tableData[7]));
$('#ab').val($.trim(tableData[8]));
$('#ta').val($.trim(tableData[9]));
$('#hidden').val(id);


    $('#myModal').modal('show');
}
    
});*/

$(".use-address").click(function() {
var id=$('#g_id').val();
if(id!='')
{

   var $row = $(this).closest("tr");    // Find the row
   var $fd = $row.find(".fd").text(); // Find the text
   var $er = $row.find(".er").text(); 
   var $bb = $row.find(".bb").text(); 
   var $am = $row.find(".am").text();  
   var $ab = $row.find(".ab").text(); 
   var $ar = $row.find(".ar").text();     
   var $ta = $row.find(".ta").text(); 
   var $bd = $row.find(".bd").text(); 
    var $datee = $row.find(".datee").text(); 
var r_id=$(this).attr('datakey');        
$('#fd').val($fd);
$('#bd').val($bd);
$('#datee').val($datee);
$('#er').val($er);
$('#bb').val($bb);
$('#amount').val($am);
$('#ar').val($ar);
$('#ab').val($ab);
$('#ta').val($ta);
$('#hidden').val(id);
$('#r_id').val(r_id);
$('#fdd').val($fd);
$('#err').val($er);
$('#bbb').val($bb);
$('#amountt').val($am);
$('#arr').val($ar);
$('#abb').val($ab);
$('#taa').val($ta);



    $('#myModal').modal('show');   
}
    
});
 
 function detail_edit()
 {
    var fd=$('#fd').val();
    var datee=$('#datee').val();
    var er=$('#er').val();
    var bb=$('#bb').val();
    var am=$('#amount').val();
    var ar=$('#ar').val();
    var ab=$('#ab').val();
    var ta=$('#ta').val();
    var bd=$('#bd').val();
    
    var fdd=$('#fdd').val();
    var err=$('#err').val();
    var bbb=$('#bbb').val();
    var amm=$('#amountt').val();
    var arr=$('#arr').val();
    var abb=$('#abb').val();
    var taa=$('#taa').val();
    
    var h_id=$('#hidden').val();
    var r_id=$('#r_id').val();
    var myurl='<?php echo base_url('maincontroller/detail_edit')?>';
   
   $.ajax({
          url: myurl,
          method:'POST',
          data:'fd='+fd+'&er='+er+'&h_id='+h_id+'&ar='+ar+'&ta='+ta+'&ab='+ab+'&am='+am+'&datee='+datee+'&bb='+bb+'&r_id='+r_id+'&fdd='+fdd+'&err='+err+'&arr='+arr+'&taa='+taa+'&abb='+abb+'&amm='+amm+'&bd='+bd+'&bbb='+bbb,
          success: function(result){ 
              console.log(result);
              if(result==1)
              {
alert("successfully addedd");
location.reload();
}
else
{
    alert("Something went wrong..");
location.reload();
}
        
 }
   });

   



 }
 
</script>

