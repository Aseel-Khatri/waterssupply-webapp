<?php
header('Access-Control-Allow-Origin: *');
class maincontroller extends CI_controller{

public function __construct() {
    parent::__construct();
   $this->load->model('Water_mod','m');
    $this->load->helper('cookie');
}



public function login(){

     $this->load->helper('url');
     $this->load->view('login');
 
}
public function login_auth(){
   
     $type=$_POST['type'];
      $email=$_POST['email'];
      $pass=$_POST['password'];
  $result =$this->m->login_auth($email,$pass,$type);

    if(!empty($result))
    {
      
       $status=$result[0]->status;
       if($status==1)
       {
           $user_name=$result[0]->user_name;
           $password='';
           if($type==2)
           {
           $password=$result[0]->pass;    
           }
            if($type==1)
           {
           $password=$result[0]->password;    
           }
           
           $cookie_time = 60 * 60 * 24 * 30;
           if(@$_POST['remember']==2)
           {
               
               

      $this->input->set_cookie('user_name', $user_name, 3600*15*24);
        $this->input->set_cookie('password', $password, 3600*15*24);
               
               
               /*$cookie_time_Onset=$cookie_time+ time();
               setcookie("user_name", $email, $cookie_time_Onset);
               setcookie("password", $pass, $cookie_time_Onset);*/
                             
           }
           else{
               delete_cookie('user_name'); 
                delete_cookie('password'); 
               
           }
           $_SESSION['login']=$result;
           echo "1";
           exit;
           
       }
       if($status==2)
       {
       
           echo "2";
           exit;
           
       }
         if($status==3)
       {
       
           echo "3";
           exit;
           
       }
       
    
        
        
    }
    else
    {
      
      echo "10";
      exit;

    }
 
}





public function index(){
 if(isset($_SESSION['login']))
 {
     $type=$_SESSION['login'][0]->type;
   if($type==1)
   {
        $result['active'] =$this->m->all_register();
        $result['db']=$this->m->all_delivery_boy();
        $result['in_ac'] =$this->m->inactive_register();
        $result['gc'] =$this->m->google_chart();
        $result['today_d'] =$this->m->today_delivery();
	   $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('index',$result);
		$this->load->view('footer');
   }
   else
   {
        echo '<script>';
        echo 'window.location.href ="https://watersupply-soft.com/maincontroller/today_delivery"';
        echo '</script>';
   }
       
       
   }
else
{
  echo $this->login();
}
}


public function users(){
 if(isset($_SESSION['login']))
 {

  $result['data']=$this->m->all_delivery_boy();
	  $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('users',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}


}



public function all_delivery_boy()
{
 if(isset($_SESSION['login']))
 {

  $result['data']=$this->m->all_delivery_boy();
	  $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('all_delivery_boy',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}


}



public function register()
{
if(isset($_SESSION['login']))
 {
    
   $result =$this->m->register();
 echo '<script>';

echo 'alert("Successfully added");';
echo 'window.location.href ="https://watersupply-soft.com/Maincontroller/users";';
 echo '</script>';
 }
else
{
  echo $this->login();
}
}
public function all_register()
{
   if(isset($_SESSION['login']))
 {

   $result['data'] =$this->m->all_register();
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('all_register',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}
}
public function inactive_register()
{
   if(isset($_SESSION['login']))
 {

   $result['data'] =$this->m->inactive_register();
        $this->load->helper('url');
    $this->load->view('header');
    $this->load->view('in_active_register',$result);
    $this->load->view('footer');   
}
else
{
  echo $this->login();
}

}
public function today_delivery()
{
   if(isset($_SESSION['login']))
 {

      $result['data'] =$this->m->today_delivery();
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('today_delivery',$result);
		$this->load->view('footer');   
}
else
{
  echo $this->login();
}


}



 public function bottle_data()
 {


    $result =$this->m->bottle_data();
    echo json_encode($result);
 }
   
 public function delete_user()
 {

 	$id=$_POST['id'];
 	$result =$this->m->delete_user($id);
 }
  public function detail_reg()
  {
     if(isset($_SESSION['login']))
 {

  	if(isset($_GET['id']))
  	{
  		$id=$_GET['id'];
    $result['data']=$this->m->detail_reg($id);
    $result['namee']=$this->m->detail_reg_name($id);
     $result['dbid']=$this->m->delivery_boy_id_detail_reg($id); 
       	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('detail_reg',$result);
		$this->load->view('footer');    
  	}
 
}
else
{
  echo $this->login();
}


  }
  public function edit_user()
  {
     if(isset($_SESSION['login']))
 {

  	if(isset($_GET['id']))
  	{
  		$id=$_GET['id'];
       	$result['data']=$this->m->edit_user($id);
       	$result['db']=$this->m->all_delivery_boy();
       	$result['t_day']=$this->m->all_days($id);
       	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('edit_users',$result);
		$this->load->view('footer');    
  	}
 
}
else
{
  echo $this->login();
}


  }
  public function edit_user_auth()
{
    $this->load->helper('url');
   $result =$this->m->edit_user_auth();
    echo '<script>';
        echo 'alert("successfully updated");window.location.href ="https://watersupply-soft.com/maincontroller/all_register"';
        echo '</script>';
}

public function detail_user_delete()
{

	
	 $result =$this->m->detail_user_delete();
     echo $result;

}

public function in_active_cus()
{
  
   $result =$this->m->in_active_cus();
     echo $result;

}

public function active_cus()
{
  
   $result =$this->m->active_cus();
     echo $result;

}


public function admin_view()
{

 if(isset($_SESSION['login']))
 {
   
    $result['data']=$this->m->admin_view();   
     $this->load->helper('url');
    $this->load->view('header');
    $this->load->view('admin_view',$result);
    $this->load->view('footer');    
}
else
{
  echo $this->login();
}

}


public function logout()
{
  session_destroy(); 
echo $this->login();
/*echo '<script> window.location.href = "http://watersupply-soft.com";</script>';*/
}
public function add_delivery_boy()
{

    $this->load->helper('url');
    $this->load->view('header');
    $this->load->view('delivery_boy');
    $this->load->view('footer');    
}
public function add_delivery_boy_auth()
{

  $result=$this->m->add_delivery_boy();
  echo json_encode($result);


}

public function all_day_by_user()
{
    $result=$this->m->all_day_by_user();
  echo json_encode($result);
}

public function day_update()
{
  $result=$this->m->day_update();
  echo json_encode($result);
}

public function day_delete()
{
  $result=$this->m->day_delete();
  echo json_encode($result);
}

public function today_delivery_modal_data()
{
    $result=$this->m->today_delivery_modal_data();
   echo json_encode($result);
}


public function get_delivery_boy()
{
   $result=$this->m->get_delivery_boy();
   echo json_encode($result);
}

public function update_delivery_boy()
{
   $result=$this->m->update_delivery_boy();
   echo json_encode($result);
}
public function delete_delivery_boy()
{
   $result=$this->m->delete_delivery_boy();
   echo json_encode($result);
}
public function add_old_order()
{
   $result=$this->m->add_old_order();
   echo json_encode($result);
}
 public function slc_add_days()
 {
     $result=$this->m->slc_add_days();
        echo '<script>';
        echo 'alert("successfully added");window.location.href ="https://watersupply-soft.com/maincontroller/all_register"';
        echo '</script>';
 }
 
 public function active_user_report()
 {
   if(isset($_SESSION['login']))
 {

   $result['data'] =$this->m->all_register();
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('active_users_report',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }
 public function active_user_by_date()
{
      if(isset($_SESSION['login']))
 {

   $result['data'] =$this->m->active_user_by_date();
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('active_users_report',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }
  
  public function delivery_boy_report()
 {
   if(isset($_SESSION['login']))
 {

   $result['data']=$this->m->all_delivery_boy();
 
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('delivery_boy_report',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }
    
public function detail_sale_report()
  {
   if(isset($_SESSION['login']))
 {

   $result['data']=$this->m->detail_sale_report();
 
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('detail_sale_report',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }   
    

  public function active_user_report_by_id()
  {
     if(isset($_SESSION['login']))
 {

  	if(isset($_GET['id']))
  	{
  		$id=$_GET['id'];
    $result['data']=$this->m->detail_reg($id);
    $result['namee']=$this->m->detail_reg_name($id);
     $result['dbid']=$this->m->delivery_boy_id_detail_reg($id); 
       	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('active_user_report_by_id',$result);
		$this->load->view('footer');    
  	}
 
}  
}


 public function counter_sale_auth()
  {
     if(isset($_SESSION['login']))
 {

  	
  		
    $result=$this->m->counter_sale_auth();
  	 	  
  	 	   print_r($result);
  	
 }
  
      
  
 

else
{
  echo $this->login();
}

      
  }
 public function counter_sale()
  {
     if(isset($_SESSION['login']))
 {

      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('counter_sale');
		$this->load->view('footer');    
   
 
}
else
{
  echo $this->login();
}
}


 public function all_counter_sale()
  {
     if(isset($_SESSION['login']))
 {
        $result['data']=$this->m->all_counter_sale();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('all_counter_sale',$result);
		$this->load->view('footer');    
   
 
}
else
{
  echo $this->login();
}
}
public function update_counter_sale()
{
   $result=$this->m->update_counter_sale();
   echo json_encode($result);
}
public function delete_counter_sale()
{
    	
 	$result =$this->m->delete_counter_sale();
}
    

public function all_reg_detail()
{
 
 
  if(isset($_SESSION['login']))
 {
     $result['data'] =$this->m->all_register_by_id();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('all_reg_detail',$result);
		$this->load->view('footer');    
   
 
 }
 else
{
  echo $this->login();
}
 
 
    
}

public function counter_sale_filter()
{
  
    if(isset($_SESSION['login']))
 {
        $result['data']=$this->m->all_counter_sale();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('all_counter_sale',$result);
		$this->load->view('footer');    
}
else
{
  echo $this->login();
}
  
}


public function pass_update()
{
  
    if(isset($_SESSION['login']))
 {
        $result=$this->m->pass_update();
      	echo $result;
}
  
}

public function am_blnc_report()
{
    if(isset($_SESSION['login']))
 {
        $result['data']=$this->m->am_blnc_report();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('am_blnc_report',$result);
		$this->load->view('footer');    
}
else
{
  echo $this->login();
}
    
    
    
}



public function download()
{

 $this->load->view('download_apk');
}

public function detail_edit()
{
   $result=$this->m->detail_edit();
    echo json_encode($result);
}



public function plant_reg(){
 if(isset($_SESSION['login']))
 {


	  $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_reg');
		$this->load->view('footer');
}
else
{
  echo $this->login();
}


}


public function plant_reg_auth()
{
   $result=$this->m->plant_reg_auth();
    echo json_encode($result);
}

 
 
 public function plant_filling(){
 if(isset($_SESSION['login']))
 {
       $result['data']=$this->m->all_plant();
	   $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_filling',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}


}
 
 
 public function plant_order()
 {
     $result=$this->m->plant_order();
    echo json_encode($result);
 }
 public function plant_order_auth()
 {
     $result=$this->m->plant_order_auth();
    echo json_encode($result);
 }
 
  
  
    public function plant_b_reg()
  {
     if(isset($_SESSION['login']))
 {

  	if(isset($_GET['id']))
  	{
  		$id=$_GET['id'];
    $result['data']=$this->m->plant_b_reg($id);
       	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_b_reg',$result);
		$this->load->view('footer');    
  	}
 
}
else
{
  echo $this->login();
}


  }

  
  
  public function plant_all_reg_detail()
{
 
 
  if(isset($_SESSION['login']))
 {
     $result['data'] =$this->m->plant_all_reg_detail();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_all_reg_detail',$result);
		$this->load->view('footer');    
   
 
 }
 else
{
  echo $this->login();
}
 
 
    
}
  
  
 
  public function plant_edit_user()
{
 
 
  if(isset($_SESSION['login']))
 {
     $result['data'] =$this->m->plant_all_reg_detail();
      	$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_edit_user',$result);
		$this->load->view('footer');    
   
 
 }
 else
{
  echo $this->login();
}
 
 
    
}
 
 public function plant_reg_update()
 {
    $result=$this->m->plant_reg_update();
    echo json_encode($result);
 }
  
  
  
  public function plant_detail_sale_report()
  {
   if(isset($_SESSION['login']))
 {

   $result['data']=$this->m->plant_detail_sale_report();
 
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('plant_detail_sale_report',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }   

  
  
 public function expense()
 {
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('expense');
		$this->load->view('footer');
 }
  public function expense_auth()
 {
     /*$result=$this->m->expense_auth();
    print json_encode($result);*/
         $un=$_POST['username'];
        date_default_timezone_set("Asia/Karachi");
        $today_datee=date('Y-m-d');
        $pr=$_POST['price'];
         $login_id=$_SESSION['login'][0]->id;
        
            for($i=0;$i<=sizeof($un);$i++)
            {
                $p='';
                $u='';
                if(!empty($un[$i]))
                {
                $u= $un[$i];
                
                }
                if(!empty($pr[$i]))
                {
                $p= $pr[$i];
                }
                if(!empty($u)&&!empty($p))
                {
                $data=array(
                    'username'=>$u,
                    'price'=>$p,
                    'datee'=>$today_datee,
                    'login_id'=>$login_id,
                    );
                    
                    $result=$this->m->expense_auth($data);
                    $data='';
                }
                
                
            }
       echo 1;
     
 }
 
 
 
 public function exp_view()
  {
   if(isset($_SESSION['login']))
 {

   $result['data']=$this->m->exp_view();
 /* $result['username']=$this->m->all_expense();*/
        $this->load->helper('url');
		$this->load->view('header');
		$this->load->view('exp_view',$result);
		$this->load->view('footer');
}
else
{
  echo $this->login();
}   
 }   

 public function web_register(){
     
     $this->load->helper('url');

	$this->load->view('web_register');
     
 }
 
 
 public function RegisterUser(){

    $result=$this->m->RegisterUser();
   echo json_encode($result);
}
 
 
 
 
 
 
 
 
    
}

?>