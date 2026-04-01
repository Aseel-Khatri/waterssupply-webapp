<?php
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
class Api_cont extends CI_Controller
{
  public $response  = array();

    public $apiSec      = "!QiA$2018@2019!";
    public $apiKey      = "MnxPc2FtYXxLU29TbDlWcnM2ME81bXA=";


  



	public function __construct() {

          parent::__construct();
          $this->load->helper(array('form', 'url'));
          $this->load->library('form_validation');
          $this->verifyChecksum();
          $this->load->model('Api_mod','m');
          $this->load->model('Saeed_mod','sm');


    }

    public function verifyChecksum()
    {
        
       // return true;
        $getHeaders = getallheaders();
        
       /* echo '<pre>';
        print_r($getHeaders['X-API-SECRET']);die;*/
        
        $sec  = isset($getHeaders['X-API-SECRET'])?$getHeaders['X-API-SECRET']:'';
        $key  = isset($getHeaders['X-API-TOKEN'])?$getHeaders['X-API-TOKEN']:'';
        
        if(empty($sec) &&  empty($key)){

              $sec  = isset($getHeaders['x-api-secret'])?$getHeaders['x-api-secret']:'';
            $key  = isset($getHeaders['x-api-token'])?$getHeaders['x-api-token']:'';
        }
        
        //echo $sec;die;


        //------------OLD_APP--------------------

        $functionName = $this->router->fetch_method();

        $NotHeaderfunctions=array('app_register','app_login_auth','update_password','app_cron');

        foreach ($NotHeaderfunctions as $e) {
            
             if($e==$functionName){return true;die;}
        }
        
        //------------OLD_APP--------------------
        
      //  echo $sec.'--'.$this->apiSec.'--'.$key.'--'.$this->apiKey;die;

        if($sec == $this->apiSec && $key == $this->apiKey)
        {
                return true;
        }else{

            $this->SendResponce('error',400,"invalid or not verified key");
        }

    }
    
    
    
    
    
    
    
    
    
    
    ///---------------OLD-APP
    
    
    public function app_register()
    {
	
           $un=$_POST['un'];
            $pass=$_POST['pass'];
            $s_datee=$date = date('Y-m-d');//$_POST['s_datee'];
            $e_datee=$date = date('Y-m-d', strtotime("+1 day"));//$_POST['e_datee'];
            $cn=$_POST['cn'];
            $ca=$_POST['ca'];
            $pn=$_POST['pn'];
            $dbl=2;
            $sms_id=1;
	  	   if(!empty($pn) && !empty($pass) && !empty($un) &&  !empty($ca) && !empty($cn))
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
               $resul=$this->sm->app_register($data);

               

	  	   }else
	  	   {

                $response['result']['status']   = 'error';
                $response['result']['response'] = "Some fields are blank";

                echo json_encode($response);
                die;
	  	   } 
	  }

    public function app_login_auth()
    {
          $PhoneNumber=$_POST['phone_number'];;
          $pn = substr($PhoneNumber, -10);
          $phn='0'.$pn;
   	      $us=$phn;
	  	  $pass=$_POST['password'];
	  	    if(!empty($us) && !empty($pass) )
	  	   {
               $resul=$this->sm->app_login_auth($us,$pass);
 
	  	   }else
	  	   {

                $response['result']['status']   = 'error';
                $response['result']['response'] = "Some fields are blank";

                echo json_encode($response);
                die;
	  	   }
   }
    public function update_password(){
      
          $PhoneNumber=$_POST['Phone_number'];;
          $pn = substr($PhoneNumber, -10);
          $phn='0'.$pn;
   	      $us=$phn;
	  	  $pass=$_POST['New_password'];
	  	    if(!empty($us) && !empty($pass) )
	  	   {
              $this->sm->update_password($us,$pass);
 
	  	   }else
	  	   {

                $response['result']['status']   = 'error';
                $response['result']['response'] = "Some fields are blank";

                echo json_encode($response);
                die;
	  	   }
   }
    public function app_cron()
    {
   	
    $resul=$this->sm->app_cron();  	
   }
    
    
    ///---------------OLD-APP
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

	public function register(){


        $config = array(
            array(
                    'field' => 'username',
                    'label' => 'username',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'company_name',
                    'label' => 'company_name',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'phone_number',
                    'label' => 'phone_number',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'address',
                    'label' => 'address',
                    'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{
            
            $un=$_POST['username'];
            $pass=$_POST['password'];
            $s_datee=$date = date('Y-m-d');//$_POST['s_datee'];
            $e_datee=$date = date('Y-m-d', strtotime("+10 year"));//$_POST['e_datee'];
            $cn=$_POST['company_name'];
            $ca=$_POST['address'];
            $pn=$_POST['phone_number'];
            $dbl=2;
            $sms_id=1;
	  	  
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
           $result=$this->m->app_register($data);
           if($result['status']=='error'){

             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
	
	}

    public function login()
    {

        $config = array(
            array(
                    'field' => 'phone_number',
                    'label' => 'phone_number',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'type',
                    'label' => 'type field is required 1->admin & 2-> deliveryboy',
                    'rules' => 'required'
            )
        );


        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{


             $PhoneNumber=$_POST['phone_number'];;
  	         $pass=$_POST['password'];
  	   
            $result=$this->m->app_login_auth($PhoneNumber,$pass,$_POST['type']);

            if($result['status']=='error'){

             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
  	   }
    }


    public function today_delivery()
    {
       
       $config = array(
            array(
                    'field' => 'user_id',
                    'label' => 'login user_id',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'type',
                    'label' => 'type',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'type',
                    'label' => 'type field is required 1->admin & 2-> deliveryboy',
                    'rules' => 'required'
            )
        );


       if ($this->input->post("type") != '1' && $this->input->post("type") != '') {

            $this->form_validation->set_rules('delivery_boy_id', 'delivery_boy_id', 'required');

       }

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->today_delivery();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function all_delivery()
    {
       
       $config = array(
            array(
                    'field' => 'user_id',
                    'label' => 'login user_id',
                    'rules' => 'required'
            )
        );


        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->all_delivery();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }

    public function client_balences()
    {
       
       $config = array(
            array(
                    'field' => 'user_id',
                    'label' => 'login user_id',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'client_id',
                    'label' => 'client_id',
                    'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->client_balences();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function save_bottle_data()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'type',
                'rules' => 'required'
            ),
            array(
                'field' => 'client_id',
                'label' => 'client_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'filled_deliver',
                'label' => 'filled_deliver',
                'rules' => 'required'
            ),
            array(
                'field' => 'empty_recieved',
                'label' => 'empty_recieved',
                'rules' => 'required'
            ),
             array(
                'field' => 'amount_recieved',
                'label' => 'amount_recieved',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->bottle_data();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }

    public function customer_register(){
        
        
        //	$sel= explode(",",$_POST['days_of_giving']);
        //	print_r($sel);die;
    	$config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'first_name',
                'rules' => 'required'
            ),
            array(
                'field' => 'last_name',
                'rules' => 'required'
            ),
            array(
                'field' => 'number',
                'rules' => 'required'
            ),
            array(
                'field' => 'address',
                'rules' => 'required'
            ),
            array(
                'field' => 'days_of_giving',
                'rules' => 'required'
            ),
            array(
                'field' => 'deliver_type',
                'rules' => 'required'
            ),
            array(
                'field' => 'deposit',
                'rules' => 'required'
            ),
            
             array(
                'field' => 'price',
                'rules' => 'required'
            ),
            
            array(
                'field' => 'delivery_boy_id',
                'rules' => 'required'
            ),
           /* array(
                'field' => 'other',
                'rules' => 'required'
            )*/
        );
    
        $this->form_validation->set_rules($config);
         $this->form_validation->set_rules('date_', 'date_', "trim|required|callback_dob_check");
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{
    
            $result =$this->m->customer_register();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);
    
           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
    }


    public function get_customers()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
             array(
                'field' => 'type',
                'label' => 'type 2 = inactive , 1= all-active , 3 = inactive+ active',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->all_active();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }

    public function all_in_active()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->all_in_active();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }


    public function customer_detail()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
             array(
                'field' => 'customer_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->customer_detail();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function day_of_giving()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
             array(
                'field' => 'customer_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->day_of_giving();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
    
    public function edit_day_of_giving()
    {
       

       $config = array(
            array(
                'field' => 'customer_id',
                'label' => 'customer_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'days_of_giving',
                'rules' => 'required'
            )
            
        );
 
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->edit_day_of_giving();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }
    
    
    
    public function delete_day_of_giving()
    {
       

       $config = array(
            array(
                'field' => 'customer_id',
                'label' => 'customer_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'day_id',
                'rules' => 'required'
            )
            
        );
 
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->delete_day_of_giving();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function customer_detail_edit()
    {
       

       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'filled_delivered',
                'rules' => 'required'
            ),
            array(
                'field' => 'empty_rec',
                'rules' => 'required'
            ),
            array(
                'field' => 'bottle_blnc',
                'label' => 'bottle_blnc',
                'rules' => 'required'
            ),
            array(
                'field' => 'total_amount',
                'label' => 'total_amount',
                'rules' => 'required'
            ),
            array(
                'field' => 'amount_rec',
                'label' => 'amount_rec',
                'rules' => 'required'
            ),
            array(
                'field' => 'amount_blnc',
                'rules' => 'required'
            ),
            array(
                'field' => 'amount',
                'rules' => 'required'
            ),
            array(
                'field' => 'detail_id',
                'label' => 'detail_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'customer_id',
                'label' => 'customer_id',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
         $this->form_validation->set_rules('date_', 'date_', "trim|required|callback_dob_check");
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->customer_detail_edit();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }


    public function edit_customer()
    {
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'first_name',
                'rules' => 'required'
            ),
            array(
                'field' => 'last_name',
                'rules' => 'required'
            ),
            array(
                'field' => 'number',
                'rules' => 'required'
            ),
            array(
                'field' => 'address',
                'rules' => 'required'
            ),
            array(
                'field' => 'is_bottle',
                'label' => '1==can && bottle==2',
                'rules' => 'required'
            ),
            array(
                'field' => 'price',
                'rules' => 'required'
            ),
            array(
                'field' => 'deposit',
                'rules' => 'required'
            ),
            array(
                'field' => 'customer_id',
                'label' => 'customer_id',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
         $this->form_validation->set_rules('date_', 'date_', "trim|required|callback_dob_check");
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->edit_customer();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }
    
    
    public function update_customer_status()
    {
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'status',
                'label'=>'active or inactive',
                'rules' => 'required'
            ),
            
            array(
                'field' => 'customer_id',
                'label' => 'customer_id',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
       
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->update_customer_status();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg']);
           }
        }
   
    }





    public function get_expenses()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->exp_view();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
    public function save_expense()
    {
        
        $today_datee=date('Y-m-d');
        $input_data = json_decode(file_get_contents('php://input'), true);
        
        
        if(isset($input_data['data'])){
            
            foreach($input_data['data'] as $v){
                
               
                $data=array(
                    'username'=>$v['expense_name'],
                    'price'=>$v['price'],
                    'datee'=>$today_datee,
                    'login_id'=>$v['login_id']
                );
                $this->m->save_expense($data);
            }
             
            $this->SendResponce('success',200,'successfully updated','');
        }
        $this->SendResponce('error',404,$this->form_validation->error_array());
        
    }
    
    
    public function exp_edit()
    {
        
       $config = array(
            array(
                'field' => 'exp_name',
                'rules' => 'required'
            ),
            array(
                'field' => 'price',
                'rules' => 'required'
            ),
            array(
                'field' => 'exp_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->exp_edit();
            $this->SendResponce('success',200,'successfully updated','');
        }
        
    }
    
    
    public function exp_delete()
    {
        
       $config = array(
            array(
                'field' => 'exp_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->exp_delete();
            $this->SendResponce('success',200,'successfully updated','');
        }
        
    }



    public function get_counter_sale()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->get_counter_sale();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
    public function update_counter_sale()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'sale_id',
                'label' => 'sale_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'new_sale_amount',
                'label' => 'new_sale_amount',
                'rules' => 'required'
            ),
            array(
                'field' => 'old_sale_amount',
                'label' => 'old_sale_amount',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_rules('date_', 'date_', "trim|required|callback_dob_check");
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->update_counter_sale();
            $this->SendResponce('success',200,'successfully updated','');
        }
   
    }
    
    
    public function delete_counter_sale()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'sale_id',
                'label' => 'sale_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'sale_amount',
                'label' => 'sale_amount',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_rules('date_', 'date_', "trim|required|callback_dob_check");
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->delete_counter_sale();
            $this->SendResponce('success',200,'successfully Deleted','');
        }
   
    }
    
    
    public function add_counter_sale()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'amount',
                'label' => 'amount',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
       
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->add_counter_sale();
            $this->SendResponce('success',200,'successfully Added','');
        }
   
    }








    public function dob_check($str)
    {
        
        if (!DateTime::createFromFormat('Y-m-d', $str)) { //yes it's YYYY-MM-DD
            $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format.Date formate should be in  Y-m-d');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
    
    public function get_all_plants()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->all_plant();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function plant_detail_by_id()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'id',
                'label' => 'plant id ',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->plant_detail_by_id();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function plant_personal_detail_by_id()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'id',
                'label' => 'plant id ',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->plant_personal_detail_by_id();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function add_plant()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'name',
                'rules' => 'required'
            ),
            array(
                'field' => 'number',
                'rules' => 'required'
            ),
            array(
                'field' => 'address',
                'rules' => 'required'
            ),
            array(
                'field' => 'price',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'type Can=1 , Bottle=2',
                'rules' => 'required'
            )
            
        );

        $this->form_validation->set_rules($config);
      
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->add_plant();
             if($result['status']=='error'){
             $this->SendResponce('error',400,$result['msg']);

            }else{
              $this->SendResponce('success',200,$result['msg'],'');
            }
        }
   
    }
    
    
    
    public function plant_order()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'empty_rec',
                'rules' => 'required'
            ),
            array(
                'field' => 'refil_rec',
                'rules' => 'required'
            ),
            array(
                'field' => 'am_rec',
                'rules' => 'required'
            ),
            array(
                'field' => 'plant_id',
                'rules' => 'required'
            )
            
        );

        $this->form_validation->set_rules($config);
      
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->plant_order_auth();
             if($result['status']=='error'){
             $this->SendResponce('error',400,$result['msg']);

            }else{
              $this->SendResponce('success',200,$result['msg'],'');
            }
        }
   
    }
    
    
    
    
    public function edit_plant()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'name',
                'rules' => 'required'
            ),
            array(
                'field' => 'number',
                'rules' => 'required'
            ),
            array(
                'field' => 'address',
                'rules' => 'required'
            ),
            array(
                'field' => 'price',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'type Can=1 , Bottle=2',
                'rules' => 'required'
            ),
            array(
                'field' => 'id',
                'label' => 'plant id',
                'rules' => 'required'
            )
            
        );

        $this->form_validation->set_rules($config);
      
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->edit_plant();
             if($result['status']=='error'){
             $this->SendResponce('error',400,$result['msg']);

            }else{
              $this->SendResponce('success',200,$result['msg'],'');
            }
        }
   
    }
    
    
    public function delete_plant()
    {
       
       $config = array(
            array(
                'field' => 'id',
                'label' => 'plant id',
                'rules' => 'required'
            ),
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
      
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->delete_plant();
            $this->SendResponce('success',200,'successfully Deleted','');
        }
   
    }
    
    
    
    
    
    public function get_delivery_boy()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->get_delivery_boy();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
    public function edit_delivery_boy()
    {
       
       $config = array(
            array(
                'field' => 'id',
                'label' => 'delivery boy id',
                'rules' => 'required'
            ),
            array(
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->edit_delivery_boy();
            if($result['status']=='error'){
             $this->SendResponce('error',400,$result['msg']);

            }else{
              $this->SendResponce('success',200,$result['msg'],'');
            }
        }
   
    }

    
     public function add_delivery_boy()
    {
       
       $config = array(
            array(
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ),
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            ),
            array(
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
     
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->add_delivery_boy();
            
            if($result['status']=='error'){
             $this->SendResponce('error',400,$result['msg']);

            }else{
              $this->SendResponce('success',200,$result['msg'],'');
            }
        }
   
    }

    public function delete_delivery_boy()
    {
       
       $config = array(
            array(
                'field' => 'id',
                'label' => 'delivery boy id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
      
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->delete_delivery_boy();
            $this->SendResponce('success',200,'successfully Deleted','');
        }
   
    }


//REPORTS

    public function detail_sale_report()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules('start_date', 'start_date', "trim|required|callback_dob_check");
        
        $this->form_validation->set_rules('end_date', 'end_date', "trim|required|callback_dob_check");

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->detail_sale_report();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    public function sales_report()
    {
       
       $config = array(
            array(
                'field' => 'user_id',
                'label' => 'login user_id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules('start_date', 'start_date', "trim|required|callback_dob_check");
        
        $this->form_validation->set_rules('end_date', 'end_date', "trim|required|callback_dob_check");

        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->sales_report();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
    
    
    
    public function number_check()
    {
       $config = array(
            array(
                'field' => 'number',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
        
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->number_check();
          
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    
    
     public function forgot_pass()
    {
       $config = array(
            array(
                'field' => 'password',
                'rules' => 'required'
            ),
            array(
                'field' => 'user_id',
                'rules' => 'required'
            )
        );
 
        $this->form_validation->set_rules($config);
        
        if($this->form_validation->run()==FALSE){
            
              $this->SendResponce('error',404,$this->form_validation->error_array());
             
        }else{

            $result =$this->m->forgot_pass();
            if($result['status']=='error'){
             $this->SendResponce('error',404,$result['msg']);

           }else{
              $this->SendResponce('success',200,$result['msg'],$result['data']);
           }
        }
   
    }
    











   public function SendResponce($type,$status='',$msg='',$data=''){

        $this->response['error'] = ($type=='error')?true:false;
        $this->response['status'] = $status;
        $this->response['message']  = $msg;
        $this->response['data']  = ($data=='')?array():$data;
        $this->response = json_encode($this->response);
        header('Content-Type: application/json');
        echo $this->response;
        exit;

   }
 
}
