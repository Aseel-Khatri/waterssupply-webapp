<?php
class Saeed_mod extends CI_Model 
{


    public function login_auth(){
     
      $email=$_POST['email'];
      $pass=$_POST['password'];

    $this->db->SELECT("*");
    $this->db->WHERE('user_name',$email );
    $this->db->WHERE('password',$pass );
    $query=$this->db->get('onwer');
   return $query->result();

}

	


  public function detail_user_delete()
  {
     $login_id=$_SESSION['saeed_login'][0]->id;
    $id=$_POST['id'];
  $this->db->where('login_id', $login_id);
    $this->db->where('id', $id);
    $this->db->delete('filling_card');
    return 1;
  }


  public function admin_view()
{
      $login_id=$_SESSION['saeed_login'][0]->id;
    $this->db->SELECT('DATE_FORMAT(datee, "%M %d, %Y") as dte,admin_view.*');
      $this->db->where('login_id', $login_id);
   $query=$this->db->get('admin_view');
        return $query->result();

}

 public function active_client()
  {
    $st=1;
    $this->db->SELECT("login.*, DATE_FORMAT(login.start_datee, '%M %d, %Y') as sdatee,DATE_FORMAT(login.end_datee, '%M %d, %Y') as edatee");
    $this->db->where('status',$st);
    $qu=$this->db->get('login');
    return $qu->result();
  }
  public function notification()
  {
    $st=3;
    $this->db->SELECT("login.*, DATE_FORMAT(login.start_datee, '%M %d, %Y') as sdatee,DATE_FORMAT(login.end_datee, '%M %d, %Y') as edatee");
    $this->db->where('status',$st);
    $qu=$this->db->get('login');
    return $qu->result();
  }
  
  
  public function all_in_active_client()
{
    $st=2;
    $this->db->SELECT("login.*, DATE_FORMAT(login.start_datee, '%M %d, %Y') as sdatee,DATE_FORMAT(login.end_datee, '%M %d, %Y') as edatee");
    $this->db->where('status',$st);
    $qu=$this->db->get('login');
    return $qu->result();
}
  
  
  
  
  

 public function add_client_auth()
  {
    $un=$_POST['un'];
    $pass=$_POST['pass'];
    $add=$_POST['add'];
    $s_datee=$_POST['s_datee'];
    $e_datee=$_POST['e_datee'];
    $cn=$_POST['cn'];
    $pma=$_POST['pma'];
    $pn=$_POST['pn'];
    $ref=$_POST['ref'];
    $dbl=$_POST['dbl'];
    $sms_id=$_POST['sms_id'];
    $add_free_id=$_POST['add_free_id'];
    
    $this->db->SELECT('*');
    $this->db->where('user_name',$un);
    $qu=$this->db->get('login');
     $qu_res=$qu->result();
     if(empty($qu_res))
     {
        $data=array(
         'user_name'=>$un,
         'password'=>$pass,
         'status'=>1,
         'com_name'=>$cn,
         'address'=>$add,
         'type'=>1,
         'start_datee'=>$s_datee,
         'end_datee'=>$e_datee,
         'phone_number'=>$pn,
         'per_month_amount'=>$pma,
         'reffer'=>$ref,
         'delivery_boy_limit'=>$dbl,
         'sms'=>$sms_id,
         'is_add_free'=>$add_free_id,
        );
        $this->db->insert('login',$data);
        return 1;
     }
     else
     {
        return 2;
     }
  }

/*
public function delivery_boy($id)
{
 

  $qu=$this->db->query("SELECT * from delivery_boy where id='$id'");
   $res=$qu->result();
   if(!empty($res))
   {
    return $res;
   }
   else
   {
     return "";
   }


}*/


public function month_complete()
{
   date_default_timezone_set("Asia/Karachi");
   $d= date('Y-m-d');
   $st=3;
       $this->db->SELECT("*");
       $this->db->WHERE('end_datee',$d);
       $q=$this->db->get('login');
       $res=$q->result();
       if(!empty($res))
       {
       foreach($res as $val)
       {
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$val->id);
        $this->db->update('delivery_boy');
       }
       $this->db->SET('status',$st);
       $this->db->WHERE('end_datee',$d);
       $this->db->update('login');
    
       }
}


/*public function add_delivery_boy()
{

  if(isset($_POST['id']))
  {
    $id=$_POST['id'];
    $qu=$this->db->query("SELECT * from login where id='$id'");
     $res=$qu->result();
     if(!empty($res))
     {
      $id=$_POST['id'];
      $un=$_POST['un'];
      $pass=$_POST['pass'];
      $st=1;
      $ty=2;
     $data=array(

      'id'=>$id,
      'user_name'=>$un,
      'pass'=>$pass,
      'status'=>$st,
      'type'=>$ty,

     );
     $this->db->insert('delivery_boy',$data);
     return 1;

     }
*/     

