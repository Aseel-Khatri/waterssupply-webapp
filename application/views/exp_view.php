<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>-->
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
      
        dom: 'lBfrtip',
    buttons: [
        'copy', 'csv','pdf', 'print'
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

                        <h5>Welcome Jhon Deo , Love to see you back. </h5>

                    </div>

                </div>              

                 <!-- /. ROW  -->

                  <hr />

             <h1  style="color: #084365;">Epenses</h1>
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
<form method="POST" action="<?php echo base_url()?>/maincontroller/exp_view">
<div class="row">
<div class="col-md-12">
<div class="col-md-3">
    
    From <input type="date" data-date-format="yyyy-mmmm-dd" name="fdate" class="form-control">
</div>
<div class="col-md-3">
    To <input type="date" name="todate" class="form-control">
</div>
<div class="col-md-3">
    
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

        <table id="myTableee" class="table table-hover table-bordered" style="width:100%">
<thead>
      <tr>

          <th>SR</th>

           <th>Day/ Month/ Year</th>
           <th>Expense Name</th>
           <th>Price</th>


      
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
          <td><?php echo $a->fdatee?></td>
          <td><?php echo $a->username?></td>
          <td class="price"><?php echo $a->price?></td>
          
          
          


      </tr>

      <?php

    }

  }

    ?>

      

      
</tbody>
     <tfoot>
      <tr>
          
         
         <td colspan="3"></td>
     
      <td style="background:#c7c5c1;color:blue;" id="price">0</td>
     
      
      
      
      
     </tr>
     </tfoot>

      

    </table>
    
    
    

</div>

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
<script>

    function calculateSum_fd()
    {
        var sum = 0;

$(".price").each(function() {

    var value = $(this).text();
    // add only if the value is number
    if(!isNaN(value) && value.length != 0) {
        sum += parseFloat(value);
    }
});
    
        $('#price').text(sum);
        
    }
    
    $(document).ready(function(){
    $('.price').each(function() {
        calculateSum_fd();
    });
});
    
    
    $(document).ready(function (){
	var myMaxSelAllowed=5;
	$('select#my_list').click(function(evt){
                if (!evt.toElement.selected)return;
		var cmpItemSel=0;
		$("select#my_list option").each(function(id,val){
			cmpItemSel+=+(val.selected);
		})
		if (cmpItemSel>myMaxSelAllowed)evt.toElement.selected=false;
	});
})
    
    
</script>
