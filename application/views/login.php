<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" type="image/gif/png" href="<?php echo base_url()?>assets/img/bottle.png"/>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Water login
    
    </title>
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
<style>
  input::placeholder {
 
  font-size: 1.4em!important;
  font-style: italic!important;
}
body{
    background: url(<?php echo base_url()?>assets/img/water.jpg);
    background-repeat: no-repeat;
    background-size: 100% 176%;
}
 
</style>
<body>
    <div class="container">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br />
                <h2> </h2>
               
                <h5></h5>
                 <br />
            </div>
        </div>
         <div class="row ">
               
                  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default " style="background:#fffe">
                            <div class="panel-heading">
                        <strong><?php echo $this->input->cookie('user_name');?>   Enter Details To Login </strong>  
                            </div>
                            <div class="panel-body">
                             
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
     <input style="background:transparent;" type="text" class="form-control" placeholder="Number "  id="email" value="<?php echo $this->input->cookie('user_name'); ?>" />
     
                                        </div>
                                                                              <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
    <input style="background:transparent; position:initial" type="password" class="form-control"  placeholder="Password" id="password" value="<?php if(isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bolder;font-size: 14px;" class="checkbox-inline">
    <input <?php if(isset($_COOKIE['user_name'])){echo "checked='checked'"; } ?> type="checkbox" id="remember" > Remember me
                                            </label>
                                 
                                        </div>
                                          <div style="font-weight: bolder;font-size: 14px;" class="form-group input-group">
                                            <span class="input-group-addon"><i>Type</i></span>&nbsp;
                                            Admin &nbsp;<input onclick="radio(this)" checked value="1" type="radio" name="bb"  class="btn_r" />
                                            &nbsp;Delivery Boy &nbsp;<input name="bb" value="2" onclick="radio(this)" type="radio" class="btn_r" />

                                        </div>
                                     
                                     <button style="float:right" onclick="Login()" class="btn btn-info">Login Now</button>
                                     
                                      <!--<a href="<?php //echo base_url('Maincontroller/web_register')?>"<button type='button' class="btn btn-info">Register Now</button></a>-->
                                    
                                    
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




    function radio(n){
        
        var value  =$(n).attr('value');
        if(value==1){
            
           // $('#email').attr('type','text');
            $('#email').attr('placeholder','Number');
        }else{
            $('#email').attr('type','text');
           // $('#email').attr('placeholder','UserName');
        }
    }





  function Login()
  {
            
            
         var email=$('#email').val();   
          //  alert(email);return;
      var password=$('#password').val();
       var type=$('.btn_r:Checked').val();
     /* var remember=$('#remember').val(); */
      if (!jQuery("#remember").is(":checked")) {

        remember=1;
    }
    else
    {
        remember=2;
    }
     
      flag=1;
if(type=='' || type==undefined){
        flag=0;
        swal ( "Oops" ,  "Please Select Type..!" ,  "error" ); 
  }


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
    var myurl='<?php echo base_url()?>maincontroller/login_auth';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'email='+encodeURIComponent(email)+'&password='+password+'&type='+type+'&remember='+remember,
          //dataType: 'JSON',
          success: function(result){ 
            
            console.log(result);
            if(result==1 && result!=0)
            {
             
                window.location.href='<?php echo base_url()?>maincontroller/index';
           
            }
            if(result==2 && result!=0)
            {
            swal ( "Unfortunately" ,  "Account is In-Active" ,  "error" );            
            }
                if(result==3 && result!=0)
            {
            swal ( "Unfortunately" ,  "your account has been expired" ,  "error" );            
            }
                if(result==10 && result!=0)
            {
            swal ( "Oops" ,  "Invalid Email or Password!" ,  "error" );            
            }
            
            
            
            
            
          }
        });

    

   }

  }
</script>
