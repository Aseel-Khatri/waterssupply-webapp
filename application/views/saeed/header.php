<!DOCTYPE html>
<?php
date_default_timezone_set("Asia/Karachi");
$this->load->model('Water_mod','m');
 @$not=$this->m->notification();
 $noti=Count(@$not);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>watersupply</title>
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
</style>
<body>
    <div id="wrapper">
        <nav class="navbar  navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class=" navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="  navbar-brand" href="<?php echo base_url();?>index.html">Saeed</a> 
            </div>
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> <a href="<?php echo base_url();?>Saeed_cont/logout" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
					</li>

					
                    <li>
                        <a href="<?php echo base_url();?>Saeed_cont/index"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>Saeed_cont/profile"><i class="fa fa-home  fa-3x"></i>Profile</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/add_client"><i class="fa fa-user  fa-3x"></i>Add client</a>
                    </li>
                    
                    <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/active_client"><i class="fa fa-th-large fa-3x"></i>Active client</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/all_in_active_client"><i class="fa fa-list fa-3x"></i>In-Active client</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/customer_delete_rec"><i class="fa fa-list fa-3x"></i>Data Delete</a>
                    </li>
                    <li>
                        
                        <a  href="<?php echo base_url();?>Saeed_cont/notification">
                            
                            <i class="fa fa-file fa-3x">
                           
                        </i>Notification
                         <?php if(@$noti!=0){echo '<span style="color:red;font-size: 24px;">'.@$noti.'</span>';} ?>
                        </a>
                    </li>
                    <li>
                        <a style="font-size: 24px;background: brown;" href="#">
                        Mobile Application</a>
                    </li>
                      <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/app_active_client"><i class="fa fa-th-large fa-3x"></i>APP Active client</a>
                    </li>
                    <li>
                        <a  href="<?php echo base_url();?>Saeed_cont/app_all_in_active_client"><i class="fa fa-list fa-3x"></i>APP In-Active client</a>
                    </li>
                    <li>
                        
                        <a  href="<?php echo base_url();?>Saeed_cont/app_notification">
                            
                            <i class="fa fa-file fa-3x">
                           
                        </i>App-Notification
                         </a>
                    </li>
                         
                </ul>
               
            </div>
            
        </nav>  