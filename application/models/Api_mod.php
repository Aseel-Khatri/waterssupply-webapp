<?php
class Api_mod extends CI_Model 
{


    public function app_register($data)
    {
        
        
        $u=$data['phone_number'];
        $check = $this->db->query("SELECT * FROM login AS b  where b.phone_number = '$u'");
        if($check->num_rows() > 0)
        {
            
            $response['status']   = 'error';
            $response['msg'] = "Phone number is Already Exists.";

        }else{

           $this->db->insert('login',$data);
          $id=$this->db->insert_id();
          $this->db->SELECT(" * ");
          $this->db->WHERE('b.id',$id);
          $qu=$this->db->get('login AS b');
          $res=$qu->result();

          $response['status']   = 'success';
          $response['msg'] = "Sucessfully Registered";
          $response['data'] = $res;

        }

        return $response;
    }



    public function app_login_auth($us,$pass,$type)
    {
         
        //type 1->admin 2->delivery_boy


        if($type==1){
            $this->db->SELECT("*");
            $this->db->WHERE('b.phone_number',$us);
            $this->db->WHERE('b.password',$pass);
            $qu=$this->db->get('login As b');
        }else{
            
            
            $this->db->select('db.* ,l.com_name,l.phone_number');
            $this->db->from('login As l');
            $this->db->join('delivery_boy db', 'db.id = l.id', 'inner');
            $this->db->where('db.pass',$pass);
            $this->db->where('db.user_name',$us);
            $qu=$this->db->get();

           /* $this->db->SELECT("*");
            $this->join()
            $this->db->WHERE('user_name',$us);
            $this->db->WHERE('pass',$pass);
            $qu=$this->db->get('delivery_boy');*/
        }


       

        $response['status']   = 'error';
        $response['msg'] = "Invalid";

        if($qu->num_rows() > 0)
        {
            
            $res=$qu->result();

            $response['status']   = 'error';
            $response['msg']      = "Your Account is expired";

           if($res[0]->status==1)
            {               
                $response['status']   = 'success';
                $response['msg']      = "Successfully Login";
                $response['data']     = $res;
        
            }

        }
        return $response;
  
    }



