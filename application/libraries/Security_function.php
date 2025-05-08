<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Security_function {
	protected $_ci;
	
  function __construct(){
    $this->_ci =&get_instance();
    $this->_ci->load->model('M_menu','mMenu');
    $this->_ci->load->library('secure');
	}
	
	function windows(){
    ob_start(); 
    system("ipconfig /all"); 
    $mycom=ob_get_contents(); 
    ob_clean(); 

    $findme = "Physical";
    $pmac   = strpos($mycom, $findme); 
    $mac    = substr($mycom,($pmac+36),17); 
    $md5    = $mac;
    return $md5;
	}
	
	function linux(){
    $mac=null;
    //exec("/sbin/ifconfig eth0 | grep HWaddr", $output);
    exec("/sbin/ifconfig em1 | grep HWaddr", $output);
    foreach($output as $line){
      if (preg_match("/(.*)em1(.*)/", $line)){
        $mac = $line;
        //$mac = str_replace("eth0      Link encap:Ethernet  HWaddr ","",$mac);
        $mac = str_replace("em1       Link encap:Ethernet  HWaddr ","",$mac);
      }
    }
    $mac = trim(strtoupper($mac));
			
    if(empty($mac)){
      exec("/sbin/ifconfig eth0 | grep HWaddr", $output);
      foreach($output as $line){
        if (preg_match("/(.*)em1(.*)/", $line)){
          $mac = $line;
          $mac = str_replace("eth0      Link encap:Ethernet  HWaddr ","",$mac);
        }
      }
      $mac = trim(strtoupper($mac));
    }
			
			
    if(empty($mac)){
      exec("ifconfig em1 | grep HWaddr", $output);
      foreach($output as $line){
        if (preg_match("/(.*)em1(.*)/", $line)){
          $mac = $line;
          $mac = str_replace("em1       Link encap:Ethernet  HWaddr ","",$mac);
        }
      }
      $mac = trim(strtoupper($mac));
    }
			
			return $mac;
	}
	
	function cekmacaddress(){
    $config=$this->linux();
    if(empty($config))	
      $config=$this->windows();
      $ipconfig	=trim(md5($config));
    return $ipconfig;
	} 
	
	function macaddress(){
    $config=$this->linux();
      if(empty($config))	$config=$this->windows();
      $ipconfig	=trim($config);
    return $ipconfig;
	} 

	function hakaccess(){
    // $sett_permissions	= $this->_ci->config->item('permissions');
    $hak_access 		  = $this->_ci->session->userdata('JThak_access');
    $username			    = $this->_ci->session->userdata('JTuser_id');
    $level			      = $this->_ci->secure->dec($this->_ci->session->userdata('JTlevel'));
    $root     	      = "root";
    $pass_root        = "3fbaf8cb25497efa9f918ba60133249b";
    $caccessall  	    = array("root","admin");
	if($username==$this->_ci->config->item('user_root')):
    $conf_permissions = array('r','w','c','x');
    $sett_permissions	= $conf_permissions;
    $nilai            = $name = null;
    $point            = array();
    $param            = $this->_ci->mMenu->SelectMenuParent(0);
    foreach($param->result() as $row){
      $array['menu'][] = $row->menu_id;
      $nilai="";
        $result=$this->_ci->mMenu->getSubMenu($row->menu_id);
        foreach($result->result() as $rowd):
        if(!empty($row->menu_id)):
          $nilai  .= $rowd->menu_id.",";
          $name   .= $rowd->menu_id.",";
        endif;
        endforeach;
        $nilai 	= substr($nilai,0,strlen($nilai)-1);
        $array['submenu'][] = $nilai;
      }
    
    $name         = substr($name,0,strlen($name)-1);
    $access       = array();
    $detail_menu  = $this->_ci->mMenu->getSubModul($name);
    foreach($detail_menu->result() as $row){
      $access[]=str_replace(" ","",strtolower($row->menu_title));
      foreach($sett_permissions as $kunci => $key){
        $point[]=strtolower(str_replace(' ','',$row->menu_title))."-".$kunci;
      }
    }
    
    $credentials = array('JTpermissions'	=> $point,'JTbypermissions'	=> "reload");
    $this->_ci->session->set_userdata($credentials);
    return $point;
	
	else: 

    $nilai  = null;
    $point  = null;
    $param  = $this->_ci->mMenu->selectAccessDetail($level);
    foreach($param->result() as $row){
      if(!empty($row->submenu_id)):
        $nilai  .= $row->submenu_id.",";
        $point  .= $row->permissions_id.",";
      endif;
    }
    $nilai 	      = substr($nilai,0,strlen($nilai)-1);
    $point 	      = substr($point,0,strlen($point)-1);
    $parameter    = array();
    $detail_menu  = $this->_ci->mMenu->getSubModul($nilai);

    foreach($detail_menu->result() as $row):
      $parameter[]=str_replace(" ","",strtolower($row->menu_title));
    endforeach;

    $point 	      = explode(",",$point);
    
    $credentials  = array('JTpermissions'	=> $point,'JTbypermissions'	=> "reload");
      $this->_ci->session->set_userdata($credentials);
    return $point;
	
	endif;
	
	}

	function subhakAccess(){
    // $sett_permissions	= $this->_ci->config->item('permissions');
    $hak_access 		  = $this->_ci->session->userdata('JThak_access');
    $username			    = $this->_ci->session->userdata('JTuser_id');
    $level			      = $this->_ci->secure->dec($this->_ci->session->userdata('JTlevel'));
    $root     	      = "root";
    $pass_root        = "3fbaf8cb25497efa9f918ba60133249b";
    $caccessall  	    = array("root","admin");
	if($username==$this->_ci->config->item('user_root')):
    $conf_permissions = array('r','w','c','x');
    $sett_permissions	= $conf_permissions;
    $nilai            = $name = null;
    $point            = array();
    $param            = $this->_ci->mMenu->SelectMenuParent(0);
    foreach($param->result() as $row){
      $array['menu'][] = $row->menu_id;
      $nilai="";
        $result=$this->_ci->mMenu->getSubMenu($row->menu_id);
        foreach($result->result() as $rowd):
        if(!empty($row->menu_id)):
          $nilai  .= $rowd->menu_id.",";
          $name   .= $rowd->menu_id.",";
        endif;
        endforeach;
        $nilai 	= substr($nilai,0,strlen($nilai)-1);
        $array['submenu'][] = $nilai;
      }
    
    $name         = substr($name,0,strlen($name)-1);
    $access       = array();
    $detail_menu  = $this->_ci->mMenu->getChildSubModul($name);
    foreach($detail_menu->result() as $row){
      $access[]=str_replace(" ","",strtolower($row->menu_title));
      foreach($sett_permissions as $kunci => $key){
        $point[]=strtolower(str_replace(' ','',$row->menu_title))."-".$kunci;
      }
    }
    
    $credentialsChild = array('JTsubpermissions'	=> $point,'JTbypermissions'	=> "reload");
    $this->_ci->session->set_userdata($credentialsChild);
    return $point;
	
	else: 

    $nilai  = null;
    $point  = null;
    $param  = $this->_ci->mMenu->selectChildAccessDetail($level);
    foreach($param->result() as $row){
      if(!empty($row->submenu_id)):
        $nilai  .= $row->submenu_id.",";
        $point  .= $row->permissions_id.",";
      endif;
    }
    $nilai 	      = substr($nilai,0,strlen($nilai)-1);
    $point 	      = substr($point,0,strlen($point)-1);
    $parameter    = array();
    $detail_menu  = $this->_ci->mMenu->getChildSubModul($nilai);

    foreach($detail_menu->result() as $row):
      $parameter[]=str_replace(" ","",strtolower($row->menu_title));
    endforeach;

    $point 	      = explode(",",$point);
    
    $credentialsChild  = array('JTsubpermissions'	=> $point,'JTbypermissions'	=> "reload");
      $this->_ci->session->set_userdata($credentialsChild);
    return $point;
	
	endif;
	
	}

  function officeAccess()
  {
    $level    = $this->_ci->secure->dec($this->_ci->session->userdata('JTlevel'));
    $off_idx  = null;
    $param    = $this->_ci->mMenu->companyAccess($level)->row_array();
    if(empty($param)){
      return false;
    }else{
      $access   = explode(',',$param['access_company_idx']);
      if(count($access) > 1){
        return true;
      }else{
        return false;
      }
    }
  }

  function counterAccess()
  {
    $level    = $this->_ci->secure->dec($this->_ci->session->userdata('JTlevel'));
    $office   = $this->_ci->secure->dec($this->_ci->session->userdata('JToffice_id'));
    $param    = $this->_ci->mMenu->counterAccess($level, $office)->row_array();
    if(empty($param)){
      return false;
    }else{
      $access   = explode(',',$param['access_counter_idx']);
      if(count($access) > 1){
        return true;
      }else{
        return false;
      }
    }
  }
	
	function permissions($controller){
    $locked = $this->_ci->session->userdata('JTpermissions');
    if(empty($locked)) $locked=$this->hakaccess();

    if (!in_array($controller, $locked)) {
      return 0;
    }else{
      return 1;
    }		
	}

	function permissionsChild($controller){
    $locked = $this->_ci->session->userdata('JTsubpermissions');
    if(empty($locked)) $locked=$this->subhakAccess();

    if (!in_array($controller, $locked)) {
      return 0;
    }else{
      return 1;
    }		
	}

  // function permissionHub($)
    
    
}
?>