/*  }


}*/

public function edit_user_by($id)
{
    $this->db->SELECT('*');
    $this->db->where('id',$id);
    $qu=$this->db->get('login');
     return $qu->result();
}
public function edit_user_auth()
{
    

    $un=$_POST['un'];
    $pass=$_POST['pass'];
    $add=$_POST['add'];
    $s_datee=$_POST['s_datee'];
    $e_datee=$_POST['e_datee'];
    $cn=$_POST['cn'];
    $pma=$_POST['pma'];
    $pn=$_POST['pn'];
    $ref=$_POST['ref'];
     $dbl=$_POST['dbl'];
     $h_id=$_POST['h_id'];
     $st=$_POST['st'];
     $sms=$_POST['sms_id'];
     $is_add_free=$_POST['is_add_free'];
     
     
//echo utf8_decode($_POST['pn']);die;


    $data=array(
         'user_name'=>$un,
         'password'=>$pass,
         'status'=>$st,
         'com_name'=>$cn,
         'address'=>$add,
         'type'=>1,
         'start_datee'=>$s_datee,
         'end_datee'=>$e_datee,
         'phone_number'=>$pn,
         'per_month_amount'=>$pma,
         'reffer'=>$ref,
         'delivery_boy_limit'=>$dbl,
         'sms'=>$sms,
         'is_add_free'=>$is_add_free


        );
        $this->db->WHERE('id',$h_id);
        $this->db->update('login',$data);
        
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$h_id);
        $this->db->update('delivery_boy');
        return 1;

}

public function in_active_cus()
{
    $id=$_POST['id'];
    $st=2;
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$id);
        $this->db->update('login');
        
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$id);
        $this->db->update('delivery_boy');
        return 1;

}
public function active_cus_by()
{
     $id=$_POST['id'];
    $st=1;
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$id);
        $this->db->update('login');
        
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$id);
        $this->db->update('delivery_boy');
        return 1;
}



public function noti_count()
{
    $st=3;
        $this->db->SELECT("*");
        $this->db->SELECT("status",$st);
        $q=$this->db->get('login');
        $noti_count= Count($q->result());
        return $noti_count;
}



public function profile()
{
        $this->db->SELECT("*");
        $q=$this->db->get('onwer');
        return $q->result();
    
}
public function delete_all_rec()
{
    $id=$_POST['id'];
    $this->db->query("DELETE from admin_view where login_id='$id'");
    $this->db->query("DELETE from counter_sale where login_id='$id'");
    $this->db->query("DELETE from days where login_id='$id'");
    $this->db->query("DELETE from delivery_boy where id='$id'"); 
    $this->db->query("DELETE from filling_card where login_id='$id'");
    $this->db->query("DELETE from register where login_id='$id'");
    $this->db->query("DELETE from temp_filling_card where login_id='$id'");
    return 1;
    
}

