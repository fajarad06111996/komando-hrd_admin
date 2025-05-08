<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelGlobal extends CI_Model
{
	protected $link;
	protected $filename;
	protected $office;
	protected $hub;
	protected $idx;
	protected $username;
	protected $level;
	protected $origin_hub;

	public function __construct()
    {
        parent::__construct();
        // is_logged_in();
        $this->load->model('ModelGlobal');
        $this->load->model('M_access');
		$this->load->library('security_function');
		$this->load->library('Secure');
		$this->link			= site_url().strtolower(get_class($this));
		$this->filename		= strtolower(get_class($this));
        $this->office   	= $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      	= $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      	= $this->secure->dec($this->session->userdata('JTidx'));
        $this->username 	= $this->session->userdata('JTuser_id');
        $this->level    	= $this->secure->dec($this->session->userdata('JTlevel'));
		$this->origin_hub 	= $this->secure->dec($this->session->userdata('JTorigin_hub_id'));
    }

    public function selectDateFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y')";
    }

    public function selectDateTimeFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y %H:%i')";
    }
    
    public function selectDateTimeSecondFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y %H:%i:%s')";
    }

	  public function whereDateFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y')";
    }

    public function executeDateFormat($date)
    {
        //set to MySQL
        return "STR_TO_DATE('" . $date . "', '%d-%b-%Y')";
    }
	
	public function getLogo()
    {
        return "<img src='" . base_url() . "assets/images/LOGO/QOURIER_EMAS.png' height='70px'>";
    }

    public function getBack()
    {
        return "<a href='javascript:window.history.go(-1);' class='text-lg text-white'><i class='fas fa-angle-left'></i></a>";
    }
	
	private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'gigihadip.if@gmail.com',
            'smtp_pass' => 'Prabowo@161191',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);

        $this->email->from('gigihadip.if@gmail.com', 'PT. Qourier');
        $this->email->to($this->input->post('signup-email'));

        if ($type == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Click this link to reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

	public function send_sms($hp, $pesan)
	{
		/*// Script Sent SMS 
        $userkey="tnwm2a"; // user
        $passkey="yoga1507"; // passs
		$url = "https://reguler.zenziva.net/";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$hp.'&pesan='.urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_exec($curlHandle);
        curl_close($curlHandle);
		
		//return $result;*/
		$userkey = 'tnwm2a';
		$passkey = 'yoga1507';
		$telepon = $hp;
		$message = $pesan;
		$url = 'https://gsm.zenziva.net/api/sendsms/';
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
			'userkey' => $userkey,
			'passkey' => $passkey,
			'nohp' => $telepon,
			'pesan' => $message
		));
		$results = json_decode(curl_exec($curlHandle), true);
		curl_close($curlHandle);
	}
	
	public function msgbox_success($tittle, $msg, $target)
	{
		echo "<link href='".base_url()."/assets/sweetalert/sweetalert.css' rel='stylesheet' />
				<script src='".base_url()."/assets/bsb/plugins/jquery/jquery.min.js'></script>
				<script src='".base_url()."/assets/sweetalert/sweetalert.min.js'></script>
				<script type='text/javascript'>
								  setTimeout(function () {  
								   swal({
									title: '" . $tittle . "',
									text: ' " . $msg . "',
									type: 'success',
									timer: 4000,
									showConfirmButton: false
								   });  
								  },10); 
								  window.setTimeout(function(){ 
								  window.location.href='" . $target . "';	
								  } ,2100); 
				</script>";
	}
	
	public function msgbox_error($tittle, $msg, $target)
	{
		echo "<link href='".base_url()."/assets/sweetalert/sweetalert.css' rel='stylesheet' />
				<script src='".base_url()."/assets/sweetalert/sweetalert.min.js'></script>
				<script type='text/javascript'>
								  setTimeout(function () {  
								   swal({
									title: '" . $tittle . "',
									text: ' " . $msg . "',
									type: 'error',
									timer: 4000,
									showConfirmButton: false
								   });  
								  },10); 
								  window.setTimeout(function(){ 
								  window.location.href='" . $target . "';	
								  } ,2100); 
				</script>";
	}
	
	function get_longitude_latitude_from_adress($address)
	{
		// Get lat and long by address   
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false&key=AIzaSyDPy7N15tvOlxfX_lsskUFMAwMzqUgpuwA');
        $output= json_decode($geocode);
        if($output->status == 'OK')
        {
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
    				
    				//echo $latitude . ", " . $longitude . "<br>";
    		
    		return [
    		 'lat' => $latitude,
    		 'lng' => $longitude
    		];
        }
        else
        {
            return 0;
        }
	}
	
	public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) 
	{
		$theta = $lon1 - $lon2;
		$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$miles = acos($miles);
		$miles = rad2deg($miles);
		$miles = $miles * 60 * 1.1515;
		$feet = $miles * 5280;
		$yards = $feet / 3;
		$kilometers = $miles * 1.609344;
		$meters = $kilometers * 1000;
		return compact('miles','feet','yards','kilometers','meters'); 
	}

	public function GetDrivingDistance($orglat, $destlat, $orglong, $destlong)
	{
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$orglat.",".$orglong."&destinations=".$destlat.",".$destlong."&key=AIzaSyABS0p11nyIwVhk1ve2ZrIdNAsaSB0qnu4&mode=driving";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response, true);
		$dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
		$time = $response_a['rows'][0]['elements'][0]['duration']['value'];

		return array('distance' => $dist, 'time' => $time);
	}

	public function passForApps($pass)
    {
		$ch = curl_init ( 'https://jtesystem.com/jtedriverauth/api/Passwordhashx' );
        curl_setopt_array ( $ch, array (
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(array(
                'password' => $pass
            )),
            CURLOPT_HTTPHEADER => array(
                'Authorization: APIKEY',
                'APIKEY: conto',
                'Content-Type: application/json'
            ),
            CURLOPT_RETURNTRANSFER => true
        ));
        $server_output = json_decode(curl_exec($ch), true);
        curl_close ($ch);
        return $server_output;
    }
	
	public function getAccessOffice($id_login)
	{
		$query = $this->db->query("select 
                                        a.user_idx,
                                        a.office_idx,
                                        b.office_name
                                    from user_access_office a, master_office b
                                    where a.office_idx=b.idx
                                    and b.status = 1
                                    and a.user_idx='$id_login'");
		return $query->result();
	}

	public function getOfficeActive($officeId)
	{
		$query = $this->db->query("select 
                                        idx,
                                        office_name,
                                        address,
                                        city,
                                        telephone
                                    from master_office 
                                    where status = 1
                                    and idx='$officeId'")->row_array(); //var_dump($query);die();
		return $query;
	}

	public function updateLastOH($id_login, $officeId)
	{
		// $id_login = $this->session->userdata('Qid_login');
		// $officeId = $this->session->userdata('Qoffice_id');
		$query = $this->db->query("update
                                        user_account
                                    set
                                        office_id='$officeId'
                                    where idx='$id_login'");
		return true;
	}
	
	public function validasiAngka($value_angka)
	{	
		$order   = array("Rp ", "_", ",");
		$result = str_replace($order, '', $value_angka);
		return $result;
	}

	public function listHub()
  	{
        $getHub = $this->M_access->getTable('app_haccess',['access_level_id' => $this->level])->row_array();
		if($getHub != NULL){
			if(empty($getHub['access_hub_idx'])){
				$innerHub = $this->origin_hub;
			}else{
				$innerHub = explode(',',$getHub['access_hub_idx']);
			}
		}else{
			$innerHub = $this->origin_hub;
		}
		$this->db->select('*')
		->from('master_hub a')
		->where_in('idx', $innerHub);
		// $result = $this->db->get_compiled_Select();
		$result = $this->db->get()->result_array();
        return $result;
    }

	public function checkHubUser($idx)
    {
        $this->db->select('idx,
			hub_name,
			address,
			city,
			telephone
		')
		->from('master_hub')
		->where('idx', $idx);
		$result = $this->db->get();
        return $result->row_array();
    }

	public function push_notif_riders($token, $msg)
    {
        $auth_key = key_notif();
        // $auth_key = "AAAAtKvdQMY:APA91bFFNMrt0oK9vNxF629GQPoPvIA0YWx4Zi9a2tdmlfpR3ap5mga0E86JOvm9JOsE743OeKnCfsV-YPJAQxUz8-u1df1lnoiQQgxlsxqLQHS7mBOrtGH2YOHExVBtJKrVrI0F4GVO";
		$postdata = json_encode([
			'notification' =>
				[
					'title'         => $msg['title'],
					'body'          => $msg['message'],
					'icon'          => $msg['icon'],
					'click_action'  => $msg['action'],
					'sound'         => $msg['sound'],
					'android_channel_id' => $msg['channel']
				]
			,
			'to' => $token,
			'data' => $msg['data']
		]);
    	$opts = array('http' =>
    	    array(
    	        'method'  => 'POST',
    	        'header'  => 'Content-type: application/json'."\r\n"
    	        			.'Authorization: key='.$auth_key."\r\n",
    	        'content' => $postdata
    	    )
    	);
    	$context  = stream_context_create($opts);
    	$result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
    	if($result) {
    		return true;
    	} else {
    	    return false;
    	}
    }
}
?>