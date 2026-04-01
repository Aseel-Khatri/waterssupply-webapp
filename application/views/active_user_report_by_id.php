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
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
                dom: 'Bfrtip',
        buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
        ]
           } );
} );



</script>


        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-3">
         <div class="alert alert-success">
    <strong>Name</strong> <span style="color: #608cce;font-size: 16px;"><?php echo @$namee[0]->first_name.'&nbsp;'.@$namee[0]->last_name;?></span>
  </div>                      
  
  </div>
            <div class="col-md-3">
                         <div class="alert alert-success">
    <strong>Number</strong> <span style="color: #608cce;font-size: 16px;"><?php echo @$namee[0]->number;?></span>
  </div>
    
  </div>
     
                </div>              
                 <!-- /. ROW  -->
                  <hr />

    <br>
    <br>
    <div id="mydiv">

    <div class="table-responsive">
    <table id="myTableee" class="table  table-bordered table-condensed table-striped  table-hover  ">
      <thead>
      <tr>
          <th>SR</th>
          <th>Date/Time</th>
          <th>Deliver by</th>
          <th>fiiled Deliver</th>
          <th>Empty Recieved</th>
           <th>bottle Balance</th>
          <th>Total Amount</th>
          <th>Amount recieved</th>
          <th>Amount balance</th>
          <!--<th>Action</th>-->
          
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
         <td><?php echo $a->datee.'/'.$a->timee?></td>
          <td class="fd"><?php echo $a->bottle_deliver?></td>
          <td class="fd"><?php echo $a->filled_deliver?></td>
          <td class="er"><?php echo $a->empty_recieved?></td>
          <td class="bb"><?php echo $a->bottle_blnc?></td>
          <td class="ta"><?php echo $a->total_amount?></td>
          <td class="ar"><?php echo $a->amount_rec?></td>
          <td class="ab"><?php echo $a->amount_blnc?></td>
          
      </tr>
      <?php
    }
    
}
    ?>
    </tbody>
      
      <tfoot>
      <tr>
          
         
         <td colspan="2"></td>
      <td  style="background:#c7c5c1;color:blue;">Total's</td>
       
      <td style="background:#c7c5c1;color:blue;" id="fd">0</td>
      <td style="background:#c7c5c1;color:blue;" id="er">0</td>
      <td style="background:#c7c5c1;color:blue;" id="bb">0</td>
      <td style="background:#c7c5c1;color:blue;" id="ta">0</td>
      <td style="background:#c7c5c1;color:blue;" id="ar">0</td>
      <td style="background:#c7c5c1;color:blue;" id="ab">0</td>
      
      
      
      
     </tr>


      

     </tfoot>

     
      
    </table>
</div>
</div>
<div class="col-md-4 pull-right">
    <table id="myTableee" class="table  table-bordered table-condensed table-striped  table-hover  ">
    <tr>
          
         
         
      <td  style="background:#428bca;color:white;">Empty Recieved - bottle balance</td>
       
      <td style="background:#c7c5c1;color:White;"id="erbb">0</td>
       <td  style="background:#428bca;color:white;">Amount Recieved - Amount balance</td>
      <td style="background:#c7c5c1;color:White;"id="arab">0</td>
         </tr>
</table>
</div>
</div>
</div>
</div>
</div>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js">
</script>
<script type="text/javascript">


    function calculateSum_fd()
    {
        var sum = 0;

$(".fd").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#fd').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.fd').each(function() {
        calculateSum_fd();
    });
});
    function calculateSum_er()
    {
        var sum = 0;

$(".er").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#er').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.er').each(function() {
        calculateSum_er();
    });
});
    function calculateSum_bb()
    {
        var sum = 0;

$(".bb").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#bb').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.bb').each(function() {
        calculateSum_bb();
    });
});
    function calculateSum_ta()
    {
        var sum = 0;

$(".ta").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#ta').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.ta').each(function() {
        calculateSum_ta();
    });
});
    function calculateSum_ar()
    {
        var sum = 0;

$(".ar").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#ar').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.ar').each(function() {
        calculateSum_ar();
    });
});
    function calculateSum_ab()
    {
        var sum = 0;

$(".ab").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#ab').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.fd').each(function() {
        calculateSum_ab();
    });
});

$(function() {
  var b=$('#bb').text();
  var e=$('#er').text();
  var t=(b*1)-(e*1); 
   $('#erbb').text(t);
var ab=$('#ab').text();
var ar=$('#ar').text();
      var tt=(ab*1)-(ar*1); 
   $('#arab').text(tt);
});



</script>

