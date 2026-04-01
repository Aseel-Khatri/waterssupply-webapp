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
        "dom": '<"pull-right"f><"pull-bottom"l>tip',
        "pagingType": "full_numbers",
                dom: 'Bfrtip',
        buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
        ]
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
    <form method="POST" action="<?php echo base_url()?>/maincontroller/admin_view">
<div class="row">
<div class="col-md-12">
<div class="col-md-3">

    From <input type="date"  name="fdate" class="form-control" value='<?php echo $today;?>' >
</div>
<div class="col-md-3">
    To <input type="date" name="todate" class="form-control">
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
    <table  width="100%" id="myTableee" class="table   table-condensed table-striped  table-hover ">
        <thead>
      <tr>
          <td>SR</td>
          <th>Date</th>
          <th>Type</th>
          <th>Filled deliver</th>
          <th>Empty Rec</th>
          <th>bottle blnc</th>
          <th>Amount Rec</th>
          <th>Amount blnc</th>
          <th>Total amount</th>
           <!--<th>Date wise</th>-->
          
          
          
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
         <td width="5%"><?php echo $index++?></td>
          <td width="10%"><?php echo $a->dte?></td>
          <td width="5%"><?php  if($a->type==1){echo "Can";}
                     if($a->type==2){echo "Bottle";}
                     if($a->type==3){echo "Counter Sale";} ?></td>
         <td width="20%" class="fd"><?php echo $a->filled_deliver?></td>
          <td width="20%" class="er"><?php echo $a->empty_rec?></td>
           <td width="20%"class="bb"><?php echo $a->bb?></td>
          <td width="20%" class="tr"><?php echo $a->ta_rec?></td>
          <td width="20%"class="ab"><?php echo $a->amount_blnc?></td>
          <td width="20%"class="ta"><?php echo $a->date_wise?></td>
         
          

      </tr>
      <?php
    }
  }
    ?>
    </tbody>
    <tfoot>
      <tr>
          
          <td colspan="2"></td>
      <td style="color:blue;">Total's</td>
      <td style="color:blue;" id="fd">0</td>
      <td style="color:blue;" id="er">0</td>
      <td style="color:blue;" id="bb">0</td>
      <td style="color:blue;" id="tr">0</td>
      <td style="color:blue;" id="ab">0</td>
      <td style="color:blue;" id="ta">0</td>
      
      
      
     </tr>
     </tfoot>
      
    </table>
  </div>
</div>
</div>


<script>

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

    
    


    function calculateSum_tr()
    {
        var sum = 0;

$(".tr").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#tr').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.tr').each(function() {
        calculateSum_tr();
    });
});

    
    

        
    function calculateSum()
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
        calculateSum();
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
    $('.ab').each(function() {
        calculateSum_ab();
    });
});

      function calculateSumbb()
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
        calculateSumbb();
    });
});

  
    

</script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js">
</script>

  