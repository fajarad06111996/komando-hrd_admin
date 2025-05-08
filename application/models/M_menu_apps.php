<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_menu_apps extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelGlobal');
        $this->load->library('Secure');
        $this->office   = $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub      = $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx      = $this->secure->dec($this->session->userdata('JTidx'));
        $this->username = $this->session->userdata('JTuser_id');
        $this->level    = $this->secure->dec($this->session->userdata('JTlevel'));
    }
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER
    // start datatables
    var $select_column4 = array(
        'a.idx',
        'a.location_code',
        'a.location_name',
        'a.postal_code',
        'a.office_idx',
        'a.office_main_location',
        'a.status_origin',
        'a.status_destination',
        'a.status',
        'a.created_by',
        'a.created_on',
        'a.modified_by',
        'a.modified_on',
        'IF(a.office_main_location = 1, "YES", "NO") as main_location',
        'IF(a.status_origin = 1, "YES", "NO") as origin_name',
        'IF(a.status_destination = 1, "YES", "NO") as destination_name',
        'b.office_name'
        
    );//set column field database for datatable select
    var $column_order = array(
        null,
        'a.menu_name'
    ); //set column field database for datatable orderable
    var $column_search = array(
        'a.menu_name'
    ); //set column field database for datatable searchable
    var $order = array('a.menu_name' => 'asc'); // default order 
  
    private function _get_datatables_query() {
    $this->db->select('*,(SELECT user_id FROM user_account WHERE idx = a.created_by) as user_id');
    $this->db->from('setting_menu_appsclient a');
    $this->db->where('a.status_delete', 'N');
    $i = 0;
    foreach ($this->column_search as $item) { // loop column 
        if(@$_POST['search']['value']) { // if datatable send POST for search
            if($i===0) { // first loop
                $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                $this->db->like($item, $_POST['search']['value']);
            } else {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            if(count($this->column_search) - 1 == $i) //last loop
                $this->db->group_end(); //close bracket
        }
        $i++;
    }
        
    $order = $this->order;
    $this->db->order_by(key($order), $order[key($order)]);
    // if(isset($_POST['order'])) { // here order processing
    //     $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    // }  else if(isset($this->order)) {
    // }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from('master_payment_account a');
        $this->db->where('a.status_delete', 'N');
        return $this->db->count_all_results();
    }
    // end datatables
    // CONTOH LAIN MODEL2 DATATABLE SERVERSIDE USER END

    public function queryMPayment($keyword = null)
    {
        if($keyword)
        {
            $search_keyword = " and (agent_code like '%$keyword%' or
                                    agent_name like '%$keyword%' or
                                    address like '%$keyword%' or
                                    email_id like '%$keyword%')";
        }
        else { $search_keyword = ""; }
        
        $result = "select *
                    from master_payment_account
                    where idx > 0" . $search_keyword . "
                    order by agent_name asc";
        return $result;
    }

    public function searchMPayment($idx)
    {
        $result = $this->db->query("select * from master_payment_account where idx = '$idx'")->row_array();
        return $result;
    }
    
    public function getMenuApps($menu_code)
    {
        $result = $this->db->query("select idx from setting_menu_appsclient where menu_code='$menu_code'")->row_array();
        return $result;
    }
    
    public function insertMenuapps($data)
    {
        $this->db->trans_begin();
        
        $this->db->insert('setting_menu_appsclient', $data);
        
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else
    	{

    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function editMenuapps($id)
    {
        $result = $this->db->get_where('setting_menu_appsclient',['idx'=>$id]);
        if($result->num_rows()>0){
            return $result->row_array();
        }else{
            return 0;
        }
    }

    public function updateMenuapps($id, $data)
    {
        $this->db->trans_begin();
        
        $this->db->where('idx', $id);
        $this->db->update('setting_menu_appsclient', $data);
        
        if($this->db->trans_status() === FALSE)
    	{
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else
    	{

    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function deleteMenuapps($id)
    {
        $this->db->where('idx',$id);
        $this->db->update('setting_menu_appsclient',['status_delete'=>'Y']);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function changeStatusMenuapps($data, $id_payment)
    {
        $this->db->where('idx', $id_payment);
        $this->db->update('setting_menu_appsclient', $data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function createAsUserMPayment($data, $query, $id_agent)
    {
        $cek = $this->db->query($query);
        if($cek){
            $this->db->where('idx', $id_agent);
            $this->db->update('master_payment_account', $data);
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
?>