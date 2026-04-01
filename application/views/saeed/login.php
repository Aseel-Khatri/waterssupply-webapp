<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Binary Admin</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo base_url();?>assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br />
                <h2> Binary Admin : Login</h2>
               
                <h5>( Login yourself to get access )</h5>
                 <br />
            </div>
        </div>
         <div class="row ">
               
                  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                        <strong>   Enter Details To Login </strong>  
                            </div>
                            <div class="panel-body">
                             
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                            <input type="text" class="form-control" placeholder="Your Username " id="email" />
                                        </div>
                                                                              <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                            <input type="password" class="form-control"  placeholder="Your Password" id="password" />
                                        </div>
                                          <!--<div class="form-group input-group">
                                            <span class="input-group-addon"><i>Type</i></span>&nbsp;
                                            Admin &nbsp;<input value="1" type="radio" name="bb" class="btn_r" />
                                            &nbsp;Delivery Boy &nbsp;<input name="bb" value="2" type="radio" class="btn_r" />

                                        </div>-->
                                     
                                     <button type="button" onclick="Login()" class="btn btn-info">Login Now</button>
                                    
                                    
                            </div>
                           
                        </div>
                    </div>
                
                
        </div>
    </div>


     <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="<?php echo base_url();?>assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="<?php echo base_url();?>assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="<?php echo base_url();?>assets/js/custom.js"></script>
   
</body>
</html>
<script type="text/javascript">
  function Login()
  {
            var email=$('#email').val();
      var password=$('#password').val();
       /*var type=$('.btn_r:Checked').val();*/
       
      flag=1;

if(email=='' || email==undefined){
        flag=0;
        $('#email').css('border','1px solid red');
  }
if(password=='' || password==undefined){
        flag=0;
        $('#password').css('border','1px solid red');
  }
    if(flag==1)    
            {
    var myurl='<?php echo base_url()?>Saeed_cont/login_auth';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'email='+email+'&password='+password,
          //dataType: 'JSON',
          success: function(result){ 
            
            console.log(result);
            if(result==1 && result!=0)
            {
             
                window.location.href='<?php echo base_url()?>Saeed_cont/index';
           
            }
            if(result==2 && result!=0)
            {
            swal ( "Oops" ,  "Invalid Email or Password!" ,  "error" );            
            }
            
            
            
            
            
          }
        });

    

   }

  }
</script>
