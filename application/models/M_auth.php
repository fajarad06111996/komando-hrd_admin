<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_auth extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->library('secure');
    }
	public function login($user, $pass)
	{
		$this->db->select('id_admin,email,fullname, username, pass_user, status, level, alamat');
		$this->db->from('t_admin');
		$this->db->where('username', $user);
		$this->db->where('status', 1);
		//$this->db->where('pass_user',$pass);
		$data = $this->db->get();
		if ($data->num_rows() == 1) {
			return $data->row();
		} else {
			return false;
		}
	}

	public function check_login($username, $pass_user)
	{
		$this->db->select('a.*,
			(SELECT company_name FROM master_company WHERE idx = a.company_idx) as company_name,
			(SELECT company_code FROM master_company WHERE idx = a.company_idx) as company_code
		');
		$this->db->from('user_account a');
		$this->db->where(['a.user_id'=> $username, 'a.status' => 1, 'a.status_delete' => 'N']);
		$this->db->where_in('a.user_type', [1,2,99]);
		$query2 = $this->db->get();

		// jika user ditemukan (nilainya 1)
		if ($query2->num_rows() == 1) {
			$hash = $query2->row('password');
			if (password_verify($pass_user, $hash)) {
				return $query2->result(); // hasil berupa objek
			} else {
				return 11; // password salah
			}
		} else {
			return 22; // user tidak ditemukan
		}
	}

	public function getOfficeType($office)
	{
		$this->db->select('office_type')
		->from('master_office')
		->where('idx', $office);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function cekRightbar($user_idx)
	{
		$this->db->where('user_idx', $user_idx);
		$query = $this->db->get('setting_rightbar');
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createRightbar($user_idx)
	{
		$this->db->trans_begin();
		$this->db->insert('setting_rightbar', ['user_idx' => $user_idx]);
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 0;
		}else
		{
			$this->db->trans_commit();
			return 1;
		}
	}

	public function logged_id()
	{
		return $this->session->userdata('JTstatusLoginApps');
	}

	public function get($where, $value = FALSE)
	{
		if (!$value) {
			$value = $where;
			$where = 'username';
		}
		$user = $this->db->where($where, $value)->get($this->table)->row_array();
		return $user;
	}

	public function check_password($password, $stored_hash)
	{
		$this->load->library('Passwordhash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
		// check password
		return $this->passwordhash->CheckPassword($password, $stored_hash);
	}

	function insertData($data, $table)
	{
		if ($this->db->insert($table, $data)) {
		return $this->db->affected_rows(); 
		} else { 
		return false; 
		}
		// $this->db->insert($table, $data);
	}

	function getleveluser($username)
	{
		$this->db->select('a.user_level_id,b.level_alias')
		->from('user_account a')
		->join('app_level_access b','a.user_level_id=b.level_id','inner')
		->where('a.user_id', $username)
		->where_in('a.user_type', [1,2,99]);
		$result = $this->db->get();
		return $result->row_array();
	}

	function getAccess($level,$office)
	{
		// $office = $this->secure->dec($office_id);
		$data = array();

		$this->db->select('*');
		$this->db->from('app_uaccess a');
		$this->db->join('app_menu b', 'a.access_menu_id = b.menu_id', 'left');
		$this->db->where('a.access_company_id', $office);
		$this->db->group_start();
		$this->db->where('a.access_level_id', $level);
			$this->db->group_start();
			$this->db->where('b.menu_access', 1);
			$this->db->where('a.access_rolemenu', 1);
			$this->db->group_end();
		$this->db->group_end();
		$this->db->order_by('b.menu_urutan', 'ASC');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				$data['menu'][]		= $row->access_menu_id;
				$data['submenu'][] 	= $row->access_submenu_id;
			}
			return $data;
		} else {
			return 0;
		}
	}

	function getAccessSub($level,$office)
	{
		$data = array();

		$this->db->select('*');
		$this->db->from('app_uaccess');
		$this->db->where('access_company_id', $office);
		$this->db->group_start();
		$this->db->where('access_level_id', $level);
		$this->db->where('access_rolemenu', 2);
		$this->db->group_end();
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				$data['menuchild'][] 		= $row->access_menu_id;
				$data['submenuchild'][]		= $row->access_submenu_id;
			}
			return $data;
		} else {
			return 0;
		}
	}

	function selectAccessDetail($level,$office)
	{
		// $office = $this->secure->dec($office_id);
		$this->db->select('*');
		$this->db->from('app_uaccess');
		$this->db->where('access_company_id', $office);
		$this->db->group_start();
		$this->db->where('access_level_id', $level);
			$this->db->group_start();
			$this->db->where('access_submenu_id <>', '');
			$this->db->where('access_rolemenu', 1);
			$this->db->group_end();
		$this->db->group_end();
		return $this->db->get();
	}

	function selectChildAccessDetail($level,$office)
	{
		// $office = $this->secure->dec($office_id);
		$this->db->select('*');
		$this->db->from('app_uaccess');
		$this->db->where('access_company_id', $office);
		$this->db->group_start();
		$this->db->where('access_level_id', $level);
			$this->db->group_start();
			$this->db->where('access_submenu_id <>', '');
			$this->db->where('access_rolemenu', 2);
			$this->db->group_end();
		$this->db->group_end();
		return $this->db->get();
	}

	// function selectHub()
	// {
	// 	$this->db->select('*')
	// 	->from('app_oaccess')
	// 	->where('')
	// }

	function SelectMenuChildName($data)
	{
		$_query = "SELECT a.*,b.menu_title AS title FROM app_menu a LEFT JOIN app_menu b ON a.menu_parent_id=b.menu_id
							WHERE a.menu_id IN(" . $data . ") AND a.menu_parent_id IS NOT NULL ORDER BY a.menu_parent_id,a.menu_urutan,a.menu_id asc";
		//echo $_query;die();
		return $this->db->query($_query);
	}

	function onlineId($id)
	{
		$result =  $this->db->get_where('user_account',['idx' => $id])->row_array();
		return $result;
	}

	function onlineUname($username)
	{
		$result =  $this->db->get_where('user_account',['user_id' => $username])->row_array();
		return $result;
	}

	function updateToOnline($us)
	{
		$data = array(
			'status_login' => 1
		);
		$this->db->where('user_id', $us);
		$this->db->update('user_account',$data);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function updateToOffline($us)
	{
		$data = array(
			'status_login' => 0
		);
		$this->db->where('user_id', $us);
		$this->db->update('user_account',$data);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function onlineUpdateAll($us)
	{
		$data = array(
			'status_login' => 0
		);
		$this->db->where('user_id !=', $us);
		$this->db->update('user_account',$data);
		// if($this->db->affected_rows() > 0){
		// 	return true;
		// }else{
		// 	return false;
		// }
		return true;
	}

	public function insertToken($data)
	{
		$this->db->trans_begin();
		$this->db->insert('user_token', $data);
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 0;
		}else
		{
			$this->db->trans_commit();
			return 1;
		}
	}

	public function updateToken($email, $data)
	{
		$this->db->trans_begin();
		$this->db->where('email_id',$email);
		$this->db->update('user_token', $data);
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 0;
		}else
		{
			$this->db->trans_commit();
			return 1;
		}
	}

	public function updatePassword($email,$token,$data)
	{
		$this->db->trans_begin();
		$this->db->where('email_id',$email);
		$this->db->update('user_account', $data);

		$this->db->where(['email_id'=>$email,'token'=>$token]);
		$this->db->delete('user_token');
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 0;
		}else
		{
			$this->db->trans_commit();
			return 1;
		}
	}

	public function get_employee($empid){
        $this->db->select('a.*')
        ->from('master_employee a')
        ->where('a.employee_id', $empid);
        // $result = $this->db->get_compiled_select();
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function insertAtt($data)
    {
        $this->db->trans_begin();
        $result = $this->db->insert('attendance_employee', $data);
        if($this->db->trans_status() === FALSE){
    		$this->db->trans_rollback();
    		return 0;
    	}else{
    		$this->db->trans_commit();
    		return 1;
    	}
    }
}

/* End of file M_auth.php */
/* Location: ./application/models/M_auth.php */