public function customer_delete_rec()
{
    $id=$_POST['c_id'];
   $q=$this->db->query("SELECT filling_card.id as fc_id ,register.type as typ , register.first_name as rfn,register.last_name as rln,filling_card.*,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
LEFT JOIN register on register.id=filling_card.customer_id
WHERE filling_card.customer_id='$id' AND register.id='$id'AND filling_card.customer_id=register.id");
    return $q->result();
    
}
public function delete_id_rec()
{
    $id=$_POST['id'];
    $this->db->query("DELETE from filling_card where id='$id'");
    return 1;
}



public function app_register($data)
{
    
    
  $u=$data['phone_number'];
  $check = $this->db->query("SELECT b.id, b.full_name ,b.phone_number,b.password,b.address,b.company_name,DATE_FORMAT(b.exp_date, '%d/%m/%Y') as exp_date,b.type,b.status  FROM app_register AS b where b.phone_number = '$u'");
  if($check->num_rows() > 0)

            {
                
                $response['result']['status']   = 'error';
                $response['result']['response'] = "Phone number is Already Exists.";

                echo json_encode($response);

                die();
            }else{

               $this->db->insert('app_register',$data);
              $id=$this->db->insert_id();
              $this->db->SELECT("b.id,b.full_name ,b.phone_number,b.password,b.address,b.company_name,DATE_FORMAT(b.exp_date, '%d/%m/%Y') as exp_date,b.type,b.status");
              $this->db->WHERE('b.id',$id);
              $qu=$this->db->get('app_register AS b');
              $res=$qu->result();
              $response['result']['status']   = 'success';
              $response['result']['response'] = "Sucessfully Registered";
               $response['result']['data'] =$res;

                echo json_encode($response);
            }
}
 

 public function app_login_auth($us,$pass)
 {
     

       $this->db->SELECT("b.id,b.full_name ,b.phone_number,b.password,b.address,b.company_name,DATE_FORMAT(b.exp_date, '%d/%m/%Y') as exp_date,b.type,b.status");
       $this->db->WHERE('b.phone_number',$us);
        $this->db->WHERE('b.password',$pass);
       $qu=$this->db->get('app_register As b');
    if($qu->num_rows() > 0)
    {
        
       $res=$qu->result(); 
       $id=$res[0]->id;
       $dt=$res[0]->exp_date;
       $status=$res[0]->status;
       if($status==1)
       { 
             if(empty($dt) && $dt=='')
             {
                date_default_timezone_set("Asia/Karachi");
                $next_due_date = date('Y-m-d', strtotime("+10 days"));
                $this->db->set('exp_date',$next_due_date);
                $this->db->where('id', $id);
                $this->db->update('app_register');
                $this->db->SELECT(" b.id,b.full_name ,b.phone_number,b.password,b.address,b.company_name,DATE_FORMAT(b.exp_date, '%d/%m/%Y') as exp_date,b.type,b.status");
                $this->db->WHERE('b.phone_number',$us);
                $this->db->WHERE('b.password',$pass);
                $resu=$this->db->get('app_register As b');
                $response['result']['status']   = 'success';
                $response['result']['response'] = "Successfully Login";
                $response['result']['login'] =$resu->result();
                echo json_encode($response);die;
             }else
             {
              $response['result']['status']   = 'success';
              $response['result']['response'] = "Successfully Login";
              $response['result']['login'] =$res;
              
              echo json_encode($response);die;
    
             }
        }else{

                   $response['result']['status']   = 'expire';
                  $response['result']['response'] = "Your Account is expired";
                echo json_encode($response);die;
            }
    }else
    {
       $response['result']['status']   = 'error';
                $response['result']['response'] = "Your Phone number Or password is incorrect..";
              echo json_encode($response);die;

    }

       

  

 }

 public function app_cron()
 {
    date_default_timezone_set("Asia/Karachi");
    $t_d= date('Y-m-d');
    $st=3;
       $this->db->SELECT("*");
       $this->db->WHERE('exp_date',$t_d);
       $q=$this->db->get('app_register');
       $res=$q->result();
       if(!empty($res))
       {
         foreach($res as $val)
         {
          $this->db->SET('status',$st);
          $this->db->WHERE('id',$val->id);
          $this->db->update('app_register');
         }
       }     
  } 
  
  
  
  
  
  public function app_all_client($st)
  {

    $this->db->SELECT("*");
    $this->db->where('status',$st);
    $qu=$this->db->get('app_register');
    return $qu->result();
  }
  
  
  
public function app_edit_user_by($id)
{
    $this->db->SELECT('*');
    $this->db->where('id',$id);
    $qu=$this->db->get('app_register');
     return $qu->result();
}
public function app_edit_user_auth()
{

    $h_id=$_POST['h_id'];
    $e_datee=$_POST['e_datee'];
    $st=$_POST['st'];
    $cn=$_POST['cn'];
    $fn=$_POST['fn'];
    $ps=$_POST['ps'];
    $add=$_POST['add'];
    $ph=$_POST['ph'];
    $data=array(
         'status'=>$st, 
         'exp_date'=>$e_datee,
         'full_name'=>$fn,
         'phone_number'=>$ph,
         'company_name'=>$cn,
         'password'=>$ps,
         'address'=>$add
        );
        $this->db->WHERE('id',$h_id);
        $this->db->update('app_register',$data);
        return 1;

}
  
    public function update_password($us,$pass){
      
      
      
        $check = $this->db->query("SELECT b.*  FROM app_register AS b where b.phone_number = '$us'");
          
          if($check->num_rows() == 0 || $check->num_rows() =='' || $check->num_rows() < 0 )

            {
                
                $response['result']['status']   = 'error';
                $response['result']['response'] = "Phone number is Not Exists.";

                echo json_encode($response);

                die();
            }else{

                    $pass_array=array('password'=>$pass);
                    
                   $this->db->WHERE('phone_number',$us);
                   $q=$this->db->update('app_register',$pass_array);
                   if($q){
                       $response['result']['status']   = 'success';
                       $response['result']['response'] = "password successfully updated";    
                       
                   }else{
                       $response['result']['status']   = 'error';
                       $response['result']['response'] = "password could not be updated.";        
                   }    

                echo json_encode($response);
            }
    }
  
 public function app_in_st_cus($id,$st)
{
    
        $this->db->SET('status',$st);
        $this->db->WHERE('id',$id);
        $this->db->update('app_register');
        return 1;

} 
  
  
  
  
  
}
?>