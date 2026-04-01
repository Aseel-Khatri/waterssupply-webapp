<!DOCTYPE html>
<?php
date_default_timezone_set("Asia/Karachi");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
     <link rel="icon" type="image/gif/png" href="<?php echo base_url()?>assets/img/bottle.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Water-suplier</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo base_url();?>assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
  
        <!-- CUSTOM STYLES-->
    <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<style type="text/css">
    .cc{background-color: blue;}
#wrapper {
    width: 100%!important;
    background: #ffffff!important;
}
.sidebar-collapse .nav > li > a {
    color: #fff;
    background: #0e3c65;
    text-shadow: none;
}
.navbar-header {
    background: #a2c9ea!important;
}
.sidebar-collapse .nav > li > a:hover { 
    background-color: #a2c9ea!important;
}
.navbar-toggle:focus {
    background-color: #0e3c65!important;
}

</style>
<body>
    <div id="wrapper">
        <nav class="navbar  navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0;background: #a2c9ea;">
            <div class=" navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a style="background: #0e3c65;border:#0E3C65 solid 1px;"class="  navbar-brand" href="<?php echo base_url();?>index.html"> Admin</a> 
            </div>
        
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> <?php 
if(@$_SESSION['login'][0]->end_datee!='')
{
$date = new DateTime($_SESSION['login'][0]->end_datee);

     echo "Expire date :".$date->format('d/M/Y').'&nbsp;&nbsp;';}?><?php date_default_timezone_set("Asia/Karachi"); echo "Today date : ".date('D/M/Y')?>&nbsp;<button id="m_p" class="btn btn-xs">change password</button> <a style="background:#0e3c65; color:white;" href="<?php echo base_url();?>maincontroller/logout" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
		<?php
                $type=$_SESSION['login'][0]->type;
		        if($type==1)
                {
                ?>
					
                    <li>
                        <a href="<?php echo base_url();?>maincontroller/index"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    
                    </li>		
			<?php
                }
                ?>
				<li>
                        <a  href="<?php echo base_url();?>maincontroller/today_delivery"><i class="fa fa-qrcode fa-3x"></i> Today Delivery</a>
                    </li>
				
				<?php
            //    $type=$_SESSION['login'][0]->type;
                if($type==1)
                {
                ?>
					
                <!--    <li>
                        <a href="<?php echo base_url();?>maincontroller/index"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    
                    </li>
                -->    
                    <li>
                        <a  href="<?php echo base_url();?>maincontroller/users"><i class="fa fa-user-circle-o fa-3x"></i>Customer registration</a>
                    </li>
                      <li>
                        <a href="<?php echo base_url();?>maincontroller/event"><i class="fa fa-sitemap fa-3x"></i> Customer's<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/all_register">Active</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/inactive_register">In-Avtive</a>
                            </li>
                           <!-- <li>
                                <a href="<?php echo base_url();?>maincontroller/card">Cards</a>
                            </li>-->
                                </ul>  
                  </li>
	
                     <li>
                        <a  href="<?php echo base_url();?>maincontroller/add_delivery_boy"><i class="fa fa-desktop fa-3x"></i>Add Delivery boy</a>
                    </li>
                     <li>
                        <a  href="<?php echo base_url();?>maincontroller/all_delivery_boy"><i class="fa fa-motorcycle"  style="font-size:30px;color:White"></i>ALL Delivery boy</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>maincontroller/counter_sale"><i class="fa fa-address-card-o"  style="font-size:30px;color:White"></i>Counter sale</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>maincontroller/all_counter_sale"><i class="fa fa-address-card-o"  style="font-size:30px;color:White"></i> All Counter sales</a>
                    </li>
                         
                   <li>
                        <a href="<?php echo base_url();?>maincontroller/event"><i class="fa fa-sitemap fa-3x"></i>Expenses<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/expense">Add</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/exp_view">view</a>
                            </li>
                            <!--<li>
                                <a href="<?php echo base_url();?>maincontroller/plant_filling">Plant filling </a>
                            </li>
                             <li>
                                <a href="<?php echo base_url();?>maincontroller/plant_detail_sale_report">Plant report </a>
                            </li>-->
                           
                                </ul>  
                  </li>


               <li>
                        <a href="<?php echo base_url();?>maincontroller/event"><i class="fa fa-file fa-3x"></i> Reports<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                        <a href="<?php echo base_url();?>maincontroller/admin_view">Sales</a>
                         </li>
                         
                            <li>
                         <a href="<?php echo base_url();?>maincontroller/detail_sale_report">Detail sale</a>
                         </li>
                           <!-- <li>
                         <a href="<?php echo base_url();?>maincontroller/am_blnc_report">Balance/records</a>
                         </li>
                            -->
                            
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/active_user_report">Active user's</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/inactive_register">In-Avtive users</a>
                            </li>
                         
                         <li>
                         <a href="<?php echo base_url();?>maincontroller/delivery_boy_report">Delivery boy</a>
                         </li>
                         
                           <!-- <li>
                                <a href="<?php echo base_url();?>maincontroller/card">Cards</a>
                            </li>-->
                                </ul>  
                  </li>
                   <li>
                        <a href="<?php echo base_url();?>maincontroller/event"><i class="fa fa-sitemap fa-3x"></i>Plant<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/plant_reg">Registration</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>maincontroller/plant_filling">Plant filling </a>
                            </li>
                             <li>
                                <a href="<?php echo base_url();?>maincontroller/plant_detail_sale_report">Plant report </a>
                            </li>
                           
                                </ul>  
                  </li>


                    <?php
                }
                 ?>
         
                    
		          
                  <?php
                  if($type==1)
                  {
                    ?>
        				   <!--<li>
                        <a class="active-menu"   href="<?php echo base_url();?>maincontroller/admin_view"><i class="fa fa-bar-chart-o fa-3x"></i> Views</a>
                    </li>	-->
                      <!--<li  >
                        <a  href="<?php echo base_url();?>maincontroller/table"><i class="fa fa-table fa-3x"></i> Contact</a>
                    </li>
                    <li  >
                        <a  href="<?php echo base_url();?>maincontroller/form"><i class="fa fa-edit fa-3x"></i> Digital Wallet Customer </a>
                    </li>				
					<li  >
                        <a   href="<?php echo base_url();?>maincontroller/login"><i class="fa fa-bolt fa-3x"></i> Login</a>
                    </li>-->   	
                    <!--  <li  >
                        <a   href="<?php echo base_url();?>registeration.php"><i class="fa fa-laptop fa-3x"></i> Registeration</a>
                    </li> -->	
					                   
                    <!--<li>
                        <a href="<?php echo base_url();?>#"><i class="fa fa-sitemap fa-3x"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                  </li>     
                  <li  >
                        <a  href="<?php echo base_url();?>maincontroller/blank"><i class="fa fa-square-o fa-3x"></i> Blank Page</a>
                    </li>-->
                    <?php
                    }
                    ?>	
                </ul>
               
            </div>
            
        </nav>  
        
        
        
         <div class="modal fade" id="modalpass" data-backdrop="false" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background: #0e3c65">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color:White;">Password Change</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-12">
               <input placeholder=" Enter a old password" type="text" class="form-control" id="old_pas">
                  <span id="olspan" style="display:none;color:red;">Password is not match!</span>
                  <input type="hidden" value="<?php echo $_SESSION['login'][0]->password;?>" id="h_old_pas">
          </div>
          <div class="col-md-12">
                    
                   <input placeholder="New password" type="text" style="display:none;" class="form-control" id="n_pas">
                    <span id="sp" style="display:none;color:red;">Password is not match!</span>
          </div>
          <br>
          <div class="col-md-12">
                    
                    <input placeholder="Confirm password" type="text" style="display:none;margin-top:12px;" class="form-control" id="c_pas">
          </div>
          <div class="col-md-12">
                    
                    <button style="margin-top:12px;display:none;" id="btn_up" class="btn btn-xs btn-info form-control">Update</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
        
    <script>
        $('#m_p').on('click',function(){
           $('#modalpass').modal();
        });
        
        $(document).on('keyup','#old_pas',function(){
           var pass=$('#old_pas').val();
           var h=$('#h_old_pas').val();
           if(h!=''&& h!=undefined)
           {
           if(h!=pass)
           {
               $('#old_pas').css('border','1px solid red');
               $('#olspan').show();
           }
           else
           {
                $('#old_pas').css('border','1px solid green');
                $('#olspan').hide();
                $('#n_pas').show();
                $('#c_pas').show();
                $('#old_pas').hide();
                 $('#btn_up').show();
               
           }
           }
           else
           {
               alert("Something went wrong..");
           }
            
        });
        
       
$('#btn_up').on('click',function(){
   
   var flag=1;
   var cp=$('#c_pas').val();
   var n=$('#n_pas').val();
   if(cp!=n)
   {
       flag=0;
       $('#c_pas').css('border','1px solid red');
       alert("Confirm password is not match!");
   }
   if(flag==1)
   {
        var myurl='<?php echo base_url('maincontroller/pass_update')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data:'cp='+cp,
          dataType :'JSON',
          success: function(result){
              if(result==1)
              {
               alert("sucessfully Changed");
                location.reload();
              }
              else
              {
                  alert("Something went wrong");
              }
                  
              }
   });
   }
});
        
    </script>    
        