    public function today_delivery()
    {
        date_default_timezone_set("Asia/Karachi");
        $login_id=$this->input->post("user_id");
        $login_type=$this->input->post("type");
        $st=0;
        $day=date('D');
            
        $datee=date('Y-m-d');
     
                $query=$this->db->query("SELECT register.* FROM register INNER JOIN days on days.user_id=register.id WHERE days.days='$day' AND register.login_id=days.login_id AND days.login_id='$login_id'AND register.status='$st' ");
      
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
        $query=$this->db->query("SELECT register.other,  register.id as client_id, register.*,DATE_FORMAT(temp_filling_card.datee, '%d/%m/%Y') as datee from register,temp_filling_card
           WHERE register.id=temp_filling_card.customer_id
            AND register.login_id='$login_id' AND temp_filling_card.login_id='$login_id'");
        }
        else
        {
            $d_b_id=$this->input->post("delivery_boy_id");
            $query=$this->db->query("SELECT register.other, register.id as client_id, register.*,DATE_FORMAT(temp_filling_card.datee, '%d/%m/%Y') as datee from register,temp_filling_card
           WHERE register.id=temp_filling_card.customer_id
            AND register.login_id='$login_id' AND temp_filling_card.login_id='$login_id' AND register.delivery_boy_id='$d_b_id'");
        }
         
        $response['status']   = 'error';
        $response['msg']      = "not any delivery";
        $sendArray=array();
        if($query->num_rows() > 0)
        {
            
            
            foreach($query->result() as $qv){
                    $get_balc=$this->client_balences_($qv->client_id,$qv->login_id);
                   // print_r($get_balc);die;
                    $sendArray[]=array(
                    
                            "client_id"=>$qv->client_id,
                            "id"=>$qv->id,
                            "first_name"=>$qv->first_name,
                            "last_name"=>$qv->last_name,
                            "number"=>$qv->number,
                            "datee"=>$qv->datee,
                            "address"=>$qv->address,
                            "price"=>$qv->price,
                            "other"=>$qv->other,
                            "type"=>$qv->type,
                            "status"=>$qv->status,
                            "deposit"=>$qv->deposit,
                            "login_id"=>$qv->login_id,
                            "delivery_boy_id"=>$qv->delivery_boy_id,
                            
                            "amount_blnc"=>$get_balc['amount_blnc'],                            
                            "bottle_blnc"=>$get_balc['bottle_blnc'],
                            "total_amount"=>$get_balc['total_amount'],
                            "daily_am_blnc"=>$get_balc['daily_am_blnc']
                            /*"client_balences"=>$this->client_balences_($qv->client_id,$qv->login_id)*/
                        
                        
                        );
            }
                
            $response['status']   = 'success';
            $response['msg']      = "Successfully ";
            $response['data']     = $sendArray;
        
        }


        //print_r($response);
        return $response;   
        
    }


    public function client_balences_($id,$login_id)
    {
       
        date_default_timezone_set("Asia/Karachi");
        $datee=date('Y-m-d');
            
        $this->db->SELECT('*');
        $this->db->WHERE('customer_id',$id);
        $this->db->WHERE('login_id',$login_id);
        $this->db->WHERE('datee',$datee);
        $query_c=$this->db->get('filling_card');
        $dta_c=$query_c->result();

        $response['status']   = 'error';
        $response['msg']      = "not any delivery";
        $response['data']=array(
                                'bottle_blnc'=>0,
                                'daily_am_blnc'=>0,
                                'total_amount'=>0,
                                'amount_blnc'=>0
                                );

        if(empty($dta_c))
        {
            
            $qu=$this->db->query("SELECT  bottle_blnc ,daily_am_blnc ,total_amount,amount_blnc FROM `filling_card` WHERE customer_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
            $res= $qu->result();
            if(!empty($res))
            {
                $response['status']   = 'success';
                $response['msg']      = "Successfully ";
                $response['data']     = array(
                                        'bottle_blnc'=>$res[0]->bottle_blnc,
                                        'daily_am_blnc'=>$res[0]->daily_am_blnc,
                                        'amount_blnc'=>$res[0]->amount_blnc,
                                        'total_amount'=>$res[0]->total_amount
                                        
                                        );
            }
        }
         return $response['data'];
    }
    
    
    
    public function client_balences_all_delivery($id,$login_id)
    {
       
       
        $response['status']   = 'error';
        $response['msg']      = "not any delivery";
        $response['data']=array(
                                'bottle_blnc'=>0,
                                'daily_am_blnc'=>0,
                                'total_amount'=>0,
                                'amount_blnc'=>0,
                                );
            
        $qu=$this->db->query("SELECT  bottle_blnc ,amount_blnc,daily_am_blnc ,total_amount FROM `filling_card` WHERE customer_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
        $res= $qu->result();
        if(!empty($res))
        {
            $response['status']   = 'success';
            $response['msg']      = "Successfully ";
            $response['data']     = array(
                                    'bottle_blnc'=>$res[0]->bottle_blnc,
                                    'amount_blnc'=>$res[0]->amount_blnc,
                                    'daily_am_blnc'=>$res[0]->daily_am_blnc,
                                    'total_amount'=>$res[0]->total_amount
                                    );
        }
        
         return $response['data'];
    }







    public function client_balences()
    {
        $login_id=$this->input->post("user_id");
        $id=$this->input->post("client_id");
        date_default_timezone_set("Asia/Karachi");
        $datee=date('Y-m-d');
            
        $this->db->SELECT('*');
        $this->db->WHERE('customer_id',$id);
        $this->db->WHERE('login_id',$login_id);
        $this->db->WHERE('datee',$datee);
        $query_c=$this->db->get('filling_card');
        $dta_c=$query_c->result();

        $response['status']   = 'error';
        $response['msg']      = "not any delivery";

        if(empty($dta_c))
        {
            
            $qu=$this->db->query("SELECT * FROM `filling_card` WHERE customer_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
            $res= $qu->result();
            if(!empty($res))
            {
                $response['status']   = 'success';
                $response['msg']      = "Successfully ";
                $response['data']     = $res;
            }
        }
         return $response;
    }
    
    public function bottle_data()
    {
        date_default_timezone_set("Asia/Karachi");

        $login_id   =$this->input->post("user_id");
        $login_type =$this->input->post("type");
        $id         =$this->input->post("client_id");;
        $fd         =$this->input->post("filled_deliver");
        $er         =$this->input->post("empty_recieved");
        $amount_rec =$this->input->post("amount_recieved");

        $datee=date('Y-m-d');
        $timee=date('h:m:s');
        $com_name='';
        $sph='';
         $sms=0;
        $bottle_deliver='';
        $first_last_name='';
        $bottle_deliver=($login_type==1)?'Admin':'delivery-boy';

        $response['status']   = 'error';
        $response['msg']      = "Something went wrong"; 


        $this->db->SELECT('*');
        $this->db->WHERE('customer_id',$id);
        $this->db->WHERE('login_id',$login_id);
        $this->db->WHERE('datee',$datee);
        $query_c=$this->db->get('filling_card');
        $dta_c=$query_c->result();
                
        /*if(empty($dta_c))*/
        if(1==1)
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
            }else
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
            $t_am=(empty($total_amount))?$ab_db_a:$total_amount;
            $am_b=(empty($amount_blnc))?$ab_db_b:$amount_blnc;

            /* if(empty($amount_blnc)){$am_b=$ab_db_b;}
             else{$am_b=$amount_blnc;}*/

            if(empty($bottle_blnc))
            {
                if(@$bb_db_b!='')
                {
                    $b_bl=$bb_db_b;
                }
            }else
            {
                $b_bl=$bottle_blnc;
            }
         
                     
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
            }else
            {
                
               $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver+'$fd',empty_rec=empty_rec+'$er',total_amount=total_amount+'$t_am',ta_rec=ta_rec+'$amount_rec',amount_blnc=amount_blnc+'$am_b',date_wise=date_wise+'$date_wise',bb=bb+'$b_bl' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' ");
            }
            
            $response['status']   = 'success';
            $response['msg']      = "Saved";  

        }
        return $response;
    }



     //---------------START_CUSTOMERS
    //-----------------------------------


    public function number_check()
    {
        
    	$pn=$_POST['number'];

    	 $check = $this->db->query("SELECT * FROM login  where phone_number = '$pn'");
        if($check->num_rows() > 0)
        {
            
            $response['status']   = 'success';
            $response['msg']      = "registered";
            $response['data']     =$check->result();

        }else{
              $response['status']   = 'error';
              $response['msg']      = "not-registered";
        }
        
        return $response;
    }
    
    
    
    
    public function forgot_pass()
    {
        
    	$user_id=$_POST['user_id'];
    	$password=$_POST['password'];

    	 $check = $this->db->query("SELECT * FROM login  where id = '$user_id'");
        if($check->num_rows() > 0)
        {
            
            $this->db->WHERE('id',$user_id);
            $this->db->update('login', array('password'=>$password));
            
            $response['status']   = 'success';
            $response['msg']      = "updated";
            $response['data']     =  $check->result();

        }else{
              $response['status']   = 'success';
              $response['msg']      = "not-updated";
        }
        
        return $response;
    }
    
   // public function
















    public function customer_register()
    {
        $db_id="0";
        if(!empty($_POST['delivery_boy_id']))
        {
            $db_id=$_POST['delivery_boy_id'];
        }
      
    	$fn=$_POST['first_name'];
    	$ln=$_POST['last_name'];
    	$pn=$_POST['number'];
    	$add=$_POST['address'];
    	$sel= explode(",",$_POST['days_of_giving']);
    	

    	
    	@$type=@$_POST['deliver_type'];
    	
    	$datee=$_POST['date_'];
    	$price=$_POST['price'];
        $deposit=$_POST['deposit'];
        $login_id   =$this->input->post("user_id");
    	$status=0;
    	
    	 $check =false ;// $this->db->query("SELECT * FROM register AS b  where b.number = '$pn'");
        /*if($check->num_rows() > 0)*/
        if($check)
        {
            
            $response['status']   = 'error';
            $response['msg'] = "Phone number is Already Exists.";

        }else{
            $other='';
            if($type==3){
                if(isset($_POST['other']) && empty($_POST['other'])){
                    
                    $response['status']   = 'error';
                    $response['msg'] = "other filed is required.";
                    return $response;
                }else{
                    $other=$_POST['other'];
                }
            }
        
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
                 'other'=>$other,
            );
    
            $this->db->insert('register', $data);
            $insert_id=$this->db->insert_id();
        
           for($i=0; $i < count($sel); $i++) 
           {
               if(!empty($sel[$i])){
                   
                    $dat = array(
                        'days' => $sel[$i],
                        'user_id' => $insert_id,
                        'login_id'=>$login_id,
                    );
                    $this->db->insert('days', $dat);
               }
            }
    
            if(!empty($_POST['amount_blnc'])|| !empty($_POST['bottle_blanc']))
            {
        	    date_default_timezone_set("Asia/Karachi");
               $ab=0;
               if(!empty($_POST['amount_blnc']))
               {
                   $ab=$_POST['amount_blnc'];
               }
               $bb=0;
               if(!empty($_POST['bottle_blanc']))
               {
            	$bb=$_POST['bottle_blanc'];
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

            $response['status']   = 'success';
            $response['msg']      = "updated";
            
        }

        return $response;
    }

    public function all_active()
    {
        $login_id  =$this->input->post("user_id");
        $type  =$this->input->post("type");
        $st=0;
        $text='';
        if($type == 1){
            $text="register.status='0' AND ";//--active
        }elseif($type == 2){
            $text="register.status='1' AND ";//--In-active
        }else if($type ==3){
             $text='';
        }else{
                $response['status']   = 'error';
                $response['msg']      = "type should be between 1 to 3";
                return $response;
        }

        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id
            
        
        WHERE $text  register.login_id='$login_id'");
       
        $response['status']   = 'error';
        $response['msg']      = "not found";
        $sendArr=[];
        if(!empty($query->result()))
        {
            
            foreach($query->result() as $d){
                
                  $this->db->SELECT("id as days_id,days");
                 $this->db->Where('user_id',$d->id);
                 $this->db->Where('login_id',$login_id);
                 $query_days=$this->db->get("days");
                 
                
                
                $sendArr[]=array(
                    "id"=>$d->id,
                    "first_name"=>$d->first_name,
                    "last_name"=>$d->last_name,
                    "number"=>$d->number,
                    "datee"=>$d->datee,
                    "address"=>$d->address,
                    "price"=>$d->price,
                    "type"=>$d->type,
                    "status"=>$d->status,
                    "deposit"=>$d->deposit,
                    "other"=>$d->other,
                    "login_id"=>$d->login_id,
                    "delivery_boy_id"=>$d->delivery_boy_id,
                    "delivery_boy"=>$d->delivery_boy,
                    'days_of_giving'=>$query_days->result_array()
                    
                );
                
            }
            
        
                $response['status']   = 'success';
                $response['msg']      = "Successfully ";
                $response['data']     =$sendArr;//$query->result();
        }

        return $response;
    }
    public function all_in_active()
    {
        $login_id  =$this->input->post("user_id");
        $st=1;

        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE register.status='$st' AND register.login_id='$login_id'");
       
        $response['status']   = 'error';
        $response['msg']      = "not found";

        if(!empty($query->result()))
        {
                $response['status']   = 'success';
                $response['msg']      = "Successfully ";
                $response['data']     =$query->result();
        }

        return $response;
    }


    public function customer_detail()
    {
        $login_id=$this->input->post("user_id");
        $id=$this->input->post("customer_id");
        $fdate=$this->input->post('start_date');
        $todate=$this->input->post('end_date');
        
        
        $sdd=(!empty($fdate) && !empty($todate))?"AND filling_card.datee BETWEEN '$fdate' AND '$todate'":'';
        
        
        
        
        $query=$this->db->query("SELECT filling_card.datee, filling_card.id as detail_id,customer_id,  filled_deliver , empty_recieved,bottle_blnc,total_amount,amount_rec,amount_blnc,date_wise as amount FROM `filling_card` INNER JOIN register ON filling_card.customer_id=register.id
          WHERE filling_card.customer_id='$id' AND register.id='$id'
          AND filling_card.login_id='$login_id' AND register.login_id='$login_id'  $sdd");

        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        
        $query_cus=$this->db->query("SELECT DATE_FORMAT(register.datee, '%d/%m/%Y') as rdatee ,register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE  register.login_id='$login_id' AND register.id='$id'");
    	//return $query_cus->result();
        
        if(!empty($query_cus->result()))
        {
            
            
            
            
            
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data']['personal_details'] = $query_cus->result();
          $response['data']['filling_data'] = $query->result();
        }
        return $response;
    }

    public function customer_detail_edit()
    {

       
       $fd          =$this->input->post('filled_delivered');
       $er          =$this->input->post('empty_rec');
       $bb          =$this->input->post('bottle_blnc');
       $ta          =$this->input->post('total_amount');
       $ar          =$this->input->post('amount_rec');
       $ab          =$this->input->post('amount_blnc');
       $am          =$this->input->post('amount');
       $r_id        =$this->input->post('detail_id');
       $h_id        =$this->input->post('customer_id');
       $login_id    =$this->input->post("user_id");
       /* $datee      =$this->input->post("date_");*/


        $get_old_records_query =$this->db->query("SELECT * FROM `filling_card` INNER JOIN register ON filling_card.customer_id=register.id
          WHERE filling_card.customer_id='$h_id'");

        $response['status']   = 'error';
        $response['msg']      = "not found";


       /* echo '<pre>';
        print_r($get_old_records_query->result());die;*/



        if(!empty($get_old_records_query->result()))
        {


            $getresult=$get_old_records_query->result();
         
            $fdd     = $getresult[0]->filled_deliver;
            $err     = $getresult[0]->empty_recieved;
            $bbb     = $getresult[0]->bottle_blnc;
            $taa     = $getresult[0]->total_amount;
            $arr     = $getresult[0]->amount_rec;
            $abb     = $getresult[0]->amount_blnc;
            $amm     = $getresult[0]->date_wise;
        

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
                'daily_am_blnc'=>abs($b_bl)
            );
              
            $this->db->WHERE('id',$r_id);
            $this->db->update('filling_card', $d_old);
                
            $this->db->SELECT("*");
            $this->db->WHERE('id',$h_id);
            $q=$this->db->get('register'); 
            $re=$q->result();
            $type=$re[0]->type;
       
            $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver-'$fdd',empty_rec=empty_rec-'$err',total_amount=total_amount-'$taa',ta_rec=ta_rec-'$arr',amount_blnc=amount_blnc-'$abb',date_wise=date_wise-'$amm',bb=bb-'$bbb' WHERE  type='$type' AND login_id='$login_id' ");
           
            $this->db->query("UPDATE admin_view SET filled_deliver=filled_deliver+'$fd',empty_rec=empty_rec+'$er',total_amount=total_amount+'$ta',ta_rec=ta_rec+'$ar',amount_blnc=amount_blnc+'$ab',date_wise=date_wise+'$am',bb=bb+'$bb' WHERE type='$type' AND login_id='$login_id' ");

            $response['status']   = 'success';
            $response['msg']      = "updated";
       
            
        }
        return $response;
    }
    
    public function day_of_giving()
    {
        $login_id=$this->input->post("user_id");
        $id=$this->input->post("customer_id");
        $this->db->SELECT("id,days");
        $this->db->Where('user_id',$id);
        $this->db->Where('login_id',$login_id);
        $query=$this->db->get("days");
        
        
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $query->result();
        }
        return $response;
        
    }
    
    
    public function edit_day_of_giving()
    {
       
        $cid=$this->input->post("customer_id");
        $user_id=$this->input->post("user_id");
        $day=$this->input->post("days_of_giving");
        
        $sel= explode(",",$_POST['days_of_giving']);
            //Delete
            $this->db->Where('user_id',$cid);
            $this->db->Where('login_id',$user_id);
            $this->db->delete('days');
        
        for($i=0; $i < count($sel); $i++) 
        {
            if(!empty($sel[$i])){
               
                $dat = array(
                    'days' => $sel[$i],
                    'user_id' => $cid,
                    'login_id'=>$user_id,
                );
                $this->db->insert('days', $dat);
            }
        }
    
        /*$this->db->Where('user_id',$id);
        $this->db->Where('id',$day_id);
        $this->db->update('days',['days'=>$day]);*/
        
        $response['status']   = 'success';
        $response['msg']      = "updated";
        
       
        return $response;
        
    }
    
    
    public function delete_day_of_giving()
    {
       
        $id=$this->input->post("customer_id");
        $day_id=$this->input->post("day_id");
        

        $this->db->Where('user_id',$id);
        $this->db->Where('id',$day_id);
        $this->db->delete('days');
        
        $response['status']   = 'success';
        $response['msg']      = "Deleted";
        
        return $response;
        
    }
    
    
    public function edit_customer()
    {
        $fn         =$this->input->post('first_name');
        $ln         =$this->input->post('last_name');
        $pn         =$this->input->post('number');
        $add        =$this->input->post('address');
        $type       =$this->input->post('is_bottle');
        $datee      =$this->input->post('date_');
        $price      =$this->input->post('price');
        $deposit    =$this->input->post('deposit');
        $db_id      =$this->input->post('delivery_boy_id');
        $id         = $this->input->post('customer_id');
        $login_id   =$this->input->post("user_id");
        $other =$this->input->post("other");
      
        $data = array(

            'first_name' => $fn,
            'last_name' => $ln,
            'number' => $pn,
            'datee' => $datee,
            'address' => $add,
            'price' => $price,
            'type' => $type,
            'deposit' => $deposit,
            'delivery_boy_id' => $db_id,
            'other' => $other
           
        );

        $this->db->where('id', $id);
        $this->db->where('login_id', $login_id);
        $this->db->update('register', $data);



        if(!empty($this->input->post('amount_blnc'))|| !empty($this->input->post('bottle_blnc')))
        {
            date_default_timezone_set("Asia/Karachi");
            $ab=0;
           if(!empty($this->input->post('amount_blnc')))
           {
               $ab=$this->input->post('amount_blnc');
           }
           $bb=0;
           if(!empty($this->input->post('bottle_blnc')))
           {
            $bb=$this->input->post('bottle_blnc');
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
        
         $response['status']   = 'success';
        $response['msg']      = "updated";
        return $response;
    }
    
    
    public function update_customer_status()
    {
        
        $status      =($this->input->post('status')=='active')?0:1;
        $id          = $this->input->post('customer_id');
        $login_id    =$this->input->post("user_id");
       
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $id);
        $this->db->where('login_id', $login_id);
        $this->db->update('register', $data);
        
        $response['status']   = 'success';
        $response['msg']      = "updated";
        return $response;
    } 
    
    
  /*  public function delete_customer()
    {
        $exp_id=$this->input->post("exp_id");
        $this->db->where('id', $exp_id);
        $this->db->delete('expense');
        return true;
        
    }
  */



 //---------------END_CUSTOMERS
    //-----------------------------------



    //---------------START_EXPENSES
    //-----------------------------------

    public function save_expense($data=array()){
        
       // print_r($data);die;
         $this->db->insert('expense',$data);
         return true;
    }

    public function exp_view()
    {
       $login_id=$this->input->post("user_id");
       $fdate=$this->input->post("from_date");
       $todate=$this->input->post("to_date");
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

        $response['status']   = 'error';
        $response['msg']      = "not found";

        if(!empty($qu->result()))
        {
            $response['status']   = 'success';
            $response['msg']      = "Successfully ";
            $response['data']     =$qu->result();
        }
        return $response;
        
    }
    
    public function all_delivery()
    {
        $login_id  =$this->input->post("user_id");
        $st=0;

        $query=$this->db->query("SELECT register.*,delivery_boy.user_name as delivery_boy from register LEFT JOIN delivery_boy on delivery_boy .delivery_boy_idd=register.delivery_boy_id WHERE  register.login_id='$login_id'");
       
        $response['status']   = 'error';
        $response['msg']      = "not found";
$sendArray=array();
        if(!empty($query->result()))
        {
            foreach($query->result() as $qv){
                  $get_balc=$this->client_balences_all_delivery($qv->id,$qv->login_id);
                  //  print_r($get_balc['amount_blnc']);die;
                $sendArray[]=array(
                
                    "client_id"=>$qv->id,
                    "id"=>$qv->id,
                    "first_name"=>$qv->first_name,
                    "last_name"=>$qv->last_name,
                    "other"=>$qv->other,
                    "number"=>$qv->number,
                    "datee"=>$qv->datee,
                    "address"=>$qv->address,
                    "price"=>$qv->price,
                    "type"=>$qv->type,
                    "status"=>$qv->status,
                    "deposit"=>$qv->deposit,
                    "login_id"=>$qv->login_id,
                    "delivery_boy_id"=>$qv->delivery_boy_id,
                    "bottle_blnc"=>$get_balc['bottle_blnc'],
                    "total_amount"=>$get_balc['total_amount'],
                     "amount_blnc"=>$get_balc['amount_blnc'],
                    "daily_am_blnc"=>$get_balc['daily_am_blnc']
                   

                );
            }
                
                
                
            
                $response['status']   = 'success';
                $response['msg']      = "Successfully ";
                $response['data']     =$sendArray;
        }

        return $response;
    }
    
    public function exp_edit()
    {
        $exp_name=$this->input->post("exp_name");
        $price=$this->input->post("price");
        $exp_id=$this->input->post("exp_id");
      
        $save = array(
            'username' => $exp_name,
            'price' => $price
        );
        
        $this->db->where('id', $exp_id);
        $this->db->update('expense', $save);
        return true;
        
    }
    
    public function exp_delete()
    {
        $exp_id=$this->input->post("exp_id");
        $this->db->where('id', $exp_id);
        $this->db->delete('expense');
        return true;
        
    }
    
     //---------------END-EXPENSES
    //-----------------------------------
    
    
    //---------------START_COUNTEr-SALE
    //-----------------------------------
    
    public function get_counter_sale()
    {
        $login_id=$this->input->post("user_id");
        $todate=$this->input->post("date");
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
      
      
      
        $response['status']   = 'error';
        $response['msg']      = "not found";
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "Sucessfully Registered";
          $response['data'] = $query->result();  
        }
          
        return $response;
    } 
    
    
    public function add_counter_sale()
    {
        date_default_timezone_set("Asia/Karachi");
        $am=$this->input->post("amount");
        $datee=date('Y-m-d');
        //for counter_sale type 3
        $type=3;
        $login_id=$this->input->post("user_id");
        
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
        }else
        {

           $this->db->query("UPDATE admin_view SET total_amount=total_amount+'$am',ta_rec=ta_rec+'$am',date_wise=date_wise+'$am' WHERE
           datee='$datee' AND type='$type' AND login_id='$login_id' ");

        }
    
        return true;
    }
    
    public function update_counter_sale()
    {
      $login_id=$this->input->post("user_id");
      $id=$_POST['sale_id'];
      $am_new=$_POST['new_sale_amount'];
      $am_old=$_POST['old_sale_amount'];
      $datee=$_POST['date_'];
      $type=3;
        $this->db->query("UPDATE admin_view SET total_amount=total_amount-'$am_old',ta_rec=ta_rec-'$am_old',date_wise=date_wise-'$am_old' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' ");
       
       $this->db->query("UPDATE admin_view SET total_amount=total_amount+'$am_new',ta_rec=ta_rec+'$am_new',date_wise=date_wise+'$am_new' WHERE
               datee='$datee' AND type='$type' AND login_id='$login_id' "); 
      
        $this->db->query("UPDATE counter_sale SET amount='$am_new' WHERE id='$id' "); 
      
      return true;

      
      
  }
  
  
    public function delete_counter_sale()
    {
        $id=$_POST['sale_id'];
        $am=$_POST['sale_amount'];
        $datee=$_POST['date_'];
        $type=3;
        
       $login_id=$this->input->post("user_id");
    
        $this->db->query("UPDATE admin_view SET total_amount=total_amount-'$am' WHERE
                   datee='$datee' AND type='$type' AND login_id='$login_id' "); 
         
         $this->db->WHERE('id',$id);
         $this->db->DELETE('counter_sale');
         return 1;
        
        
    }
    
     //---------------END-COUNTER-SALE
    //-----------------------------------
    
    
    
    
     //---------------START_PLANT
    //-----------------------------------
    
    public function all_plant()
    {
        $login_id=$this->input->post("user_id");
        $this->db->SELECT("*");
        $this->db->Where('login_id',$login_id);
        $q=$this->db->get("plant_reg");
        
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($q->result()))
        {
            
            
            
            
            
            
            $sendArray=[];
            foreach($q->result() as $d){
                
                
                $id=$d->id;
         $query=$this->db->query("SELECT * FROM `plant_filling` WHERE plant_id='$id' AND login_id='$login_id' ORDER BY id DESC LIMIT 1");
         $res =$query->result();
                
                
                
                $sendArray[]=array(
                    
                    'id'       =>$d->id,
                    'name'     =>$d->name,
                    "number"   => $d->number,
                    "address"  => $d->address,
                    "price"    => $d->price,
                    "type"     =>$d->type,
                    "login_id" =>$d->login_id,
                    "m_t_b" =>(isset($res[0]->m_t_b))?$res[0]->m_t_b:0,
                    "refil_b" =>(isset($res[0]->refil_b))?$res[0]->refil_b:0,
                    "blnc_b" =>(isset($res[0]->blnc_b))?$res[0]->blnc_b:0,
                    "amount" =>(isset($res[0]->amount))?$res[0]->amount:0,
                    "amount_rec" =>(isset($res[0]->amount_rec))?$res[0]->amount_rec:0,
                    "am_blnc" =>(isset($res[0]->am_blnc))?$res[0]->am_blnc:0,
                    "t_am" =>(isset($res[0]->t_am))?$res[0]->t_am:0,
                    "m_t_b" =>(isset($res[0]->m_t_b))?$res[0]->m_t_b:0,
                    
                    );
                
                
            }
         //   print_r($res);die;
            
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $sendArray;
        }
        return $response;
    }
    
    
    
    public function plant_order_auth()
    {
        date_default_timezone_set("Asia/Karachi");
        
        $id        =$_POST['plant_id'];
        $ed        =$_POST['empty_rec'];
        $fr        =$_POST['refil_rec'];
        $am_rec    =$_POST['am_rec'];
        $datee     =date('Y-m-d');
        $login_id  =$this->input->post("user_id");
  
    
        $response['status']   = 'error';
        $response['msg'] = "Something went wrong";
  
  
  
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
            
            $response['status']   = 'success';
            $response['msg'] = "Successfully Added";
            
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
            
            $response['status']   = 'success';
            $response['msg'] = "Successfully Added";
        }
        
        return $response;
    }
    
    
    
    
    
    
    
    
    
    public function add_plant()
    {
       $login_id    =$this->input->post("user_id");
       $fn          =$this->input->post("name");
       $num         =$this->input->post("number");
       $add         =$this->input->post("address");
       $pr          =$this->input->post("price");
       $ty          =$this->input->post("type");
       
       if($ty ==1 || $ty==2 || $ty==3){
           
         
            $d_old = array(
                'name' => $fn,
                'number' =>$num,
                'address' =>$add,
                'price' => $pr,
                'type' =>$ty,
                'login_id'=>$login_id
                             
            );
              
            $this->db->insert('plant_reg',$d_old);
             
            $response['status']   = 'success';
            $response['msg'] = "Successfully Added";
            return $response;
           
       }
       
        $response['status']   = 'error';
        $response['msg'] = "Type should be 1 == Can or 2==Bottle";
        return $response;
     
    }
    
    public function delete_plant()
    {
        $id=$this->input->post("id");
        $login_id=$this->input->post("user_id");
    
         
        $this->db->where('id',$id);
        $this->db->where('login_id',$login_id);
        $this->db->DELETE('plant_reg');
         return true;
        
        
    }
    
    
    public function plant_detail_by_id()
    {
       
         $login_id=$this->input->post("user_id");
         $id=$this->input->post("id");
         
         $query=$this->db->query("SELECT * FROM `plant_filling` WHERE plant_id='$id' AND login_id='$login_id'");
         
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $query->result();
        }
        return $response;
    }
    
    public function plant_personal_detail_by_id()
    {
        
         $login_id=$this->input->post("user_id");
         $r_id=$this->input->post("id");
         
        $query=$this->db->query("SELECT * from plant_reg WHERE  plant_reg.login_id='$login_id' AND plant_reg.id='$r_id'");
        
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $query->result();
        }
        return $response;
        
    }
    
    
    public function edit_plant()
    {
        $login_id    =$this->input->post("user_id");
        $fn          =$this->input->post("name");
        $num         =$this->input->post("number");
        $add         =$this->input->post("address");
        $pr          =$this->input->post("price");
        $ty          =$this->input->post("type");
        $id          =$this->input->post("id");
       
       if($ty ==1 || $ty==2){
           
         
            $d_old = array(
                'name' => $fn,
                'number' =>$num,
                'address' =>$add,
                'price' => $pr,
                'type' =>$ty
                             
            );
              
            $this->db->where('id',$id);
            $this->db->where('login_id',$login_id);
            $this->db->update('plant_reg',$d_old);  
             
             
            $response['status']   = 'success';
            $response['msg'] = "Successfully Updated";
            return $response;
           
       }
       
        $response['status']   = 'error';
        $response['msg'] = "Type should be 1 == Can or 2==Bottle";
        return $response;
     
    }
    

