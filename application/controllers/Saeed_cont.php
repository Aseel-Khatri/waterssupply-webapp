<?php
header('Access-Control-Allow-Origin: *');


class Saeed_cont extends CI_controller{



public function __construct() {

    parent::__construct();

   $this->load->model('Saeed_mod','m');

}







public function login(){

     $this->load->helper('url');
     $this->load->view('saeed/login');

 

}

public function login_auth(){

  $result =$this->m->login_auth();
    if(empty($result))

    {

       echo "2";

    }

    else

    {

      $_SESSION['saeed_login']=$result;

     
       echo 1;
     


    }

 

}











public function index(){

if(!empty($_SESSION['saeed_login']))
{

	     $this->load->helper('url');
		$this->load->view('saeed/header');
		$this->load->view('saeed/index');
		$this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}

    
}





public function active_client(){

if(!empty($_SESSION['saeed_login']))
{

    $result['data']=$this->m->active_client();
     $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/active_client',$result);
    $this->load->view('saeed/footer');

}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}



}

public function add_client(){

    //$result['data']=$this->m->active_client();
if(!empty($_SESSION['saeed_login']))
{

     
     $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/users');
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}

}

public function add_client_auth(){


    $result=$this->m->add_client_auth();
   echo json_encode($result);

}





public function logout()

{

  session_destroy(); 

  echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';

}



public function delivery_boy()

{
    if(!empty($_SESSION['saeed_login']))
{

  if(isset($_GET['id']))
  {
    $id=$_GET['id'];
   $this->load->helper('url');
   $this->load->view('saeed/header');
   $result['data']=$this->m->delivery_boy($id);
      if(empty($result['data']))
      {
    $this->load->view('saeed/delivery_boy');      
  }
      else
      {
       $this->load->view('saeed/delivery_boy');
      }

    $this->load->view('saeed/footer');  

}


}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}


public function add_delivery_boy()
{

  $result=$this->m->add_delivery_boy();
  echo json_encode($result);


}




public function notification()
{
    if(!empty($_SESSION['saeed_login']))
{


    $result['data']=$this->m->notification();
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/notification',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}

public function edit_user()
{
    
    if(!empty($_SESSION['saeed_login']))
{

    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
    
    $result['data']=$this->m->edit_user_by($id);
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/edit_user',$result);
    $this->load->view('saeed/footer');

}
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}



}

public function edit_user_auth()
{

  $result=$this->m->edit_user_auth();
  echo json_encode($result);


}
public function in_active_cus()
{
    $result=$this->m->in_active_cus();
    echo json_encode($result);
}

public function active_cus_by()
{
    $result=$this->m->active_cus_by();
    echo json_encode($result);
}
    

//corn-job
public function month_complete()
{
    $result=$this->m->month_complete();
    
}


public function all_in_active_client()
{
    if(!empty($_SESSION['saeed_login']))
{

    $result['data']=$this->m->all_in_active_client();
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/all_in_active_client',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}

}


public function profile()
{
    if(!empty($_SESSION['saeed_login']))
{


    $result['data']=$this->m->profile();
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/profile',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}

public function delete_all_rec()
{
    if(!empty($_SESSION['saeed_login']))
{


    $result=$this->m->delete_all_rec();
    echo json_encode($result);
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}

public function customer_delete_rec()
{
    if(!empty($_SESSION['saeed_login']))
{

    
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/customer_delete_rec');
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}


public function customer_delete_rec_auth()
{
    if(!empty($_SESSION['saeed_login']))
{

    
    $result['data']=$this->m->customer_delete_rec();
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/customer_delete_rec',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}

public function delete_id_rec()
{
    $result=$this->m->delete_id_rec();
    echo json_encode($result);
}

/*APP WORK*/
public function app_notification()
{
    if(!empty($_SESSION['saeed_login']))
{

    $st=3;
    $result['data']=$this->m->app_all_client($st);
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/app_notification',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}


}



public function app_edit_user()
{
    
    if(!empty($_SESSION['saeed_login']))
{

    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
    
    $result['data']=$this->m->app_edit_user_by($id);
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/app_edit_user',$result);
    $this->load->view('saeed/footer');

}
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}



}

public function app_edit_user_auth()
{

  $result=$this->m->app_edit_user_auth();
  echo json_encode($result);


}

public function app_active_client(){

if(!empty($_SESSION['saeed_login']))
{
    $st=1;
    $result['data']=$this->m->app_all_client($st);
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/app_active_client',$result);
    $this->load->view('saeed/footer');

}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}



}
public function app_in_active_cus()
{
    $id=$_POST['id'];
    $st=2;
    $result=$this->m->app_in_st_cus($id,$st);
    echo json_encode($result);
}
public function app_inactive_cus()
{
    $id=$_POST['id'];
    $st=1;
    $result=$this->m->app_in_st_cus($id,$st);
    echo json_encode($result);
}
public function app_all_in_active_client()
{
    if(!empty($_SESSION['saeed_login']))
{

    $st=2;
    $result['data']=$this->m->app_all_client($st);
    $this->load->helper('url');
    $this->load->view('saeed/header');
    $this->load->view('saeed/app_all_in_active_client',$result);
    $this->load->view('saeed/footer');
}
else
{
    echo '<script>';
    echo 'window.location.href = "https://watersupply-soft.com/Saeed_cont/login";';
    echo '</script>';
}

}




}