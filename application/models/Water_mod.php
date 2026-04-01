<?php
class Water_mod extends CI_Model 
{


    public function login_auth($email,$pass,$type){
     
/*      $st=1;*/
    if($type==1)
    {
      
    $this->db->SELECT("*");
    //$this->db->WHERE('user_name',$email );
    $this->db->where("(phone_number = '$email' or user_name = '$email')"); 
    $this->db->WHERE('password',$pass );
   /*  $this->db->WHERE('status',$st );*/
    $query=$this->db->get('login');
   return $query->result();
    }
    if($type==2)
    {
    $this->db->SELECT("*");
    $this->db->WHERE('user_name',$email );
  /*  $this->db->where("(phone_number = '$email' or user_name = '$email')");*/
    $this->db->WHERE('pass',$pass );
     /*$this->db->WHERE('status',$st );*/
    $query=$this->db->get('delivery_boy');
   return $query->result();
    }
 
}

	

     
    public function register()
    {
      $db_id="0";
      if(!empty($_POST['db_id']))
      {
        
        $db_id=$_POST['db_id'];

      }
      
    	$fn=$_POST['fn'];
    	$ln=$_POST['ln'];
    	$pn=$_POST['pn'];
    	$add=$_POST['add'];
    	$sel=$_POST['sel'];
    

    	@$type=@$_POST['r_v'];
    	$datee=$_POST['datee'];
    	$price=$_POST['price'];
        $deposit=$_POST['deposit'];
        $login_id=$_SESSION['login'][0]->id;
    	$status=0;
    $data = array(
        'first_name' => $fn,
        'last_name' => $ln,
        'number' => $pn,
        'datee' => $datee,
        'address' => $add,
        'price' => $price,
        'type' => $type,
        'status' => $status,
        'deposit' => $deposit,
        'login_id' => $login_id,
         'delivery_boy_id' => $db_id,
);

$this->db->insert('register', $data);
$insert_id=$this->db->insert_id();
    
       for($i=0; $i < sizeof($sel); $i++) 
       {
       $dat = array(
        'days' => $sel[$i],
        'user_id' => $insert_id,
        'login_id'=>$login_id,
);

$this->db->insert('days', $dat);
}

if(!empty($_POST['ab'])|| !empty($_POST['bb']))
{
    	date_default_timezone_set("Asia/Karachi");
       $ab=0;
       if(!empty($_POST['ab']))
       {
           $ab=$_POST['ab'];
       }
       $bb=0;
       if(!empty($_POST['bb']))
       {
    	$bb=$_POST['bb'];
       }
    
    	$datee=date('Y-m-d');
    	$timee=date('h-m-s');
    	   $d_old = array(
                    'customer_id' => $insert_id,
                    'datee' => $datee,
                    'timee' => $timee,
                    'filled_deliver' =>0,
                    'empty_recieved' =>0,
                    'bottle_blnc' => $bb,
                    'total_amount' =>0,
                    'amount_rec' => 0,
                    'amount_blnc' => $ab,
                    'login_id'=>$login_id,
                    'bottle_deliver'=>'Admin',
                     'date_wise'=>0,
                    'daily_bottle_blnc'=>0,
                    'daily_am_blnc'=>0,
            );
          
            $this->db->insert('filling_card', $d_old);
         
}


    return true;
      
   
    }
    
    public function all_register()
    {
        $login_id=$_SESSION['login'][0]->id;
        $st=0;
    /*	$this->db->SELECT('*');
        $this->db->WHERE('status',$st);
        $this->db->WHERE('login_id',$login_id);
    	$query=$this->db->get('register');*/
         
        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id'");
    	return $query->result();
    }
    
    
    public function all_register_by_id()
    {
        
        
        $login_id=$_SESSION['login'][0]->id;
      
        $r_id=$_GET['id'];
         
        $query=$this->db->query("SELECT DATE_FORMAT(register.datee, '%d/%m/%Y') as rdatee ,register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE  register.login_id='$login_id' AND register.id='$r_id'");
    	return $query->result();
        
    }
    
    
    
    public function inactive_register()
    {
       $login_id=$_SESSION['login'][0]->id;
        $st=1;
        $this->db->SELECT('*');
        $this->db->WHERE('status',$st);
        $this->db->WHERE('login_id',$login_id);
        $query=$this->db->get('register');
        return $query->result();
    }
    
