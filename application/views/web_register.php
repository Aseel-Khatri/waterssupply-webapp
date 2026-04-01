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
<!--      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">-->
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
                     <strong><?php echo $this->input->cookie('user_name');?>   Enter Details To Register </strong>  
                  </div>
                  <div class="panel-body">
                     <br />
                     <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                        <input style="background:transparent;" type="text" class="form-control" placeholder="username" id="un" />
                     </div>
                    
                     
                   
                     
                     <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-file"  ></i></span>
                        <input style="background:transparent;" type="text" class="form-control" placeholder="Company name" id="cn"/>
                     </div>
                     
                     <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-book"  ></i></span>
                       <textarea class="form-control" id="ca" placeholder="Company Adress"></textarea>
                     </div>
                     
                     <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"  ></i></span>
                        <input style="background:transparent;" type="text" class="form-control" placeholder="Phone number " id="pn"/>
                     </div>
                     <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                        <input style="background:transparent; position:initial;" type="password" class="form-control"  placeholder="Password" id="pass" />
                     </div>
                   
                   
                     <center>
                     <button type='button' onclick="register()" class="btn btn-info">Register Now</button>
                     </center>
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
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js"></script>-->
<script type="text/javascript">
/*$("#email").intlTelInput();
    
    var telInput = $("#pn"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({
utilsScript:"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
     
      var getCode = telInput.intlTelInput('getSelectedCountryData').dialCode;
      alert(getCode);
    } else {
      //telInput.addClass("error");
      //errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);*/



function register()
  {
    var un=$('#un').val();
    
    var pn=$('#pn').val();
    var ca=$('#ca').val();
    var cn=$('#cn').val();
    var pass=$('#pass').val();
    flag=1;
 if(un=='' || un==undefined){
        flag=0;
        $('#un').css('border','1px solid red');
  } 
if(ca=='' || ca==undefined){
        flag=0;
        $('#ca').css('border','1px solid red');
  }


if(cn=='' || cn==undefined){
        flag=0;
        $('#cn').css('border','1px solid red');
  }
if(pn=='' || pn==undefined){
        flag=0;
        $('#pn').css('border','1px solid red');
  }
  if(pass=='' || pass==undefined){
        flag=0;
        $('#pass').css('border','1px solid red');
  }
  
      pn='+'+$('#pn').intlTelInput("getSelectedCountryData").dialCode+$('#pn').val();
  

   
  if(flag==1)
  {
    var myurl='<?php echo base_url('maincontroller/RegisterUser')?>';
   $.ajax({
          url: myurl,
          method:'POST',
          data: 'cn='+cn+'&un='+un+'&ca='+ca+'&pn='+encodeURIComponent(pn)+'&pass='+pass,
           dataType: 'JSON',
            
          
          success: function(result){ 
            console.log(result);
            
            if(result==1){
               swal({
                    title: "Registered",
                    text: "click on button to back to login",
                    type: "success"
                }).then(function() {
                    window.location = "<?php echo base_url()?>";
                });
            }else{
                swal("Username already existe!", "success");
            }
        
  }
});
}
  }
</script>