    //---------------END-PLANT
    //-----------------------------------

    
    
    
    
    //---------------START_DELIVERY-BOY
    //-----------------------------------
    
    
    
    
    
    public function get_delivery_boy()
    {
       $st=1;
       $login_id=$this->input->post("user_id");
       $this->db->SELECT("delivery_boy_idd as id , pass , user_name");
       $this->db->WHERE('id', $login_id);    
       $this->db->WHERE('status',$st );
       $query=$this->db->get('delivery_boy');
      
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $query->result();
        }
        return $response;
       
    
    }
    
    public function edit_delivery_boy()
    {
        
      
        $num=str_replace(' ', '', $this->input->post("username"));
        $pass=$this->input->post("password");
        $id=$this->input->post("id");
        
        $nu=$this->db->query("SELECT * FROM `delivery_boy` WHERE user_name='$num' AND delivery_boy_idd<>'$id'");
        $result=$nu->result();
          if(!empty($result))
          {
                $response['status']   = 'error';
                $response['msg']      = "username already exist ";
                return $response;
          }

         $this->db->SET('user_name',$num);
         $this->db->SET('pass',$pass);
         $this->db->where('delivery_boy_idd',$id);
         $this->db->update('delivery_boy'); 
         
            $response['status']   = 'success';
            $response['msg'] = "Successfully updated";
            return $response;
          
    }   
    
    public function delete_delivery_boy()
    {
          $id=$this->input->post("id");
          $this->db->Where('delivery_boy_idd',$id);
          $this->db->DELETE('delivery_boy');
          return true;
         
     }
     
    
    public function add_delivery_boy()
    {
    
        $id=$this->input->post("user_id");
        $un=str_replace(' ', '', $this->input->post("username"));
        $pass=$this->input->post("password");
        $st=1;
        $ty=2;
     
        $this->db->SELECT("*");
        $this->db->where('user_name',$un);
        $nu= $this->db->get('delivery_boy');
        $result=$nu->result();
        
        if(!empty($result))
        {
                $response['status']   = 'error';
                $response['msg']      = "username already exist ";
                return $response;
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
            $response['status']   = 'error';
            $response['msg']      = "deliveryboy limit exceeded.";
            return $response;
    
        }else
        {
            $data=array(
              'id'=>$id,
              'user_name'=>$un,
              'pass'=>$pass,
              'status'=>$st,
              'type'=>$ty,
            );
            
            $this->db->insert('delivery_boy',$data);
            
            $response['status']   = 'success';
            $response['msg'] = "Successfully Added";
            return $response;

       }
        
       
    }
     
    //---------------END_DELIVERY-BOY
    //----------------------------------- 

 
  //Reports
  
   public function detail_sale_report()
    {
          
           $login_id=$this->input->post('user_id');
           $fdate=$this->input->post('start_date');
           $todate=$this->input->post('end_date');
           date_default_timezone_set("Asia/Karachi");
           $today_datee=date('Y-m-d');
 
           $qu=$this->db->query("SELECT register.other,register.type as type ,CONCAT(register.first_name,' ',register.last_name) as name, 
              filling_card.filled_deliver,
              filling_card.empty_recieved,
              filling_card.bottle_blnc,
              filling_card.total_amount,
              filling_card.amount_rec,
              filling_card.amount_blnc,
              filling_card.bottle_deliver as bottle_deliver_by,
              filling_card.date_wise as amount,
              filling_card.daily_bottle_blnc,
              filling_card.daily_am_blnc
           ,DATE_FORMAT(filling_card.datee, '%d/%m/%Y') as fdatee from filling_card
            LEFT JOIN register on register.id=filling_card.customer_id
            WHERE register.login_id=filling_card.login_id AND filling_card.login_id='$login_id' AND filling_card.datee BETWEEN '$fdate' AND '$todate'");

           
           
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($qu->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $qu->result();
        }
        return $response;

        
    }
    
    public function sales_report()
    {
        $login_id=$this->input->post('user_id');
        $fdate=$this->input->post('start_date');
        $todate=$this->input->post('end_date');
        date_default_timezone_set("Asia/Karachi");
        $today_datee=date('Y-m-d');
        
        $query=$this->db->query("SELECT admin_view.type,DATE_FORMAT(datee, '%d/%m/%Y') as dte,
         admin_view.filled_deliver,
         admin_view.empty_rec,
         admin_view.total_amount,
         admin_view.ta_rec as total_amount_rec,
         admin_view.amount_blnc,
         admin_view.datee as fdatee,
         admin_view.date_wise as amount,
         admin_view.bb as bottle_blnc
          from admin_view WHERE   admin_view.login_id='$login_id' AND admin_view.datee BETWEEN '$fdate' AND '$todate'");
           
        $response['status']   = 'error';
        $response['msg']      = "not found";
        
        if(!empty($query->result()))
        {
          $response['status']   = 'success';
          $response['msg'] = "data-found";
          $response['data'] = $query->result();
        }
        return $response;

    }

}
?>