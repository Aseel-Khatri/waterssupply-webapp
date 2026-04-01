<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">-->
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2><?php echo $_SESSION['login'][0]->com_name?></h2>   
                        
                    </div>
                </div>              
                  <hr />
                <div class="row">
                <!--<div class="col-md-4">           
                <div class="panel panel-back noti-box">
                
                <div class="text-box" >
                    <p class="main-text"><i style="color: green;" class="fa fa-user-circle" aria-hidden="true"></i>Customer's</p>
                    <p class="text-muted">All Active : <?php echo count($active)?></p>
                
                </div>
             </div>
         </div>-->
                    <!--<div class="col-md-4">           
                    <div class="panel panel-back noti-box">
                <div class="text-box" >
                    <p class="main-text"><i style="color: red;" class="fa fa-user-circle" aria-hidden="true"></i>Customer's</p>
                    <p class="text-muted">All In-Active : <?php echo count($in_ac)?></p>
                
                </div>
             </div>
         </div>-->
                    <!--<div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                    <p class="main-text"><i style="color: green;" class="fa fa-motorcycle" aria-hidden="true"></i>Delivery boy</p>
                    <p class="text-muted">All : <?php echo count($db)?></p>
                
                </div>
             </div>
         </div>-->
        
         
         <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;"  href="<?php echo base_url()?>maincontroller/today_delivery" class="btn-warning btn-lg form-control"><p><i class="fa fa-qrcode " style="font-size:25px;color:White;margin-left:78px;"></i></p> <span style="margin-left: 30px;">Today Deliveries : <?php print_r (count($today_d))?></span></a>
                
                </div>
             </div>
         </div>
         
         




 <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;background:#616754;"  href="<?php echo base_url()?>maincontroller/users" class="btn-info btn-lg form-control"><p><i class="fa fa-user-circle" style="font-size:25px;color:White;margin-left:86px;"></i></p> <span style="margin-left: 30px;">Customer registration</span></a>
                
                </div>
             </div>
         </div>









 <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;"  href="<?php echo base_url()?>maincontroller/all_register" class="btn-info btn-lg form-control"><p><i class="fa fa-user-circle" style="font-size:25px;color:green;margin-left:78px;"></i></p> <span style="margin-left: 30px;">Active customer : <?php echo count($active)?></span></a>
                
                </div>
             </div>
         </div>
          <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;background:darkseagreen"  href="<?php echo base_url()?>maincontroller/inactive_register" class="btn-info btn-lg form-control"><p><i class="fa fa-user-circle" style="font-size:25px;color:red;margin-left:78px;"></i></p> <span style="margin-left: 30px;">In-Active customer : <?php echo count($in_ac)?></span></a>
                
                </div>
             </div>
         </div>

         <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px; background:darkgray;"  href="<?php echo base_url()?>maincontroller/add_delivery_boy" class="btn-warning btn-lg form-control"><p><i class="fa fa-desktop  " style="font-size:25px;color:White;margin-left:78px;"></i></p> <span style="margin-left: 30px;">Add Delivery boy </span></a>
                
                </div>
             </div>
         </div>
            <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px; background:brown"  href="<?php echo base_url()?>maincontroller/all_delivery_boy" class="btn-warning btn-lg form-control"><p><i class="fa fa-motorcycle  " style="font-size:25px;color:White;margin-left:78px;"></i></p> <span style="margin-left: 30px;">All Delivery boy : <?php echo count($db)?></span></a>
                
                </div>
             </div>
         </div>
         

 <div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;background:#0e3c65;"  href="<?php echo base_url()?>maincontroller/admin_view" class="btn-info btn-lg form-control"><p><i class="fa fa-address-card-o" style="font-size:25px;color:White;margin-left:86px;"></i></p> <span style="margin-left: 30px;">Sales</span></a>
                
                </div>
             </div>
         </div>

 <!--<div class="col-md-4">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                  <a style="height:86px;background:#5bc0de;"  href="<?php echo base_url()?>maincontroller/detail_sale_report" class="btn-info btn-lg form-control"><p><i class="fa fa-file" style="font-size:25px;color:White;margin-left:86px;"></i></p> <span style="margin-left: 30px;">Sales Detail</span></a>
                
                </div>
             </div>
         </div>
-->
             
        <!--start Google chart-->
          <!--<div class="col-md-12">
              
              <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Orders'],
          ['Total Amount',  <?php print_r($gc[0]->ta);?>],
          ['Total recieved',       <?php print_r($gc[0]->ar)?>],
           ['Total balance',       <?php print_r($gc[0]->ab)?>],
         
          
        ]);

        var options = {
             width: '100%',
             height: '400',
             
          title: 'My Daily Activities',
          is3D: true,
          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }

    </script>
  
  
    <div id="piechart_3d" ></div>

          </div>-->
          
         <!-- End Google chart-->
         
         
        <!--            <div class="col-md-3 ">           
      <div class="panel panel-back noti-box">
                <div class="text-box" >
                    <p class="main-text"><i class="fa fa-money" aria-hidden="true"></i>Amount's</p>
                    <p class="text-muted">Total-amount : 2000</p>
                
                </div>
             </div>
         </div>-->
      </div>
              