 public function today_delivery()
    {
    	date_default_timezone_set("Asia/Karachi");
        $login_id=$_SESSION['login'][0]->id;
        $login_type=$_SESSION['login'][0]->type;
        $st=0;
    	$day=date('D');
            
      $datee=date('Y-m-d');
     
                $query=$this->db->query("SELECT register.* FROM register INNER JOIN days on days.user_id=register.id WHERE days.days='$day' AND register.login_id=days.login_id AND days.login_id='$login_id'AND register.status='$st' ");
      
    	/*$this->db->SELECT('*');
        $this->db->WHERE('status',$st);
    	$this->db->WHERE('login_id',$login_id);
        $this->db->WHERE('day_of_giving',$day);
    	$query=$this->db->get('register');*/
        $dta=$query->result();
       
        foreach ($dta as $v) 
        {
            $id=$v->id;
        $this->db->SELECT('*');
        $this->db->WHERE('login_id',$login_id);
        $this->db->WHERE('customer_id',$id);
        $query=$this->db->get('temp_filling_card');
        $dta_tp=$query->result();
        if(empty($dta_tp))
        {
            $this->db->SELECT('*');
            $this->db->WHERE('customer_id',$id);
            $this->db->WHERE('login_id',$login_id);
            $this->db->WHERE('datee',$datee);
            $query_c=$this->db->get('filling_card');
            $dta_c=$query_c->result();
            if(empty($dta_c))
            {




         $data = array(
                    'customer_id' => $id,
                    'status'=>1,
                    'datee'=>$datee,
                    'login_id'=>$login_id,
                    
            );
          
            $this->db->insert('temp_filling_card', $data);
           }
           

           } 
         }
        
        if($login_type==1)
        {
        $query=$this->db->query("SELECT register.*,DATE_FORMAT(temp_filling_card.datee, '%d/%m/%Y') as datee from register,temp_filling_card
           WHERE register.id=temp_filling_card.customer_id
            AND register.login_id='$login_id' AND temp_filling_card.login_id='$login_id'");
        }
        else
        {
            $d_b_id=$_SESSION['login'][0]->delivery_boy_idd;
            $query=$this->db->query("SELECT register.*,DATE_FORMAT(temp_filling_card.datee, '%d/%m/%Y') as datee from register,temp_filling_card
           WHERE register.id=temp_filling_card.customer_id
            AND register.login_id='$login_id' AND temp_filling_card.login_id='$login_id' AND register.delivery_boy_id='$d_b_id'");
        }
            
        
        return $query->result();
    }
    
    public function bottle_data()
 {
 	date_default_timezone_set("Asia/Karachi");
  $login_id=$_SESSION['login'][0]->id;
  $login_type=$_SESSION['login'][0]->type;
  $com_name='';
  $sph='';
  $sms=0;
  $bottle_deliver='';
  $first_last_name='';
  if($login_type==1)
  {
      $bottle_deliver='Admin';
      $sms=$_SESSION['login'][0]->sms;
      $com_name=$_SESSION['login'][0]->com_name;
      $sph=$_SESSION['login'][0]->phone_number;
      
  }
  else
  {
      $bottle_deliver=$_SESSION['login'][0]->user_name;
           
            $this->db->SELECT('*');
            $this->db->WHERE('id',$login_id);
            $query_l=$this->db->get('login');
            $dta_l=$query_l->result();
            $sms=$dta_l[0]->sms;
            $com_name=$dta_l[0]->com_name;
            $sph=$dta_l[0]->phone_number;

            
      
  }
  
   $id=$_POST['id'];
   $fd=$_POST['fd'];
   $er=$_POST['er'];
   $amount_rec=$_POST['am_rec'];
   $datee=date('Y-m-d');
   $timee=date('h:m:s');
   /*$ty=$_POST['ty'];*/
   /*$flag=1;*/
   
            $this->db->SELECT('*');
            $this->db->WHERE('customer_id',$id);
            $this->db->WHERE('login_id',$login_id);
            $this->db->WHERE('datee',$datee);
            $query_c=$this->db->get('filling_card');
            $dta_c=$query_c->result();
                
            if(empty($dta_c))
            {
                  
            
  
        $this->db->SELECT('*');
        $this->db->WHERE('id',$id);
        $this->db->WHERE('login_id',$login_id);
    	$qp=$this->db->get('register');
    	 $p=$qp->result();
    	 $price=$p[0]->price;
         $type=$p[0]->type;
         $number=$p[0]->number;
         $first_last_name=$p[0]->first_name;
         $date_wise=$fd*$price;
         $daily_b_blnc=$fd-$er;
         $daily_am_blnc=$date_wise-$amount_rec;

    	$this->db->SELECT('*');
    	$this->db->WHERE('customer_id',$id);
        $this->db->WHERE('login_id',$login_id);
    	$query=$this->db->get('filling_card');
    	$query_res=$query->result();
    	if(!empty($query_res))
    	{
            $query=$this->db->query("SELECT * FROM `filling_card` WHERE customer_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
              $dt=$query->result();
              $fd_db=$dt[0]->filled_deliver;
              $er_db=$dt[0]->empty_recieved;
              $bb_db=$dt[0]->bottle_blnc;
    		  $ta_db=$dt[0]->total_amount;
              $am_rec_db=$dt[0]->amount_rec;
              $ab_db=$dt[0]->amount_blnc;
              
              $bb_db_a=$bb_db+$fd;
              $bb_db_b=$bb_db_a-$er;//bottle-balence
              $ab_db_a=$ab_db+($fd*$price);//chang total-am
              $ab_db_b=$ab_db_a-$amount_rec;//amount-balence
    	      $ta_db_a=$ta_db+($fd*$price);//total amount
              $ta_db_b=$ta_db_a;//total_amount
         
            $data = array(
                    'customer_id' => $id,
                    'datee' => $datee,
                    'timee' => $timee,
                    'filled_deliver' => $fd,
                    'empty_recieved' => $er,
                    'bottle_blnc' => $bb_db_b,
                    'total_amount' => $ab_db_a,
                    'amount_rec' => $amount_rec,
                    'amount_blnc' => $ab_db_b,
                    'login_id'=>$login_id,
                    'bottle_deliver'=>$bottle_deliver,
                    'date_wise'=>$date_wise,
                    'daily_bottle_blnc'=>abs($daily_b_blnc),
                    'daily_am_blnc'=>abs($daily_am_blnc),
                    
            ); 

          
            $this->db->insert('filling_card', $data);






        }
    	else
    	{
    		$total_amount=$price*$fd;
    	
              $amount_blnc=$total_amount-$amount_rec;
             
         
              if($amount_blnc==0)
              {
              	$amount_blnc=0;
              }
    	
    		$bottle_blnc=$fd-$er;
    		$data = array(
                    'customer_id' => $id,
                    'datee' => $datee,
                    'timee' => $timee,
                    'filled_deliver' => $fd,
                    'empty_recieved' => $er,
                    'bottle_blnc' => $bottle_blnc,
                    'total_amount' => $total_amount,
                    'amount_rec' => $amount_rec,
                    'amount_blnc' => $amount_blnc,
                    'login_id'=>$login_id,
                    'bottle_deliver'=>$bottle_deliver,
                    'date_wise'=>$date_wise,
                    'daily_bottle_blnc'=>abs($daily_b_blnc),
                    'daily_am_blnc'=>abs($daily_am_blnc),
            );
    		
    		$this->db->insert('filling_card', $data);
    	}
         
         
         $this->db->where('customer_id',$id);
         $this->db->where('login_id',$login_id);
         $this->db->DELETE('temp_filling_card');

            $this->db->SELECT('*');
            $this->db->where('login_id',$login_id);
            $this->db->WHERE('type',$type);
            $this->db->WHERE('datee',$datee);
            $query_ad=$this->db->get('admin_view');
            $dta_ad=$query_ad->result();
              $t_am=0;
              $am_b=0;
              $b_bl=0;
             if(empty($total_amount)){$t_am=$ab_db_a;}
             else{$t_am=$total_amount;}
             
             if(empty($amount_blnc)){$am_b=$ab_db_b;}
             else{$am_b=$amount_blnc;}
            
             if(empty($bottle_blnc))
                 {
                     if(@$bb_db_b!='')
                     {
                     $b_bl=$bb_db_b;
                     }
                 }
                 else
                 {
                     $b_bl=$bottle_blnc;
                 }
         
               
            /*sms-Api*/         
           if($sms==1)
           {
           $number= substr($number, -10);
           $number='92'.$number;
            $username = "923223344458";///Your Username
$password = "5748";///Your Password
$mobile = "$number";///Recepient Mobile Number
$sender = "SenderID";
$message = "".$first_last_name.
 " Fill deliver: ". $fd.'
'."Empty-rec:". $er.'
'."Amount rec:". $amount_rec.'  
'."Bottle Bal:".$b_bl.' 
'."Amount Bal:".$am_b.'
'."Regards".'
'.$com_name.'
'.$sph;

////sending sms

$post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."";
$url = "http://bulksms.com.pk/api/sms.php?username=923223344458&password=5748";
$ch = curl_init();
$timeout = 30; // set to zero for no timeout
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$result = curl_exec($ch); 
/*Print Responce*/
/*echo $result;*/ 
    
           }
            
           /*sms-Api*/  
            /*$am_b=$date_wise-$amount_rec;
                $bb=$fd-$er; */  
         if(empty($b_bl))
         {
             $b_bl=0;
         }
         if(empty($am_b))
         {
            $am_b=0;   
         }
         
            if(empty($dta_ad))
            {
               
            
               $data = array(
                    'filled_deliver' => $fd,
                    'empty_rec' => $er,
                    'total_amount'=>$t_am,
                     'ta_rec' => $amount_rec,
                    'amount_blnc' =>$am_b,
                    'type'=>$type,
                    'datee' => $datee,
                    'login_id'=>$login_id,
                    'date_wise'=>$date_wise,
                    'bb'=>$b_bl,
                 );
                
           $this->db->insert('admin_view', $data);          
            }
            else
            {
            /*    $u=abs($daily_am_blnc);
                 $e=abs($daily_b_blnc);*/
                
               $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver+'$fd',empty_rec=empty_rec+'$er',total_amount=total_amount+'$t_am',ta_rec=ta_rec+'$amount_rec',amount_blnc=amount_blnc+'$am_b',date_wise=date_wise+'$date_wise',bb=bb+'$b_bl' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' ");

            }
            
return 1;            
}

           else
            {
               return 2;         
            }


 }

    public function delete_user($id)
    {
        $st=1;
        $login_id=$_SESSION['login'][0]->id;
        $this->db->query("UPDATE  register SET status='$st' WHERE id='$id' AND login_id='$login_id'");
    }

    public function detail_reg_name($id)
    {
      $login_id=$_SESSION['login'][0]->id;
        $query=$this->db->query("SELECT * FROM `filling_card` INNER JOIN register ON filling_card.customer_id=register.id
          WHERE filling_card.customer_id='$id' AND register.id='$id'
          AND filling_card.login_id='$login_id' AND register.login_id='$login_id'");
        return $query->result();
    }
    
        public function delivery_boy_id_detail_reg($id)
        {
            $this->db->SELECT("delivery_boy_id as dbid");
            $this->db->Where('id',$id);
            $qu=$this->db->get('register');
            $res=$qu->result();
            $dbid=$res[0]->dbid;
             $this->db->SELECT("*");
            $this->db->Where('delivery_boy_idd',$dbid);
            $query=$this->db->get('delivery_boy');
             return $query->result();
            
        }
    
       public function detail_reg($id)
    {
         $login_id=$_SESSION['login'][0]->id;

        $query=$this->db->query("SELECT * FROM `filling_card` 
        WHERE filling_card.customer_id='$id' AND filling_card.login_id='$login_id'");
        return $query->result();
    }
     public function edit_user($id)
    {
        $login_id=$_SESSION['login'][0]->id;

          $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy,delivery_boy.delivery_boy_idd as dbid from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.id='$id' AND register.login_id='$login_id'");
        return $query->result();
    }

     public function all_days($id)
    {
        $login_id=$_SESSION['login'][0]->id;

        $this->db->SELECT("days");
        $this->db->WHERE('user_id',$id);
         $this->db->WHERE('login_id',$login_id);
        $query=$this->db->get('days');
        return $query->result_array();
    }


    public function edit_user_auth()
    {
        $fn=$_POST['fn'];
        $ln=$_POST['ln'];
        $pn=$_POST['pn'];
        $add=$_POST['add'];
        $type=$_POST['r_v'];
        $datee=$_POST['datee'];
        $price=$_POST['price'];
        $deposit=$_POST['deposit'];
        $db_id=$_POST['db_id'];
        $id=$_POST['id'];
        $login_id=$_SESSION['login'][0]->id;
        $status=0;
    $data = array(
        'first_name' => $fn,
        'last_name' => $ln,
        'number' => $pn,
        'datee' => $datee,
        'address' => $add,
        'price' => $price,
        'type' => $type,
        'status' => $status,
        'deposit' => $deposit,
        'delivery_boy_id' => $db_id,
       
);

    $this->db->where('id', $id);
    $this->db->where('login_id', $login_id);
    $this->db->update('register', $data);



if(!empty($_POST['ab'])|| !empty($_POST['bb']))
{
    	date_default_timezone_set("Asia/Karachi");
        $ab=0;
       if(!empty($_POST['ab']))
       {
           $ab=$_POST['ab'];
       }
       $bb=0;
       if(!empty($_POST['bb']))
       {
    	$bb=$_POST['bb'];
       }
    	$datee=date('Y-m-d');
    	$timee=date('h-m-s');
    	   $d_old = array(
                    'customer_id' =>$id,
                    'datee' => $datee,
                    'timee' => $timee,
                    'filled_deliver' =>0,
                    'empty_recieved' =>0,
                    'bottle_blnc' => $bb,
                    'total_amount' =>0,
                    'amount_rec' => 0,
                    'amount_blnc' => $ab,
                    'login_id'=>$login_id,
                    'bottle_deliver'=>'Admin',
            );
          
            $this->db->insert('filling_card', $d_old);
         
}





    
    
    
    
    return 1;

    }

  public function detail_user_delete()
  {
     $login_id=$_SESSION['login'][0]->id;
    $id=$_POST['id'];
  $this->db->where('login_id', $login_id);
    $this->db->where('id', $id);
    $this->db->delete('filling_card');
    return 1;
  }


  public function admin_view()
{
      $login_id=$_SESSION['login'][0]->id;
      $fdate=@$_POST['fdate'];
      $todate=@$_POST['todate'];
      date_default_timezone_set("Asia/Karachi");
      $today_datee=date('Y-m-d');
   if($fdate==''&&$todate=='')
           {
           
           
        $query=$this->db->query("SELECT DATE_FORMAT(datee, '%d/%m/%Y') as dte,admin_view.* from admin_view WHERE   admin_view.login_id='$login_id' AND admin_view.datee='$today_datee'");
           }
           if($fdate!=''&&$todate!='')
           {
                $query=$this->db->query("SELECT DATE_FORMAT(datee, '%d/%m/%Y') as dte,admin_view.* from admin_view WHERE   admin_view.login_id='$login_id' AND admin_view.datee BETWEEN '$fdate' AND '$todate'");
           }
            if($fdate!=''&&$todate=='')
           {
                $query=$this->db->query("SELECT DATE_FORMAT(datee, '%d/%m/%Y') as dte,admin_view.* from admin_view WHERE   admin_view.login_id='$login_id' AND admin_view.datee='$fdate'");
           }
             if($fdate==''&&$todate!='')
           {
                $query=$this->db->query("SELECT DATE_FORMAT(datee, '%d/%m/%Y') as dte,admin_view.* from admin_view WHERE   admin_view.login_id='$login_id' AND admin_view.datee='$todate'");
           }
    	return $query->result();
    
}

 public function in_active_cus()
  {
    $id=$_POST['id'];
   $st=1;
   $login_id=$_SESSION['login'][0]->id;
    $this->db->SET('status',$st );
    $this->db->WHERE('id',$id );
    $this->db->where('login_id', $login_id);
    $this->db->update('register');
    return 1;
  }
  public function active_cus()
  {
    $id=$_POST['id'];
   $st=0;
   $login_id=$_SESSION['login'][0]->id;
    $this->db->SET('status',$st );
    $this->db->WHERE('id',$id );
    $this->db->where('login_id', $login_id);
    $this->db->update('register');
    return 1;
  }

public function all_delivery_boy()
{
   $st=1;
   $login_id=$_SESSION['login'][0]->id;
   $this->db->SELECT("*");
   $this->db->WHERE('id', $login_id);    
   $this->db->WHERE('status',$st );
  $query=$this->db->get('delivery_boy');
   return $query->result();

}
 

public function add_delivery_boy()
{

  $id=$_SESSION['login'][0]->id;
      $un=$_POST['un'];
      $pass=$_POST['pass'];
      $st=1;
      $ty=2;
 
  $this->db->SELECT("*");
  $this->db->where('user_name',$un);
  $nu= $this->db->get('delivery_boy');
  $result=$nu->result();
  if(!empty($result))
  {
      return 3;
      exit;
  }
  
  
  
  $this->db->SELECT("delivery_boy_limit as dbl");
  $this->db->where('id',$id);
  $query=$this->db->get('login');
  $res=$query->result();
  $dbl=$res[0]->dbl;
  $this->db->SELECT("*");
  $this->db->where('id',$id);
  $num = $this->db->get('delivery_boy');
  $res_c=$num->result();
  $res_count=count($res_c);
  
  if($dbl==$res_count)
  {
       return 2;

  }
  else
  {

  
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
    
     

  



}


public function all_day_by_user()
{
    $login_id=$_SESSION['login'][0]->id;
    $id=$_POST['id'];
    $this->db->SELECT("*");
    $this->db->Where('user_id',$id);
    $this->db->Where('login_id',$login_id);
    $qu=$this->db->get("days");
    return $qu->result();
}

public function day_update()
{
   $id=$_POST['id'];
   $slc_id=$_POST['slc'];
   $this->db->SET('days',$slc_id);
  $this->db->Where('id',$id);
   $this->db->update('days');
   return 2;
}
public function day_delete()
{
      $id=$_POST['id'];
      $this->db->Where('id',$id);
      $this->db->DELETE('days');
      return 1;
    
}
    
    
    
    public function today_delivery_modal_data()
    {
     $login_id=$_SESSION['login'][0]->id;
     $id=$_POST['id'];
     date_default_timezone_set("Asia/Karachi");
      $datee=date('Y-m-d');
            
            $this->db->SELECT('*');
            $this->db->WHERE('customer_id',$id);
            $this->db->WHERE('login_id',$login_id);
            $this->db->WHERE('datee',$datee);
            $query_c=$this->db->get('filling_card');
            $dta_c=$query_c->result();
            if(empty($dta_c))
            {
                
     $qu=$this->db->query("SELECT * FROM `filling_card` WHERE customer_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
        $res= $qu->result();
        if(empty($res))
        {
            return 1;
        }
        else
        {
            return $res;
        }
        }
        else
        {
            return 3;
        }
    }
    
    
public function get_delivery_boy()
{
    $id=$_POST['id'];
    $this->db->SELECT("*");
    $this->db->Where('delivery_boy_idd',$id);
    $qu=$this->db->get("delivery_boy");
    return $qu->result();
}
    
    
    
    public function update_delivery_boy()
{
    $num=$_POST['num'];
    $pass=$_POST['pass'];
    $id=$_POST['id'];
    
  $nu=$this->db->query("SELECT * FROM `delivery_boy` WHERE user_name='$num' AND delivery_boy_idd<>'$id'");
  $result=$nu->result();
  if(!empty($result))
  {
      return 2;
      exit;
  }
   else
   
  {
     $this->db->SET('user_name',$num);
     $this->db->SET('pass',$pass);
     $this->db->where('delivery_boy_idd',$id);
     $this->db->update('delivery_boy'); 
    return 1;
      
  }
  
    
}   

 public function delete_delivery_boy()
 {
      $id=$_POST['id'];
      $this->db->Where('delivery_boy_idd',$id);
      $this->db->DELETE('delivery_boy');
      return 1;
     
 }
  public function slc_add_days()
 {
    $slc=$_POST['sel'];
    $id=$_POST['user_id'];
    $login_id=$_SESSION['login'][0]->id;
     
       for($i=0; $i < sizeof($slc); $i++) 
       {
           $this->db->SELECT("*");
           $this->db->where('user_id', $id);
           $this->db->where('days', $slc[$i]);
           $ch=$this->db->get('days');
           $resu=$ch->result();
           if(empty($resu))
           {
       $dat = array(
        'days' => $slc[$i],
        'user_id' => $id,
        'login_id'=>$login_id,
);

$this->db->insert('days', $dat);
           }
               
               
           }
     
    return 1;
 }
   
   
   
  public function add_old_order()
{
   $fd=$_POST['fd'];
   $er=$_POST['er'];
   $bb=$_POST['bb'];
   $ta=$_POST['ta'];
   $ar=$_POST['ar'];
   $ab=$_POST['ab'];
   $datee=$_POST['datee'];
   $timee=$_POST['timee'];
   $h_id=$_POST['h_id'];
   $login_id=$_SESSION['login'][0]->id;
   /*$this->db->SELECT('*');
        $this->db->WHERE('id',$id);
        $this->db->WHERE('login_id',$login_id);
    	$qp=$this->db->get('register');
    	 $p=$qp->result();
    	 $price=$p[0]->price;
          */
    
            $data = array(
                    'customer_id' => $h_id,
                    'datee' => $datee,
                    'timee' => $timee,
                    'filled_deliver' => $fd,
                    'empty_recieved' => $er,
                    'bottle_blnc' => $bb,
                    'total_amount' => $ta,
                    'amount_rec' => $ar,
                    'amount_blnc' => $ab,
                    'login_id'=>$login_id,
                    'bottle_deliver'=>'Admin',
            );
          
            $this->db->insert('filling_card', $data);
             return 1;
    
    
} 
   

public function detail_sale_report()
    {
           $login_id=$_SESSION['login'][0]->id;
           $fdate=@$_POST['fdate'];
           $todate=@$_POST['todate'];
           date_default_timezone_set("Asia/Karachi");
           $today_datee=date('Y-m-d');
           
           if($fdate!=''&&$todate=='')
           {
              
               $qu=$this->db->query("SELECT register.type as typ , register.first_name as rfn,register.last_name as rln,filling_card.*,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
LEFT JOIN register on register.id=filling_card.customer_id
WHERE register.login_id=filling_card.login_id AND filling_card.login_id='$login_id' AND filling_card.datee='$fdate'");
           }
           if($fdate==''&&$todate!='')
           {
               
               
               $qu=$this->db->query("SELECT register.type as typ , register.first_name as rfn,register.last_name as rln,filling_card.*,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
LEFT JOIN register on register.id=filling_card.customer_id
WHERE register.login_id=filling_card.login_id AND filling_card.login_id='$login_id' AND filling_card.datee='$todate'");
               
           }
           if($fdate!=''&&$todate!='')
           {
               
               $qu=$this->db->query("SELECT register.type as typ , register.first_name as rfn,register.last_name as rln,filling_card.*,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
LEFT JOIN register on register.id=filling_card.customer_id
WHERE register.login_id=filling_card.login_id AND filling_card.login_id='$login_id' AND filling_card.datee BETWEEN '$fdate' AND '$todate'");
           }
           if($fdate==''&&$fdate=='')
           {
               $qu=$this->db->query("SELECT register.type as typ , register.first_name as rfn,register.last_name as rln,filling_card.*,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
LEFT JOIN register on register.id=filling_card.customer_id
WHERE register.login_id=filling_card.login_id AND filling_card.login_id='$login_id' AND filling_card.datee='$today_datee'");
           }
           
           return $qu->result();
        
    }

public function active_user_by_date()
{
    $login_id=$_SESSION['login'][0]->id;
        $fdate=@$_POST['fdate'];
         $st=0;
           $todate=@$_POST['todate'];
           
           if($fdate!=''&&$todate=='')
           {
               $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id' AND register.datee='$fdate'");
           }
            if($fdate==''&&$todate!='')
           {
               $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id' AND register.datee='$todate'");
           }
           if($fdate!=''&&$todate!='')
           {
           
        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id' AND register.datee BETWEEN '$fdate' AND '$todate'");
           }
             if($fdate==''&&$todate=='')
           {
           
        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id'");
           }
    	return $query->result();
}
    
    
    public function google_chart()
    {
          $login_id=$_SESSION['login'][0]->id;
        $qu=$this->db->query("SELECT SUM(admin_view.total_amount) as ta,SUM(admin_view.ta_rec) as ar,SUM(admin_view.amount_blnc) as ab FROM `admin_view` WHERE admin_view.login_id='$login_id'");
        return $qu->result();
    }
    
    

public function counter_sale_auth()
{
     date_default_timezone_set("Asia/Karachi");
    $am=$_POST['c_s'];
    $datee=date('Y-m-d');
    //for counter_sale type 3
    $type=3;
    $login_id=$_SESSION['login'][0]->id;
    
    $data = array(

                    'datee' => $datee,
                    'amount' =>$am,
                    'login_id'=>$login_id
                 );
            $this->db->insert('counter_sale', $data);
    
            $this->db->SELECT('*');
            $this->db->where('login_id',$login_id);
            $this->db->WHERE('type',$type);
            $this->db->WHERE('datee',$datee);
            $query_ad=$this->db->get('admin_view');
            $dta_ad_c=$query_ad->result();
            if(empty($dta_ad_c))
            {
               /*$data = array(
                    'filled_deliver' =>'0',
                    'empty_rec' => 0,
                    'total_amount' =>$am,
                    'ta_rec' =>$am,
                    'amount_blnc' =>0,
                    'type'=>$type,
                    'datee' => $datee,
                    'login_id'=>$login_id
                 );*/
                 $data = array(
                    'filled_deliver' =>0,
                    'empty_rec' => 0,
                    'total_amount'=>$am,
                    'ta_rec' => $am,
                    'amount_blnc' =>0,
                    'type'=>$type,
                    'datee' => $datee,
                    'login_id'=>$login_id,
                    'date_wise'=>$am,
                    'bb'=>0,
                 );
            $this->db->insert('admin_view', $data);              
            }
            else
            {


                
               $this->db->query("UPDATE admin_view SET total_amount=total_amount+'$am',ta_rec=ta_rec+'$am',date_wise=date_wise+'$am' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' ");

            }

   
    return 1;
}


public function all_counter_sale()
  {
        $login_id=$_SESSION['login'][0]->id;
        $todate=@$_POST['fdate'];
        date_default_timezone_set("Asia/Karachi");
        $today_datee=date('Y-m-d');
      if($todate=='')
      {
       $query=$this->db->query("SELECT counter_sale.*,DATE_FORMAT(counter_sale.datee, '%d/%m/%Y') as dt  from  counter_sale where login_id='$login_id' AND counter_sale.datee='$today_datee'");
      }
      else
      {
          $query=$this->db->query("SELECT counter_sale.*,DATE_FORMAT(counter_sale.datee, '%d/%m/%Y') as dt  from  counter_sale where login_id='$login_id' AND counter_sale.datee='$todate'");
          
      }
    return $query->result();
  }
  
  public function update_counter_sale()
  {
      $login_id=$_SESSION['login'][0]->id;
      $id=$_POST['id'];
      $am_new=$_POST['num'];
      $am_old=$_POST['am_old'];
      $datee=$_POST['datee'];
      $type=3;
        $this->db->query("UPDATE admin_view SET total_amount=total_amount-'$am_old',ta_rec=ta_rec-'$am_old',date_wise=date_wise-'$am_old' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' ");
       
       $this->db->query("UPDATE admin_view SET total_amount=total_amount+'$am_new',ta_rec=ta_rec+'$am_new',date_wise=date_wise+'$am_new' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' "); 
      
        $this->db->query("UPDATE counter_sale SET amount='$am_new' WHERE id='$id' "); 
      
      return 1;

      
      
  }
  public function delete_counter_sale()
{
    $id=$_POST['id'];
    $am=$_POST['am'];
    $datee=$_POST['datee'];
    $type=3;
    
    $login_id=$_SESSION['login'][0]->id;

    $this->db->query("UPDATE admin_view SET total_amount=total_amount-'$am' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' "); 
     
     $this->db->WHERE('id',$id);
     $this->db->DELETE('counter_sale');
     return 1;
    
    
}


public function pass_update()
{
    if(!empty($_POST['cp']))
    {
    $pass=$_POST['cp'];
    $id=$_SESSION['login'][0]->id;
    $user=$_SESSION['login'][0]->user_name;
     $this->db->where('user_name',$user);
     $this->db->where('id',$id);
     $this->db->SET('password',$pass);
     $this->db->update('login');
     session_destroy(); 
      return 1;
        
    }
    else
    {
      return 2;  
    }
    
}


public function am_blnc_report()
{
    $login_id=$_SESSION['login'][0]->id;
    $qu=$this->db->query("SELECT register.first_name as fn, register.last_name as ln,register.number as number,register.price as price, SUM(filling_card.daily_bottle_blnc) as bb, SUM(filling_card.daily_am_blnc) as ab,SUM(filling_card.date_wise) as am ,filling_card.customer_id FROM `filling_card` LEFT JOIN register on register.id=filling_card.customer_id WHERE filling_card.login_id='$login_id' GROUP BY filling_card.customer_id");
    return $qu->result();

    
}



public function detail_edit()
{
   
   
   $fdd=$_POST['fdd'];
   $err=$_POST['err'];
   $bbb=$_POST['bbb'];
   $taa=$_POST['taa'];
   $arr=$_POST['arr'];
   $abb=$_POST['abb'];
   $amm=$_POST['amm'];
   
   
   
   $fd=$_POST['fd'];
   $er=$_POST['er'];
   $bb=$_POST['bb'];
   $ta=$_POST['ta'];
   $ar=$_POST['ar'];
   $ab=$_POST['ab'];
   $datee=$_POST['datee'];
   $r_id=$_POST['r_id'];
   $am=$_POST['am'];
   $h_id=$_POST['h_id'];
   $login_id=$_SESSION['login'][0]->id;
   	date_default_timezone_set("Asia/Karachi");
    
    	$am_b=$am-$ar;
    	$b_bl=$fd-$er;
    	   $d_old = array(
                    'customer_id' => $h_id,
                    'filled_deliver' =>$fd,
                    'empty_recieved' =>$er,
                    'bottle_blnc' => $bb,
                    'total_amount' =>$ta,
                    'amount_rec' => $ar,
                    'amount_blnc' => $ab,
                    'login_id'=>$login_id,
                     'date_wise'=>$am,
                    'daily_bottle_blnc'=>abs($am_b),
                    'daily_am_blnc'=>abs($b_bl),
            );
          
            $this->db->WHERE('id',$r_id);
            $this->db->update('filling_card', $d_old);
            
             $this->db->SELECT("*");
             $this->db->WHERE('id',$h_id);
             $q=$this->db->get('register'); 
             $re=$q->result();
             $type=$re[0]->type;
   
   $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver-'$fdd',empty_rec=empty_rec-'$err',total_amount=total_amount-'$taa',ta_rec=ta_rec-'$arr',amount_blnc=amount_blnc-'$abb',date_wise=date_wise-'$amm',bb=bb-'$bbb' WHERE datee='$datee' AND type='$type' AND login_id='$login_id' ");
   
   $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver+'$fd',empty_rec=empty_rec+'$er',total_amount=total_amount+'$ta',ta_rec=ta_rec+'$ar',amount_blnc=amount_blnc+'$ab',date_wise=date_wise+'$am',bb=bb+'$bb' WHERE datee='$datee' AND type='$type' AND login_id='$login_id' ");
   
    return 1;
}
    



public function plant_reg_auth()
{
   $login_id=$_SESSION['login'][0]->id;
   $fn=$_POST['fn'];
   $num=$_POST['pn'];
   $add=$_POST['add'];
   $pr=$_POST['price'];
   $ty=$_POST['type'];
   $d_old = array(
                    'name' => $fn,
                    'number' =>$num,
                    'address' =>$add,
                    'price' => $pr,
                    'type' =>$ty,
                    'login_id'=>$login_id,
                     
            );
          
            $this->db->insert('plant_reg',$d_old);
            return 1;
    
}

public function all_plant()
{
    $login_id=$_SESSION['login'][0]->id;
    $this->db->SELECT("*");
    $this->db->Where('login_id',$login_id);
    $q=$this->db->get("plant_reg");
    return $q->result();
}

public function plant_order()
 {
     $id=$_POST['id'];
     $login_id=$_SESSION['login'][0]->id; 
    $query=$this->db->query("SELECT * FROM `plant_filling` WHERE plant_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
    $res =$query->result();
     if(empty($res))
        {
            return 1;
        }
        else
        {
            return $res;
        }
 }
 public function plant_order_auth()
 {
     date_default_timezone_set("Asia/Karachi");
     $id=$_POST['id'];
     $ed=$_POST['ed'];
     $fr=$_POST['fr'];
     $am_rec=$_POST['am_rec'];
     $datee=date('Y-m-d');
     $login_id=$_SESSION['login'][0]->id; 
  
        $this->db->SELECT('*');
        $this->db->WHERE('id',$id);
        $this->db->WHERE('login_id',$login_id);
    	$qp=$this->db->get('plant_reg');
    	 $p=$qp->result();
    	 $price=$p[0]->price;
       
        $this->db->SELECT('*');
        $this->db->WHERE('plant_id',$id);
        $this->db->WHERE('login_id',$login_id);
    	$pq=$this->db->get('plant_filling');
         $pq_res=$pq->result();
         if(empty($pq_res))
         {
  
           $bb=$ed-$fr;
           $am=$price*$ed;
           $am_b=$am-$am_rec;
           $data= array(
                    'datee' => $datee,
                    'm_t_b' =>$ed,
                    'refil_b' =>$fr,
                    'blnc_b' => $bb,
                    'amount' =>$am,
                    'amount_rec' =>$am_rec,
                    'am_blnc' =>$am_b,
                    't_am' => $am,
                    'login_id'=>$login_id,
                    'plant_id' =>$id,
                     
            );
          
            $this->db->insert('plant_filling',$data);
           return 1;
           exit;
        }
        else
        {
         $query=$this->db->query("SELECT * FROM `plant_filling` WHERE plant_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
            $res =$query->result();
            
            $bb=$res[0]->blnc_b;
            $t_am=$res[0]->t_am;
            $am_b=$res[0]->am_blnc;
            
            $bb=$bb+$ed;
            $bottle_b=$bb-$fr;
           
            $amount=$price*$ed;
            $to_am=$am_b+$amount;
            $amount_b=$to_am-$am_rec;
           /* $t_am=$am_b+$amount;*/
            
            $dataa= array(
                    'datee' => $datee,
                    'm_t_b' =>$ed,
                    'refil_b' =>$fr,
                    'blnc_b' => $bottle_b,
                    'amount' =>$amount,
                    'amount_rec' =>$am_rec,
                    'am_blnc' =>$amount_b,
                    't_am' => $to_am,
                    'login_id'=>$login_id,
                    'plant_id' =>$id,
                     
            );
          
            $this->db->insert('plant_filling',$dataa);
           return 1;
           exit;
        }
 }
 
public function plant_b_reg($id)
{
     $login_id=$_SESSION['login'][0]->id; 
     $query=$this->db->query("SELECT * FROM `plant_filling` WHERE plant_id='$id' AND login_id='$login_id'");
    return $query->result();
}


     public function plant_all_reg_detail()
    {
        
        
        $login_id=$_SESSION['login'][0]->id;
      
        $r_id=$_GET['id'];
         
        $query=$this->db->query("SELECT * from plant_reg WHERE  plant_reg.login_id='$login_id' AND plant_reg.id='$r_id'");
    	return $query->result();
        
    }

    
    
 public function plant_reg_update()
 {
   $login_id=$_SESSION['login'][0]->id;
   $fn=$_POST['fn'];
   $num=$_POST['pn'];
   $add=$_POST['add'];
   $pr=$_POST['price'];
   $ty=$_POST['type'];
   $id=$_POST['id'];
   $d_old = array(
                    'name' => $fn,
                    'number' =>$num,
                    'address' =>$add,
                    'price' => $pr,
                    'type' =>$ty,
        
                     
            );
            $this->db->where('id',$id);
            $this->db->where('login_id',$login_id);
            $this->db->update('plant_reg',$d_old);
            return 1;
 
 }
    

    
    public function plant_detail_sale_report()
    {
           $login_id=$_SESSION['login'][0]->id;
           $fdate=@$_POST['fdate'];
           $todate=@$_POST['todate'];
           date_default_timezone_set("Asia/Karachi");
           $today_datee=date('Y-m-d');
           
           if($fdate!=''&&$todate=='')
           {
              
               $qu=$this->db->query("SELECT plant_filling.*, plant_reg.name as name,plant_reg.type as type, DATE_FORMAT(plant_filling.datee, '%d/%m/%Y') as fdatee
from plant_filling LEFT JOIN plant_reg on plant_reg.id=plant_filling.plant_id
WHERE plant_reg.login_id=plant_filling.login_id AND plant_filling.login_id='$login_id' AND plant_filling.datee='$fdate'");
           }
           if($fdate==''&&$todate!='')
           {
               
               
               $qu=$this->db->query("SELECT plant_filling.*, plant_reg.name as name,plant_reg.type as type, DATE_FORMAT(plant_filling.datee, '%d/%m/%Y') as fdatee
from plant_filling LEFT JOIN plant_reg on plant_reg.id=plant_filling.plant_id
WHERE plant_reg.login_id=plant_filling.login_id AND plant_filling.login_id='$login_id' AND plant_filling.datee='$todate'");
               
           }
           if($fdate!=''&&$todate!='')
           {
               
               $qu=$this->db->query("SELECT plant_filling.*, plant_reg.name as name,plant_reg.type as type, DATE_FORMAT(plant_filling.datee, '%d/%m/%Y') as fdatee
from plant_filling LEFT JOIN plant_reg on plant_reg.id=plant_filling.plant_id
WHERE plant_reg.login_id=plant_filling.login_id AND plant_filling.login_id='$login_id' AND plant_filling.datee BETWEEN '$fdate' AND '$todate'");
           }
           if($fdate==''&&$fdate=='')
           {
               $qu=$this->db->query("SELECT plant_filling.*, plant_reg.name as name,plant_reg.type as type, DATE_FORMAT(plant_filling.datee, '%d/%m/%Y') as fdatee
from plant_filling LEFT JOIN plant_reg on plant_reg.id=plant_filling.plant_id
WHERE plant_reg.login_id=plant_filling.login_id AND plant_filling.login_id='$login_id' AND plant_filling.datee='$today_datee'");
           }
           
           return $qu->result();
        
    }
    
    public function expense_auth($data)
    {
         $this->db->insert('expense',$data);
           
    }
  
  
  
  
  public function exp_view()
    {
           $login_id=$_SESSION['login'][0]->id;
           $fdate=@$_POST['fdate'];
           $todate=@$_POST['todate'];
           date_default_timezone_set("Asia/Karachi");
           $today_datee=date('Y-m-d');
           
           if($fdate!=''&&$todate=='')
           {
              
               $qu=$this->db->query("SELECT expense.*,DATE_FORMAT(expense.datee, '%d/%m/%Y') as fdatee from expense
WHERE expense.login_id='$login_id' AND expense.datee='$fdate'");
           }
           if($fdate==''&&$todate!='')
           {
               
               
               $qu=$this->db->query("SELECT expense.*,DATE_FORMAT(expense.datee, '%d/%m/%Y') as fdatee from expense
WHERE expense.login_id='$login_id' AND expense.datee='$todate'");
               
           }
           if($fdate!=''&&$todate!='')
           {
               
               $qu=$this->db->query("SELECT expense.*,DATE_FORMAT(expense.datee, '%d/%m/%Y') as fdatee from expense
WHERE expense.login_id='$login_id' AND expense.datee BETWEEN '$fdate' AND '$todate'");
           }
           if($fdate==''&&$fdate=='')
           {
               $qu=$this->db->query("SELECT expense.*,DATE_FORMAT(expense.datee, '%d/%m/%Y') as fdatee from expense
WHERE expense.login_id='$login_id' AND expense.datee='$today_datee'");
           }
           
           return $qu->result();
        
    }
  
  
    public function RegisterUser()
  {
    $un=$_POST['un'];
    $pass=$_POST['pass'];
    $s_datee=$date = date('Y-m-d');//$_POST['s_datee'];
    $e_datee=$date = date('Y-m-d', strtotime("+30 day"));//$_POST['e_datee'];
    $cn=$_POST['cn'];
    $ca=$_POST['ca'];
    $pn=$_POST['pn'];
    $dbl=2;
    $sms_id=1;
    
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
         'address'=>$ca,
         'type'=>1,
         'start_datee'=>$s_datee,
         'end_datee'=>$e_datee,
         'phone_number'=>$pn,
         'is_app_register'=>0,
         'per_month_amount'=>0.0,
         'delivery_boy_limit'=>$dbl,
         'sms'=>$sms_id,
        );
        $this->db->insert('login',$data);
        return 1;
     }
     else
     {
        return 2;
     }
  }
  
    
    
}



?>